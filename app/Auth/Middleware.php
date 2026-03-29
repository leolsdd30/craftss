<?php
namespace App\Auth;

class Middleware
{
    /**
     * Ensures the user is authenticated. 
     * Acts as the primary security gate for protected routes.
     */
    public static function requireLogin()
    {
        // 1. Check if the session exists
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . APP_URL . "/login");
            exit;
        }

        // 2. Active Session Validation (Instant-Ban Protection)
        // Ensure the user's account wasn't suspended or deleted by an admin while they were actively logged in.
        // Without this real-time check, a banned user would stay logged in until their cookie naturally expired.
        $userModel = new \App\Models\User();
        $user = $userModel->findById($_SESSION['user_id']);
        
        if (!$user || empty($user['is_active'])) {
            session_destroy(); // Instantly kill the hijacker's or banned user's session
            $msg = urlencode("Your account has been suspended or deleted.");
            header("Location: " . APP_URL . "/login?error=" . $msg);
            exit;
        }

        // Keep session synced with DB for instant verification status updates (e.g. phpMyAdmin edits)
        $_SESSION['email_verified_at'] = $user['email_verified_at'] ?? null;
    }

    /**
     * Ensures the user is logged in AND possesses a specific role.
     * Used for separating Craftsman-only and Homeowner-only actions.
     */
    public static function requireRole($role)
    {
        self::requireLogin();

        if ($_SESSION['role'] !== $role) {
            http_response_code(403);
            require_once __DIR__ . '/../../resources/views/errors/403.php';
            exit;
        }
    }

    /**
     * Ensures the user has verified their email address.
     * Prevents unverified users from performing critical actions (posting, booking).
     */
    public static function requireEmailVerification()
    {
        self::requireLogin();

        if (empty($_SESSION['email_verified_at'])) {
            header("Location: " . APP_URL . "/verify-notice");
            exit;
        }
    }

    /**
     * Ensures an admin is logged in. 
     * Protects the /admin dashboard and user-management endpoints.
     */
    public static function requireAdmin()
    {
        self::requireLogin();

        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            require_once __DIR__ . '/../../resources/views/errors/403.php';
            exit;
        }
    }

    /**
     * Verifies the CSRF token on every POST request.
     * Prevents Cross-Site Request Forgery attacks where a malicious website
     * tries to trick the user's browser into submitting forms on our behalf.
     */
    public static function verifyCsrfToken()
    {
        $token = $_POST['csrf_token'] ?? '';

        // hash_equals() is critical here: it compares strings in "constant time".
        // This prevents "Timing Attacks" where hackers guess the token character-by-character
        // based on how many milliseconds the server takes to reject it.
        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(403);
            require_once __DIR__ . '/../../resources/views/errors/403.php';
            exit;
        }
        return true;
    }
}
