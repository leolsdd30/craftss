<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Auth\Middleware;
use App\Models\User;
use App\Models\CraftsmanProfile;
use App\Models\Notification;
use App\Models\Stats;

class AdminController extends Controller
{
    /**
     * Admin Dashboard with platform stats.
     */
    public function dashboard()
    {
        Middleware::requireAdmin();

        $db = \App\Database\Database::getInstance()->getConnection();

        $statsModel = new Stats();
        $stats = $statsModel->getDashboardMetrics();

        // Recent users — include profile_picture for avatar display
        $stmt = $db->query(
            "SELECT id, first_name, last_name, email, role, is_active, created_at, username, profile_picture
             FROM users ORDER BY created_at DESC LIMIT 10"
        );
        $recentUsers = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->view('layouts/app', [
            'pageTitle'   => 'Admin Dashboard - Crafts',
            'contentView' => 'admin/dashboard',
            'stats'       => $stats,
            'recentUsers' => $recentUsers,
        ]);
    }

    /**
     * User management page.
     */
    public function users()
    {
        Middleware::requireAdmin();

        $db = \App\Database\Database::getInstance()->getConnection();

        $search       = trim($_GET['search'] ?? '');
        $roleFilter   = $_GET['role']   ?? '';
        $statusFilter = $_GET['status'] ?? '';
        $wilayaFilter = $_GET['wilaya'] ?? '';
        $sortFilter   = $_GET['sort']   ?? 'date_desc';

        $page    = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 15;
        $offset  = ($page - 1) * $perPage;

        $orderBy = match($sortFilter) {
            'date_asc'  => 'u.created_at ASC',
            'name_asc'  => 'u.first_name ASC, u.last_name ASC',
            'name_desc' => 'u.first_name DESC, u.last_name DESC',
            default     => 'u.created_at DESC',
        };

        $countSql = "SELECT COUNT(u.id)
                     FROM users u
                     LEFT JOIN craftsmen_profiles cp ON u.id = cp.user_id
                     WHERE 1=1";

        $sql = "SELECT u.id, u.first_name, u.last_name, u.email, u.role, u.is_active,
                       u.created_at, u.username, u.profile_picture, u.wilaya,
                       cp.service_category, cp.is_verified
                FROM users u
                LEFT JOIN craftsmen_profiles cp ON u.id = cp.user_id
                WHERE 1=1";

        $params  = [];
        $filters = '';

        if (!empty($search)) {
            $filters .= " AND (u.first_name LIKE :s1 OR u.last_name LIKE :s2 OR u.email LIKE :s3)";
            $params['s1'] = '%' . $search . '%';
            $params['s2'] = '%' . $search . '%';
            $params['s3'] = '%' . $search . '%';
        }
        if (!empty($roleFilter)) {
            $filters .= " AND u.role = :role";
            $params['role'] = $roleFilter;
        }
        if ($statusFilter === 'active') {
            $filters .= " AND u.is_active = TRUE";
        } elseif ($statusFilter === 'inactive') {
            $filters .= " AND u.is_active = FALSE";
        }
        if (!empty($wilayaFilter)) {
            $filters .= " AND u.wilaya = :wilaya";
            $params['wilaya'] = $wilayaFilter;
        }

        $countStmt = $db->prepare($countSql . $filters);
        $countStmt->execute($params);
        $totalUsers = (int) $countStmt->fetchColumn();
        $totalPages = (int) ceil($totalUsers / $perPage);

        $stmt = $db->prepare($sql . $filters . " ORDER BY $orderBy LIMIT $perPage OFFSET $offset");
        $stmt->execute($params);
        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $wilayaStmt = $db->query("SELECT DISTINCT wilaya FROM users WHERE wilaya IS NOT NULL AND wilaya != '' ORDER BY wilaya ASC");
        $wilayas = $wilayaStmt->fetchAll(\PDO::FETCH_COLUMN);

        $this->view('layouts/app', [
            'pageTitle'    => 'User Management - Admin',
            'contentView'  => 'admin/users',
            'users'        => $users,
            'search'       => $search,
            'roleFilter'   => $roleFilter,
            'statusFilter' => $statusFilter,
            'wilayaFilter' => $wilayaFilter,
            'sortFilter'   => $sortFilter,
            'wilayas'      => $wilayas,
            'page'         => $page,
            'totalPages'   => $totalPages,
            'totalUsers'   => $totalUsers,
        ]);
    }

    /**
     * Toggle user active/inactive status.
     */
    public function toggleUserStatus()
    {
        Middleware::requireAdmin();
        Middleware::verifyCsrfToken();

        $userId = $_POST['user_id'] ?? null;
        if (!$userId || $userId == $_SESSION['user_id']) {
            header("Location: " . APP_URL . "/admin/users");
            exit;
        }

        $userModel = new User();
        $user = $userModel->findById($userId);
        if (!$user) {
            header("Location: " . APP_URL . "/admin/users");
            exit;
        }

        $newStatus = $user['is_active'] ? 0 : 1;
        $userModel->executeQuery(
            "UPDATE users SET is_active = :status WHERE id = :id",
            ['status' => $newStatus, 'id' => $userId]
        );

        header("Location: " . APP_URL . "/admin/users?success=status_updated");
        exit;
    }

