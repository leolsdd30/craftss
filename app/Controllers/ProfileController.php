<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\CraftsmanProfile;
use App\Models\Review;
use App\Models\Favorite;
use App\Auth\Middleware;

class ProfileController extends Controller
{
    /**
     * Show a detailed profile of a single user (Homeowner or Craftsman).
     */
    public function show($username = null)
    {
        $userModel = new User();
        $user = null;

        // 1. Direct username lookup (e.g., /profile/ahmed_dev)
        if ($username) {
            $user = $userModel->findByUsername($username);
        }
        // 2. Legacy lookup by ID, immediately redirect to clean URL
        elseif (isset($_GET['id'])) {
            $user = $userModel->findById($_GET['id']);
            if ($user && !empty($user['username'])) {
                header("Location: " . APP_URL . "/profile/" . $user['username'], true, 301);
                exit;
            }
        }
        // 3. Fallback for just /profile - redirect to own profile
        elseif (isset($_SESSION['user_id'])) {
            $user = $userModel->findById($_SESSION['user_id']);
            if ($user && !empty($user['username'])) {
                header("Location: " . APP_URL . "/profile/" . $user['username']);
                exit;
            }
        }

        if (!$user) {
            // Show the proper styled 404 page instead of raw text
            http_response_code(404);
            $viewPath = BASE_PATH . '/resources/views/errors/404.php';
            if (file_exists($viewPath)) {
                require $viewPath;
            } else {
                echo "404 - User Not Found";
            }
            exit;
        }

        // We use the internal $id for related queries
        $id = $user['id'];

        $craftsmanDetails = null;

        if ($user['role'] === 'craftsman') {
            $craftsmanModel = new CraftsmanProfile();
            $craftsmanDetails = $craftsmanModel->findByUserId($id);

            // If they haven't created a specific profile record yet
            if (!$craftsmanDetails) {
                $craftsmanDetails = [
                    'service_category' => 'General Handyman',
                    'hourly_rate' => 0.00,
                    'bio' => '',
                    'portfolio_images' => '[]',
                    'is_verified' => false,
                    'created_at' => $user['created_at']
                ];
            }
        }

        $reviews = [];
        $rating = ['avg_rating' => 0, 'total_reviews' => 0];
        $isFavorite = false;

        if ($user['role'] === 'craftsman') {
            $reviewModel = new Review();
            $reviews = $reviewModel->getReviewsForCraftsman($id);
            $rating = $reviewModel->getCraftsmanRating($id);

            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $id && ($_SESSION['role'] ?? '') !== 'admin') {
                $favoriteModel = new Favorite();
                $isFavorite = $favoriteModel->isFavorite($_SESSION['user_id'], $id);
            }
        }

        // Prepare SEO Tags
        $fullName = $user['first_name'] . ' ' . $user['last_name'];
        $ogTitle = $fullName . ' - Profile on Crafts';
        $metaDesc = "View the profile of {$fullName} on Crafts.";

        if ($user['role'] === 'craftsman') {
            $service = $craftsmanDetails['service_category'] ?? 'Professional';
            $loc = !empty($user['wilaya']) ? " in {$user['wilaya']}" : "";
            $metaDesc = "Hire {$fullName}, a skilled {$service}{$loc} on Crafts. Read reviews and view their portfolio.";
        }

        $ogImage = APP_URL . get_profile_picture_url($user['profile_picture'] ?? 'default.png', $user['first_name'], $user['last_name']);

