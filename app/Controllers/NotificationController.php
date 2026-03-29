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
            'pageTitle' => 'Notifications - Crafts',
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
                $parsedUrl = parse_url($link);
                $appHost = parse_url(APP_URL, PHP_URL_HOST);
                $linkHost = $parsedUrl['host'] ?? null;
                $linkScheme = $parsedUrl['scheme'] ?? null;
                
                $isValidHost = ($linkHost === $appHost && !empty($appHost));
                $isValidRelative = empty($linkHost) && empty($linkScheme) && preg_match('#^/[a-zA-Z0-9]#', $link);
                
                if ($isValidHost || $isValidRelative) {
                    $hash = $_GET['hash'] ?? '';
                    if (!empty($hash) && preg_match('/^[a-zA-Z0-9_-]+$/', $hash)) {
                        $link .= '#' . $hash;
                    }
                    header('Location: ' . $link);
                    exit;
                }
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

    /**
     * Delete (dismiss) a single notification — AJAX POST.
     */
    public function delete()
    {
        Middleware::requireLogin();
 
        $input  = json_decode(file_get_contents('php://input'), true) ?? [];
        $token  = $input['csrf_token'] ?? ($_POST['csrf_token'] ?? '');
 
        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            $this->json(['success' => false, 'message' => 'Invalid CSRF token.'], 403);
            return;
        }
 
        $notifId = (int) ($input['notification_id'] ?? 0);
        if (!$notifId) {
            $this->json(['success' => false, 'message' => 'Missing notification ID.'], 400);
            return;
        }
 
        $notifModel = new Notification();
        $notifModel->delete($notifId, $_SESSION['user_id']);
 
        $this->json(['success' => true]);
    }
 
    /**
     * Delete ALL notifications for the current user — AJAX POST.
     */
    public function deleteAll()
    {
        Middleware::requireLogin();
 
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $token = $input['csrf_token'] ?? ($_POST['csrf_token'] ?? '');
 
        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            $this->json(['success' => false, 'message' => 'Invalid CSRF token.'], 403);
            return;
        }
 
        $db = \App\Database\Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM notifications WHERE user_id = :uid");
        $stmt->execute(['uid' => $_SESSION['user_id']]);
 
        $this->json(['success' => true]);
    }
 
}

    