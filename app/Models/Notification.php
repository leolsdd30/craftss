<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Notification extends Model
{
    /**
     * Create a new notification.
     */
    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO notifications (user_id, type, title, message, link) 
             VALUES (:user_id, :type, :title, :message, :link)"
        );

        return $stmt->execute([
            'user_id' => $data['user_id'],
            'type' => $data['type'],
            'title' => $data['title'],
            'message' => $data['message'],
            'link' => $data['link'] ?? null
        ]);
    }

    /**
     * Get all notifications for a user (newest first).
     */
    public function getForUser($userId, $limit = 30)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM notifications 
             WHERE user_id = :user_id 
             ORDER BY created_at DESC 
             LIMIT :lim"
        );
        $stmt->bindValue('user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get unread notification count for a user.
     */
    public function getUnreadCount($userId)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM notifications WHERE user_id = :user_id AND is_read = FALSE"
        );
        $stmt->execute(['user_id' => $userId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead($notificationId, $userId)
    {
        $stmt = $this->db->prepare(
            "UPDATE notifications SET is_read = TRUE WHERE id = :id AND user_id = :user_id"
        );
        return $stmt->execute(['id' => $notificationId, 'user_id' => $userId]);
    }

    /**
     * Mark all notifications as read for a user.
     */
    public function markAllAsRead($userId)
    {
        $stmt = $this->db->prepare(
            "UPDATE notifications SET is_read = TRUE WHERE user_id = :user_id AND is_read = FALSE"
        );
        return $stmt->execute(['user_id' => $userId]);
    }

    /**
     * Delete a notification.
     */
    public function delete($notificationId, $userId)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM notifications WHERE id = :id AND user_id = :user_id"
        );
        return $stmt->execute(['id' => $notificationId, 'user_id' => $userId]);
    }

    /**
     * Helper: send a notification (static-style convenience).
     */
    public function send($userId, $type, $title, $message, $link = null)
    {
        return $this->create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link
        ]);
    }
}
