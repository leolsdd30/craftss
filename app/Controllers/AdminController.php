<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Auth\Middleware;
use App\Models\User;
use App\Models\CraftsmanProfile;
use App\Models\Notification;

class AdminController extends Controller
{
    /**
     * Admin Dashboard with platform stats.
     */
    public function dashboard()
    {
        Middleware::requireAdmin();

        $db = \App\Database\Database::getInstance()->getConnection();

        // Platform stats
        $stats = [];
        $stats['total_users'] = (int) $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $stats['homeowners'] = (int) $db->query("SELECT COUNT(*) FROM users WHERE role = 'homeowner'")->fetchColumn();
        $stats['craftsmen'] = (int) $db->query("SELECT COUNT(*) FROM users WHERE role = 'craftsman'")->fetchColumn();
        $stats['admins'] = (int) $db->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
        $stats['total_bookings'] = (int) $db->query("SELECT COUNT(*) FROM requests_bookings")->fetchColumn();
        $stats['active_bookings'] = (int) $db->query("SELECT COUNT(*) FROM requests_bookings WHERE status = 'hired'")->fetchColumn();
        $stats['completed_bookings'] = (int) $db->query("SELECT COUNT(*) FROM requests_bookings WHERE status = 'completed'")->fetchColumn();
        $stats['total_jobs'] = (int) $db->query("SELECT COUNT(*) FROM job_postings")->fetchColumn();
        $stats['open_jobs'] = (int) $db->query("SELECT COUNT(*) FROM job_postings WHERE status = 'open'")->fetchColumn();
        $stats['total_reviews'] = (int) $db->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
        $stats['avg_rating'] = round((float) $db->query("SELECT IFNULL(AVG(star_rating), 0) FROM reviews")->fetchColumn(), 1);
        $stats['verified_craftsmen'] = (int) $db->query("SELECT COUNT(*) FROM craftsmen_profiles cp JOIN users u ON cp.user_id = u.id WHERE cp.is_verified = TRUE AND u.role = 'craftsman'")->fetchColumn();
        $stats['pending_verification'] = (int) $db->query("SELECT COUNT(*) FROM craftsmen_profiles cp JOIN users u ON cp.user_id = u.id WHERE cp.is_verified = FALSE AND u.role = 'craftsman'")->fetchColumn();
        $stats['total_messages'] = (int) $db->query("SELECT COUNT(*) FROM messages")->fetchColumn();

        // Recent users
        $stmt = $db->query("SELECT id, first_name, last_name, email, role, is_active, created_at, username FROM users ORDER BY created_at DESC LIMIT 10");
        $recentUsers = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->view('layouts/app', [
            'pageTitle' => 'Admin Dashboard - Crafts',
            'contentView' => 'admin/dashboard',
            'stats' => $stats,
            'recentUsers' => $recentUsers
        ]);
    }

    /**
     * User management page.
     */
    public function users()
    {
        Middleware::requireAdmin();

        $db = \App\Database\Database::getInstance()->getConnection();

        $search = trim($_GET['search'] ?? '');
        $roleFilter = $_GET['role'] ?? '';
        $statusFilter = $_GET['status'] ?? '';

        $sql = "SELECT u.id, u.first_name, u.last_name, u.email, u.role, u.is_active, u.created_at, u.username,
                       cp.service_category, cp.is_verified
                FROM users u
                LEFT JOIN craftsmen_profiles cp ON u.id = cp.user_id
                WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (u.first_name LIKE :s1 OR u.last_name LIKE :s2 OR u.email LIKE :s3)";
            $params['s1'] = '%' . $search . '%';
            $params['s2'] = '%' . $search . '%';
            $params['s3'] = '%' . $search . '%';
        }
        if (!empty($roleFilter)) {
            $sql .= " AND u.role = :role";
            $params['role'] = $roleFilter;
        }
        if ($statusFilter === 'active') {
            $sql .= " AND u.is_active = TRUE";
        } elseif ($statusFilter === 'inactive') {
            $sql .= " AND u.is_active = FALSE";
        }

        $sql .= " ORDER BY u.created_at DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->view('layouts/app', [
            'pageTitle' => 'User Management - Admin',
            'contentView' => 'admin/users',
            'users' => $users,
            'search' => $search,
            'roleFilter' => $roleFilter,
            'statusFilter' => $statusFilter
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
        $userModel->executeQuery("UPDATE users SET is_active = :status WHERE id = :id", [
            'status' => $newStatus,
            'id' => $userId
        ]);

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

        $filter = $_GET['filter'] ?? 'pending';

        $sql = "SELECT cp.*, u.first_name, u.last_name, u.email, u.profile_picture, u.wilaya, u.username, u.created_at as user_created
                FROM craftsmen_profiles cp
                JOIN users u ON cp.user_id = u.id
                WHERE u.is_active = TRUE AND u.role = 'craftsman'";

        if ($filter === 'pending') {
            $sql .= " AND cp.is_verified = FALSE";
        } elseif ($filter === 'verified') {
            $sql .= " AND cp.is_verified = TRUE";
        }

        $sql .= " ORDER BY cp.id DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $craftsmen = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->view('layouts/app', [
            'pageTitle' => 'Craftsman Verification - Admin',
            'contentView' => 'admin/verifications',
            'craftsmen' => $craftsmen,
            'filter' => $filter
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

        $db = \App\Database\Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE craftsmen_profiles SET is_verified = :status WHERE user_id = :uid");
        $stmt->execute(['status' => $newStatus, 'uid' => $userId]);

        // Notify the craftsman
        $notif = new Notification();
        if ($newStatus) {
            $notif->send($userId, 'booking_accepted', 'Profile Verified!', 
                'Congratulations! Your profile has been verified by Crafts. You now have a verified badge!', 
                APP_URL . '/profile/' . $userId);
        } else {
            $notif->send($userId, 'booking_declined', 'Verification Removed', 
                'Your verified status has been removed. Please contact support for more info.', 
                APP_URL . '/profile/' . $userId);
        }

        header("Location: " . APP_URL . "/admin/verifications?success=updated");
        exit;
    }
}
