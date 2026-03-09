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
    // [SECURITY] Allowed MIME types for image uploads
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    // [SECURITY] Max upload size: 5MB
    private const MAX_UPLOAD_BYTES = 5 * 1024 * 1024;

    /**
     * Show a detailed profile of a single user (Homeowner or Craftsman).
     */
    public function show($username = null)
    {
        $userModel = new User();
        $user      = null;

        // 1. Direct username lookup (e.g., /profile/ahmed_dev)
        if ($username) {
            $user = $userModel->findByUsername($username);
        }
        // 2. Legacy lookup by ID → redirect to clean URL
        elseif (isset($_GET['id'])) {
            $userId = (int) $_GET['id'];
            $user   = $userModel->findById($userId);
            if ($user && !empty($user['username'])) {
                header("Location: " . APP_URL . "/profile/" . rawurlencode($user['username']), true, 301);
                exit;
            }
        }
        // 3. Fallback: redirect to own profile
        elseif (isset($_SESSION['user_id'])) {
            $user = $userModel->findById($_SESSION['user_id']);
            if ($user && !empty($user['username'])) {
                header("Location: " . APP_URL . "/profile/" . rawurlencode($user['username']));
                exit;
            }
        }

        if (!$user) {
            http_response_code(404);
            $viewPath = BASE_PATH . '/resources/views/errors/404.php';
            if (file_exists($viewPath)) require $viewPath;
            else echo "404 - User not found.";
            exit;
        }

        $id              = $user['id'];
        $craftsmanDetails = null;

        if ($user['role'] === 'craftsman') {
            $craftsmanModel  = new CraftsmanProfile();
            $craftsmanDetails = $craftsmanModel->findByUserId($id);

            if (!$craftsmanDetails) {
                $craftsmanDetails = [
                    'service_category'  => 'General Handyman',
                    'hourly_rate'       => 0.00,
                    'bio'               => '',
                    'portfolio_images'  => '[]',
                    'is_verified'       => false,
                    'created_at'        => $user['created_at']
                ];
            }
        }

        $reviews    = [];
        $rating     = ['avg_rating' => 0, 'total_reviews' => 0];
        $isFavorite = false;

        if ($user['role'] === 'craftsman') {
            $reviewModel = new Review();
            $reviews     = $reviewModel->getReviewsForCraftsman($id);
            $rating      = $reviewModel->getCraftsmanRating($id);

            if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'homeowner') {
                $favoriteModel = new Favorite();
                $isFavorite    = $favoriteModel->isFavorite($_SESSION['user_id'], $id);
            }
        }

        $fullName  = $user['first_name'] . ' ' . $user['last_name'];
        $ogTitle   = $fullName . ' - Profile on CraftConnect';
        $metaDesc  = "View the profile of {$fullName} on CraftConnect.";

        if ($user['role'] === 'craftsman') {
            $service  = $craftsmanDetails['service_category'] ?? 'Professional';
            $loc      = !empty($user['wilaya']) ? " in {$user['wilaya']}" : "";
            $metaDesc = "Hire {$fullName}, a skilled {$service}{$loc} on CraftConnect. Read reviews and view their portfolio.";
        }

        $ogImage = APP_URL . get_profile_picture_url(
            $user['profile_picture'] ?? 'default.png',
            $user['first_name'],
            $user['last_name']
        );

        $this->view('layouts/app', [
            'pageTitle'       => $fullName . ' - Profile',
            'contentView'     => 'profile/show',
            'user'            => $user,
            'craftsmanDetails'=> $craftsmanDetails,
            'reviews'         => $reviews,
            'rating'          => $rating,
            'isFavorite'      => $isFavorite,
            'metaDescription' => $metaDesc,
            'ogTitle'         => $ogTitle,
            'ogDescription'   => $metaDesc,
            'ogImage'         => $ogImage
        ]);
    }

    /**
     * Show the edit profile form.
     */
    public function edit()
    {
        Middleware::requireLogin();
        $id = $_SESSION['user_id'];

        $userModel       = new User();
        $user            = $userModel->findById($id);
        $craftsmanDetails = null;

        if ($user['role'] === 'craftsman') {
            $craftsmanModel  = new CraftsmanProfile();
            $craftsmanDetails = $craftsmanModel->findByUserId($id);
        }

        $this->view('layouts/app', [
            'pageTitle'       => 'Edit Profile - CraftConnect',
            'contentView'     => 'profile/edit',
            'user'            => $user,
            'craftsmanDetails'=> $craftsmanDetails
        ]);
    }

    /**
     * Process profile updates.
     */
    public function update()
    {
        Middleware::requireLogin();
        Middleware::verifyCsrfToken();
        $id = $_SESSION['user_id'];

        $firstName = trim($_POST['first_name'] ?? '');
        $lastName  = trim($_POST['last_name'] ?? '');
        $phone     = trim($_POST['phone_number'] ?? '');
        $wilaya    = trim($_POST['wilaya'] ?? '');
        $username  = trim($_POST['username'] ?? '');

        // [SECURITY] Input length limits
        if (strlen($firstName) > 100 || strlen($lastName) > 100) {
            // Silently truncate
            $firstName = mb_substr($firstName, 0, 100);
            $lastName  = mb_substr($lastName, 0, 100);
        }
        if (strlen($phone) > 20) $phone = mb_substr($phone, 0, 20);

        $userModel = new User();
        $user      = $userModel->findById($id);

        // Username update logic
        $usernameUpdateSql = "";
        $usernameParams    = [];
        if (!empty($username) && $username !== $user['username']) {
            if (preg_match('/^[a-zA-Z][a-zA-Z0-9_-]{2,29}$/', $username)) {
                $username  = strtolower(trim($username));
                $canUpdate = true;

                if (!empty($user['username_updated_at'])) {
                    $lastUpdated = strtotime($user['username_updated_at']);
                    if (time() - $lastUpdated < (14 * 24 * 60 * 60)) {
                        $canUpdate = false;
                    }
                }

                if ($canUpdate) {
                    $existing = $userModel->findByUsername($username);
                    if (!$existing || (int)$existing['id'] === (int)$id) {
                        $usernameUpdateSql        = ", username = :username, username_updated_at = NOW()";
                        $usernameParams['username'] = $username;
                    }
                }
            }
        }

        $sql    = "UPDATE users SET first_name = :first_name, last_name = :last_name, phone_number = :phone_number, wilaya = :wilaya {$usernameUpdateSql} WHERE id = :id";
        $params = [
            'first_name'   => $firstName,
            'last_name'    => $lastName,
            'phone_number' => $phone,
            'wilaya'       => $wilaya,
            'id'           => $id
        ];

        $userModel->executeQuery($sql, array_merge($params, $usernameParams));

        // Handle Profile Picture Removal
        if (isset($_POST['remove_picture']) && $_POST['remove_picture'] == '1') {
            $userModel->executeQuery("UPDATE users SET profile_picture = 'default.png' WHERE id = :id", ['id' => $id]);
            if (!empty($user['profile_picture']) && $user['profile_picture'] !== 'default.png') {
                $oldFile = BASE_PATH . '/public/uploads/profile/' . $user['profile_picture'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
        }
        // Handle Profile Picture Upload
        elseif (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $uploadError = $this->handleProfilePictureUpload(
                $_FILES['profile_picture'],
                $id,
                $user,
                $userModel
            );
            // $uploadError is a string message if failed, null on success — can surface to user if needed
        }

        // Handle Craftsman specific updates
        if ($user['role'] === 'craftsman') {
            $craftsmanModel = new CraftsmanProfile();

            $bio          = trim($_POST['bio'] ?? '');
            $hourlyRate   = (float) ($_POST['hourly_rate'] ?? 0);
            $category     = trim($_POST['service_category'] ?? 'General Handyman');

            // [SECURITY] Input length limits
            if (strlen($bio) > 2000) $bio = mb_substr($bio, 0, 2000);

            $data = [
                'service_category' => $category,
                'hourly_rate'      => max(0, $hourlyRate),
                'bio'              => $bio
            ];

            // Portfolio Image Management
            $uploadDir = BASE_PATH . '/public/uploads/portfolio/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $existing   = $craftsmanModel->findByUserId($id);
            $oldImages  = [];
            if ($existing && !empty($existing['portfolio_images'])) {
                $oldImages = json_decode($existing['portfolio_images'], true) ?: [];
            }

            // Only keep filenames that actually exist on disk (prevent path traversal via kept images)
            $keptImagesRaw = $_POST['existing_images'] ?? [];
            $keptImages    = [];
            foreach ($keptImagesRaw as $img) {
                $safe = basename($img); // Strip any directory traversal
                if ($safe && file_exists($uploadDir . $safe)) {
                    $keptImages[] = $safe;
                }
            }

            // Delete removed images from disk
            foreach ($oldImages as $oldImg) {
                if (!in_array($oldImg, $keptImages, true)) {
                    $filePath = $uploadDir . basename($oldImg);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }
            }

            // Upload new portfolio images
            $newImages = [];
            if (!empty($_FILES['portfolio_images']['name'][0])) {
                $maxFiles = max(0, 10 - count($keptImages));

                for ($i = 0; $i < min(count($_FILES['portfolio_images']['name']), $maxFiles); $i++) {
                    if ($_FILES['portfolio_images']['error'][$i] !== UPLOAD_ERR_OK) continue;

                    $tmpName = $_FILES['portfolio_images']['tmp_name'][$i];
                    $size    = $_FILES['portfolio_images']['size'][$i];

                    // [SECURITY] File size check
                    if ($size > self::MAX_UPLOAD_BYTES) continue;

                    // [SECURITY] Verify actual MIME type
                    $mime = $this->getRealMimeType($tmpName);
                    if (!in_array($mime, self::ALLOWED_MIME_TYPES, true)) continue;

                    $ext     = $this->mimeToExt($mime);
                    $newName = bin2hex(random_bytes(16)) . '.' . $ext;

                    if (move_uploaded_file($tmpName, $uploadDir . $newName)) {
                        $newImages[] = $newName;
                    }
                }
            }

            $data['portfolio_images'] = array_merge($keptImages, $newImages);
            $craftsmanModel->updateOrCreate($id, $data);
        }

        // Update session name in case it changed
        $_SESSION['name'] = $firstName;

        header('Location: ' . APP_URL . '/profile/' . rawurlencode($user['username'] ?? $id));
        exit;
    }

    /**
     * Publish or unpublish the craftsman's marketplace card.
     */
    public function publish()
    {
        Middleware::requireLogin();
        Middleware::verifyCsrfToken();

        $id   = $_SESSION['user_id'];
        $role = $_SESSION['role'] ?? '';

        if ($role !== 'craftsman') {
            header('Location: ' . APP_URL . '/profile?id=' . $id);
            exit;
        }

        $status         = (int) ($_POST['status'] ?? 1);
        $craftsmanModel = new CraftsmanProfile();

        if ($status === 1) {
            $userModel        = new User();
            $user             = $userModel->findById($id);
            $craftsmanDetails = $craftsmanModel->findByUserId($id);

            if (empty($craftsmanDetails['id']) || empty($user['wilaya']) || empty($user['phone_number'])) {
                header('Location: ' . APP_URL . '/profile?id=' . $id . '&error=incomplete');
                exit;
            }
        } else {
            $userModel = new User();
            $user      = $userModel->findById($id);
        }

        $craftsmanModel->setPublishStatus($id, $status);

        header('Location: ' . APP_URL . '/profile/' . rawurlencode($user['username'] ?? $id));
        exit;
    }

    /**
     * AJAX endpoint: Check if a username is available.
     */
    public function checkUsername()
    {
        header('Content-Type: application/json');

        $username      = trim($_GET['username'] ?? '');
        $currentUserId = $_SESSION['user_id'] ?? null;

        if (empty($username)) {
            echo json_encode(['available' => false, 'message' => 'Username is empty.']);
            exit;
        }

        $userModel = new User();
        $existing  = $userModel->findByUsername($username);

        if ($existing && (int)$existing['id'] !== (int)$currentUserId) {
            echo json_encode(['available' => false, 'message' => 'This username is already taken.']);
        } else {
            echo json_encode(['available' => true, 'message' => 'Username is available!']);
        }
        exit;
    }

    // ─── Private Helpers ──────────────────────────────────────────────────────

    /**
     * Handle profile picture upload with full validation.
     * Returns null on success, error string on failure.
     */
    private function handleProfilePictureUpload(array $file, int $userId, array $user, User $userModel): ?string
    {
        $tmpName = $file['tmp_name'];
        $size    = $file['size'];

        // [SECURITY] File size check
        if ($size > self::MAX_UPLOAD_BYTES) {
            return "Profile picture must be under 5MB.";
        }

        // [SECURITY] Verify real MIME type from binary content, not extension
        $mime = $this->getRealMimeType($tmpName);
        if (!in_array($mime, self::ALLOWED_MIME_TYPES, true)) {
            return "Invalid file type. Please upload a JPG, PNG, GIF, or WebP image.";
        }

        $ext       = $this->mimeToExt($mime);
        // [SECURITY] Cryptographically random filename
        $newName   = bin2hex(random_bytes(16)) . '.' . $ext;
        $uploadDir = BASE_PATH . '/public/uploads/profile/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (!move_uploaded_file($tmpName, $uploadDir . $newName)) {
            return "Could not save the uploaded file.";
        }

        // Delete old picture from disk
        if (!empty($user['profile_picture']) && $user['profile_picture'] !== 'default.png') {
            $oldFile = $uploadDir . basename($user['profile_picture']);
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $userModel->executeQuery("UPDATE users SET profile_picture = :pic WHERE id = :id", [
            'pic' => $newName,
            'id'  => $userId
        ]);

        return null;
    }

    /**
     * Get the real MIME type of a file using finfo (not extension).
     */
    private function getRealMimeType(string $tmpPath): string
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        return $finfo->file($tmpPath) ?: 'application/octet-stream';
    }

    /**
     * Map a MIME type to a safe file extension.
     */
    private function mimeToExt(string $mime): string
    {
        return [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            'image/webp' => 'webp',
        ][$mime] ?? 'jpg';
    }
}
