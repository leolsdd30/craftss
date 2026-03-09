<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Auth\Middleware;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Show all notifications page.
     */
    public function index()
    {
        Middleware::requireLogin();
        $userId = $_SESSION['user_id'];

        $notifModel    = new Notification();
        $notifications = $notifModel->getForUser($userId, 50);
        $unreadCount   = $notifModel->getUnreadCount($userId);

        $this->view('layouts/app', [
            'pageTitle'   => 'Notifications - CraftConnect',
            'contentView' => 'notifications/index',
            'notifications'=> $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * Mark all notifications as read (AJAX).
     */
    public function markAllRead()
    {
        Middleware::requireLogin();
        $userId = $_SESSION['user_id'];

        $notifModel = new Notification();
        $notifModel->markAllAsRead($userId);

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }

    /**
     * Mark single notification as read and redirect to its link.
     */
    public function markRead()
    {
        Middleware::requireLogin();
        $userId  = $_SESSION['user_id'];
        $notifId = $_POST['notification_id'] ?? $_GET['id'] ?? null;

        if ($notifId) {
            $notifModel = new Notification();
            $notifModel->markAsRead((int) $notifId, $userId);

            // If AJAX request
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            }

            // [SECURITY] Validate redirect to prevent open redirect attacks.
            // Only allow relative paths or URLs starting with APP_URL.
            $link = $_GET['redirect'] ?? null;
            if ($link && $this->isSafeRedirect($link)) {
                header('Location: ' . $link);
                exit;
            }
        }

        header('Location: ' . APP_URL . '/notifications');
        exit;
    }

    /**
     * Get unread count (AJAX polling).
     */
    public function unreadCount()
    {
        Middleware::requireLogin();
        $userId = $_SESSION['user_id'];

        $notifModel = new Notification();
        $count      = $notifModel->getUnreadCount($userId);

        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
        exit;
    }

    // ─── Private Helpers ──────────────────────────────────────────────────────

    /**
     * Validate that a redirect target is safe (relative or same origin).
     * Blocks open redirects to external sites.
     *
     * @param string $url
     * @return bool
     */
    private function isSafeRedirect(string $url): bool
    {
        // Allow relative paths (starting with /)
        if (strpos($url, '/') === 0 && strpos($url, '//') !== 0) {
            return true;
        }

        // Allow absolute URLs only if they start with our own APP_URL
        if (!empty(APP_URL) && strpos($url, APP_URL) === 0) {
            return true;
        }

        return false;
    }
}
