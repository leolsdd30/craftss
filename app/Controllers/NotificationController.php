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

        $notifModel = new Notification();
        $notifications = $notifModel->getForUser($userId, 50);
        $unreadCount = $notifModel->getUnreadCount($userId);

        $this->view('layouts/app', [
            'pageTitle' => 'Notifications - CraftConnect',
            'contentView' => 'notifications/index',
            'notifications' => $notifications,
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
     * Mark single notification as read and redirect to its link (AJAX or redirect).
     */
    public function markRead()
    {
        Middleware::requireLogin();
        $userId = $_SESSION['user_id'];
        $notifId = $_POST['notification_id'] ?? $_GET['id'] ?? null;

        if ($notifId) {
            $notifModel = new Notification();
            $notifModel->markAsRead($notifId, $userId);

            // If AJAX request
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            }

            // If there's a redirect link — validate to prevent open redirect
            $link = $_GET['redirect'] ?? null;
            if ($link) {
                // Only allow internal URLs (starts with APP_URL or relative paths)
                $isRelative = (strpos($link, '/') === 0 && strpos($link, '//') !== 0);
                $isInternal = (strpos($link, APP_URL) === 0);
                
                if ($isRelative || $isInternal) {
                    // Append hash fragment if provided separately (urlencode destroys #)
                    $hash = $_GET['hash'] ?? '';
                    if (!empty($hash) && preg_match('/^[a-zA-Z0-9_-]+$/', $hash)) {
                        $link .= '#' . $hash;
                    }
                    header('Location: ' . $link);
                    exit;
                }
                // If invalid redirect, fall through to notifications page
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
        $count = $notifModel->getUnreadCount($userId);

        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
        exit;
    }
}