    /**
     * Craftsman verification page.
     */
    public function verifications()
    {
        Middleware::requireAdmin();

        $db = \App\Database\Database::getInstance()->getConnection();

        $filter         = $_GET['filter']   ?? 'pending';
        $search         = trim($_GET['search'] ?? '');
        $wilayaFilter   = $_GET['wilaya']   ?? '';
        $categoryFilter = $_GET['category'] ?? '';
        $sortFilter     = $_GET['sort']     ?? 'date_desc';

        $page    = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 12;
        $offset  = ($page - 1) * $perPage;

        $orderBy = match($sortFilter) {
            'date_asc'  => 'u.created_at ASC',
            'name_asc'  => 'u.first_name ASC, u.last_name ASC',
            'name_desc' => 'u.first_name DESC, u.last_name DESC',
            default     => 'cp.id DESC',
        };

        $baseSql = "FROM craftsmen_profiles cp
                    JOIN users u ON cp.user_id = u.id
                    WHERE u.is_active = TRUE AND u.role = 'craftsman'";

        $params = [];

        if ($filter === 'pending') {
            $baseSql .= " AND cp.is_verified = FALSE";
        } elseif ($filter === 'verified') {
            $baseSql .= " AND cp.is_verified = TRUE";
        }
        if (!empty($search)) {
            $baseSql .= " AND (u.first_name LIKE :s1 OR u.last_name LIKE :s2)";
            $params['s1'] = '%' . $search . '%';
            $params['s2'] = '%' . $search . '%';
        }
        if (!empty($wilayaFilter)) {
            $baseSql .= " AND u.wilaya = :wilaya";
            $params['wilaya'] = $wilayaFilter;
        }
        if (!empty($categoryFilter)) {
            $baseSql .= " AND cp.service_category = :category";
            $params['category'] = $categoryFilter;
        }

        $countStmt = $db->prepare("SELECT COUNT(cp.id) " . $baseSql);
        $countStmt->execute($params);
        $totalCraftsmen = (int) $countStmt->fetchColumn();
        $totalPages     = (int) ceil($totalCraftsmen / $perPage);

        $selectSql = "SELECT cp.*, u.first_name, u.last_name, u.email,
                             u.profile_picture, u.wilaya, u.username,
                             u.created_at as user_created " . $baseSql;
        $selectSql .= " ORDER BY $orderBy LIMIT $perPage OFFSET $offset";

        $stmt = $db->prepare($selectSql);
        $stmt->execute($params);
        $craftsmen = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $wilayaStmt = $db->query(
            "SELECT DISTINCT u.wilaya FROM craftsmen_profiles cp
             JOIN users u ON cp.user_id = u.id
             WHERE u.wilaya IS NOT NULL AND u.wilaya != ''
             ORDER BY u.wilaya ASC"
        );
        $wilayas = $wilayaStmt->fetchAll(\PDO::FETCH_COLUMN);

        $categoryStmt = $db->query(
            "SELECT DISTINCT service_category FROM craftsmen_profiles
             WHERE service_category IS NOT NULL AND service_category != ''
             ORDER BY service_category ASC"
        );
        $categories = $categoryStmt->fetchAll(\PDO::FETCH_COLUMN);

        $this->view('layouts/app', [
            'pageTitle'      => 'Craftsman Verification - Admin',
            'contentView'    => 'admin/verifications',
            'craftsmen'      => $craftsmen,
            'filter'         => $filter,
            'search'         => $search,
            'wilayaFilter'   => $wilayaFilter,
            'categoryFilter' => $categoryFilter,
            'sortFilter'     => $sortFilter,
            'wilayas'        => $wilayas,
            'categories'     => $categories,
            'page'           => $page,
            'totalPages'     => $totalPages,
            'totalCraftsmen' => $totalCraftsmen,
        ]);
    }

    /**
     * Verify or unverify a craftsman.
     */
    public function toggleVerification()
    {
        Middleware::requireAdmin();
        Middleware::verifyCsrfToken();

        $userId = $_POST['user_id'] ?? null;
        if (!$userId) {
            header("Location: " . APP_URL . "/admin/verifications");
            exit;
        }

        $craftsmanModel = new CraftsmanProfile();
        $profile = $craftsmanModel->findByUserId($userId);

        if (!$profile) {
            header("Location: " . APP_URL . "/admin/verifications");
            exit;
        }

        $newStatus = $profile['is_verified'] ? 0 : 1;

        $db   = \App\Database\Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE craftsmen_profiles SET is_verified = :status WHERE user_id = :uid");
        $stmt->execute(['status' => $newStatus, 'uid' => $userId]);

        // Use username for the notification link so the URL resolves correctly
        // $profile already contains 'username' from the JOIN with users in findByUserId()
        $profileUrl = !empty($profile['username'])
            ? APP_URL . '/profile/' . $profile['username']
            : APP_URL . '/profile/' . $userId; // fallback — should never happen

        $notif = new Notification();
        if ($newStatus) {
            $notif->send(
                $userId,
                'booking_accepted',
                'Profile Verified!',
                'Congratulations! Your profile has been verified by Crafts. You now have a verified badge!',
                $profileUrl
            );
        } else {
            $notif->send(
                $userId,
                'booking_declined',
                'Verification Removed',
                'Your verified status has been removed. Please contact support for more info.',
                $profileUrl
            );
        }

        header("Location: " . APP_URL . "/admin/verifications?success=updated&filter=" . urlencode($_POST['filter'] ?? 'pending'));
        exit;
    }
}