        $this->view('layouts/app', [
            'pageTitle' => $fullName . ' - Profile',
            'contentView' => 'profile/show',
            'user' => $user,
            'craftsmanDetails' => $craftsmanDetails,
            'reviews' => $reviews,
            'rating' => $rating,
            'isFavorite' => $isFavorite,
            'metaDescription' => $metaDesc,
            'ogTitle' => $ogTitle,
            'ogDescription' => $metaDesc,
            'ogImage' => $ogImage
        ]);
    }

    /**
     * Show the edit profile form
     */
    public function edit()
    {
        Middleware::requireLogin();
        $id = $_SESSION['user_id'];

        $userModel = new User();
        $user = $userModel->findById($id);

        $craftsmanDetails = null;
        if ($user['role'] === 'craftsman') {
            $craftsmanModel = new CraftsmanProfile();
            $craftsmanDetails = $craftsmanModel->findByUserId($id);
        }

        $this->view('layouts/app', [
            'pageTitle' => 'Edit Profile - Crafts',
            'contentView' => 'profile/edit',
            'user' => $user,
            'craftsmanDetails' => $craftsmanDetails
        ]);
    }

    /**
     * Process profile updates
     */
    public function update()
    {
        Middleware::requireLogin();
        Middleware::verifyCsrfToken();
        $id = $_SESSION['user_id'];

        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $phone = trim($_POST['phone_number'] ?? '');
        $wilaya = trim($_POST['wilaya'] ?? '');
        $username = trim($_POST['username'] ?? '');

        // Handle basic User table updates
        $userModel = new User();
        $user = $userModel->findById($id);

        // Username Logic
        $usernameUpdateSql = "";
        $usernameParams = [];
        if (!empty($username) && $username !== $user['username']) {
            // Check regex format server-side
            if (preg_match('/^[a-zA-Z][a-zA-Z0-9_-]{2,}$/', $username)) {
                // Sanitize slug
                $username = strtolower(trim($username));

                // Check if 14 days have passed
                $canUpdate = true;
                if (!empty($user['username_updated_at'])) {
                    $lastUpdated = strtotime($user['username_updated_at']);
                    if (time() - $lastUpdated < (14 * 24 * 60 * 60)) {
                        $canUpdate = false;
                    }
                }

                if ($canUpdate) {
                    // Check if unique
                    $existing = $userModel->findByUsername($username);
                    if (!$existing || $existing['id'] == $id) {
                        $usernameUpdateSql = ", username = :username, username_updated_at = NOW()";
                        $usernameParams['username'] = $username;
                    }
                }
            }
        }

        $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, phone_number = :phone_number, wilaya = :wilaya {$usernameUpdateSql} WHERE id = :id";
        $params = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone_number' => $phone,
            'wilaya' => $wilaya,
            'id' => $id
        ];

        $userModel->executeQuery($sql, array_merge($params, $usernameParams));

        // Handle Profile Picture Removal
        if (isset($_POST['remove_picture']) && $_POST['remove_picture'] == '1') {
            $userModel->executeQuery("UPDATE users SET profile_picture = 'default.png' WHERE id = :id", [
                'id' => $id
            ]);
            if (!empty($user['profile_picture']) && $user['profile_picture'] !== 'default.png') {
                $oldFile = BASE_PATH . '/public/uploads/profile/' . $user['profile_picture'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
        }
        // Handle Profile Picture Upload
        elseif (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['profile_picture']['tmp_name'];
            $name = basename($_FILES['profile_picture']['name']);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

            $maxFileSize = 5 * 1024 * 1024;
            if ($_FILES['profile_picture']['size'] > $maxFileSize) {
                // File too large — silently skip
            }
            elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $detectedMime = $finfo->file($tmpName);
                $imageInfo = @getimagesize($tmpName);

                if (in_array($detectedMime, $allowedMimes) && $imageInfo !== false) {
                    $newName = time() . '_' . uniqid() . '.' . $ext;
                    $uploadDir = BASE_PATH . '/public/uploads/profile/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    if (move_uploaded_file($tmpName, $uploadDir . $newName)) {
                        $userModel->executeQuery("UPDATE users SET profile_picture = :pic WHERE id = :id", [
                            'pic' => $newName,
                            'id' => $id
                        ]);
                    }
                }
            }
        }

        // Handle Craftsman specific updates
        if ($user['role'] === 'craftsman') {
            $craftsmanModel = new CraftsmanProfile();

            $data = [
                'service_category' => $_POST['service_category'] ?? 'General Handyman',
                'hourly_rate' => $_POST['hourly_rate'] ?? 0,
                'bio' => $_POST['bio'] ?? ''
            ];

            // --- Portfolio Image Management ---
            $uploadDir = BASE_PATH . '/public/uploads/portfolio/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $existing = $craftsmanModel->findByUserId($id);
            $oldImages = [];
            if ($existing && !empty($existing['portfolio_images'])) {
                $oldImages = json_decode($existing['portfolio_images'], true) ?: [];
            }

            $keptImages = $_POST['existing_images'] ?? [];

            foreach ($oldImages as $oldImg) {
                if (!in_array($oldImg, $keptImages)) {
                    $filePath = $uploadDir . $oldImg;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            $newImages = [];
            if (!empty($_FILES['portfolio_images']['name'][0])) {
                $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                $maxFiles = 10 - count($keptImages);

                for ($i = 0; $i < min(count($_FILES['portfolio_images']['name']), $maxFiles); $i++) {
                    if ($_FILES['portfolio_images']['error'][$i] === UPLOAD_ERR_OK) {
                        $tmpName = $_FILES['portfolio_images']['tmp_name'][$i];
                        $origName = basename($_FILES['portfolio_images']['name'][$i]);
                        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

                        if (in_array($ext, $allowedExts) && $_FILES['portfolio_images']['size'][$i] <= 5 * 1024 * 1024) {
                            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                            $finfo = new \finfo(FILEINFO_MIME_TYPE);
                            $detectedMime = $finfo->file($tmpName);
                            $imageInfo = @getimagesize($tmpName);

                            if (in_array($detectedMime, $allowedMimes) && $imageInfo !== false) {
                                $newName = time() . '_' . uniqid() . '_' . $i . '.' . $ext;
                                if (move_uploaded_file($tmpName, $uploadDir . $newName)) {
                                    $newImages[] = $newName;
                                }
                            }
                        }
                    }
                }
            }

            $data['portfolio_images'] = array_merge($keptImages, $newImages);
            $craftsmanModel->updateOrCreate($id, $data);
        }

        $user = $userModel->findById($id);
        header('Location: ' . APP_URL . '/profile/' . $user['username']);
        exit;
    }

    /**
     * Publish or unpublish the marketing card to the marketplace.
     */
    public function publish()
    {
        Middleware::requireLogin();
        $id = $_SESSION['user_id'];
        $role = $_SESSION['role'] ?? '';

        if ($role !== 'craftsman') {
            header('Location: ' . APP_URL . '/profile?id=' . $id);
            exit;
        }

        $status = $_POST['status'] ?? 1;

        $userModel = new User();
        $user = $userModel->findById($id);
        $craftsmanModel = new CraftsmanProfile();

        if ($status == 1) {
            $craftsmanDetails = $craftsmanModel->findByUserId($id);

            if (empty($craftsmanDetails['id']) || empty($user['wilaya']) || empty($user['phone_number'])) {
                header('Location: ' . APP_URL . '/profile/' . ($user['username'] ?? $id) . '?error=incomplete');
                exit;
            }
        }

        $craftsmanModel->setPublishStatus($id, $status);

        header('Location: ' . APP_URL . '/profile/' . ($user['username'] ?? $id));
        exit;
    }

    /**
     * AJAX endpoint: Check if a username is available.
     */
    public function checkUsername()
    {
        header('Content-Type: application/json');

        $username = trim($_GET['username'] ?? '');
        $currentUserId = $_SESSION['user_id'] ?? null;

        if (empty($username)) {
            echo json_encode(['available' => false, 'message' => 'Username is empty.']);
            exit;
        }

        $userModel = new User();
        $existing = $userModel->findByUsername($username);

        if ($existing && $existing['id'] != $currentUserId) {
            echo json_encode(['available' => false, 'message' => 'This username is already taken.']);
        } else {
            echo json_encode(['available' => true, 'message' => 'Username is available!']);
        }
        exit;
    }
}