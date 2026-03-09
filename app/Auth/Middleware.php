<?php
namespace App\Auth;

class Middleware
{
    /**
     * Ensure the user is logged in. If not, redirect to the login page.
     */
    public static function requireLogin(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . APP_URL . "/login");
            exit;
        }
    }

    /**
     * Ensure the user is logged in AND has a specific role.
     *
     * @param string $role The required role ('homeowner' or 'craftsman')
     */
    public static function requireRole(string $role): void
    {
        self::requireLogin();

        if ($_SESSION['role'] !== $role) {
            http_response_code(403);
            $viewPath = BASE_PATH . '/resources/views/errors/403.php';
            if (file_exists($viewPath)) {
                require $viewPath;
            } else {
                echo "403 - Access Denied: You do not have permission to view this page.";
            }
            exit;
        }
    }

    /**
     * Ensure an admin is logged in.
     */
    public static function requireAdmin(): void
    {
        self::requireLogin();

        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            $viewPath = BASE_PATH . '/resources/views/errors/403.php';
            if (file_exists($viewPath)) {
                require $viewPath;
            } else {
                echo "403 - Access Denied: Administrator privileges required.";
            }
            exit;
        }
    }

    /**
     * Verify a CSRF token from the POST request.
     * Works for both standard form posts and JSON/AJAX requests.
     */
    public static function verifyCsrfToken(): void
    {
        // Support AJAX requests sending token via header (X-CSRF-Token)
        $headerToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        $postToken   = $_POST['csrf_token'] ?? '';
        $token       = !empty($headerToken) ? $headerToken : $postToken;

        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(403);
            // Return JSON for AJAX callers, HTML for normal requests
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'CSRF token validation failed.']);
            } else {
                echo "403 - Access Denied: CSRF Token Validation Failed. Please go back and try again.";
            }
            exit;
        }
    }

    /**
     * Simple login rate limiter using session storage.
     * Allows up to $maxAttempts per $decaySeconds window.
     *
     * @param string $key          Unique key per action (e.g. 'login_' . md5($email))
     * @param int    $maxAttempts  Max allowed attempts
     * @param int    $decaySeconds Lockout window in seconds
     * @return bool True if allowed, false if rate-limited
     */
    public static function checkRateLimit(string $key, int $maxAttempts = 5, int $decaySeconds = 900): bool
    {
        $now = time();

        if (!isset($_SESSION['_rate'][$key])) {
            $_SESSION['_rate'][$key] = ['count' => 0, 'window_start' => $now];
        }

        $data = &$_SESSION['_rate'][$key];

        // Reset window if expired
        if ($now - $data['window_start'] > $decaySeconds) {
            $data = ['count' => 0, 'window_start' => $now];
        }

        $data['count']++;

        if ($data['count'] > $maxAttempts) {
            $remaining = $decaySeconds - ($now - $data['window_start']);
            return false; // Rate limited
        }

        return true;
    }

    /**
     * Reset rate limit counter after a successful action (e.g. successful login).
     */
    public static function clearRateLimit(string $key): void
    {
        unset($_SESSION['_rate'][$key]);
    }
}
