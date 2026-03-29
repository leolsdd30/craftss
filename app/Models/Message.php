<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Message extends Model
{
    // ─── CONVERSATION MANAGEMENT ────────────────────────────────

    /**
     * Find or create a conversation between two users.
     * Handles: new, existing, soft-deleted (resurrect), and declined (reactivate).
     */
    public function findOrCreateConversation($userId, $otherUserId)
    {
        $sql = "SELECT * FROM conversations 
                WHERE (initiator_id = :u1 AND participant_id = :u2)
                   OR (initiator_id = :u3 AND participant_id = :u4)
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['u1' => $userId, 'u2' => $otherUserId, 'u3' => $otherUserId, 'u4' => $userId]);
        $convo = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($convo) {
            $setClauses  = [];
            $needsUpdate = false;

            // ── Resurrect soft-delete for current user's side
            if ($convo['initiator_id'] == $userId && !empty($convo['deleted_by_initiator'])) {
                $setClauses[] = 'deleted_by_initiator = 0';
                $needsUpdate  = true;
            } elseif ($convo['participant_id'] == $userId && !empty($convo['deleted_by_participant'])) {
                $setClauses[] = 'deleted_by_participant = 0';
                $needsUpdate  = true;
            }

            // ── Reactivate a declined conversation (user is reaching out again)
            if ($convo['status'] === 'declined') {
                $hasBooking   = $this->hasSharedBooking($userId, $otherUserId);
                $newStatus    = $hasBooking ? 'accepted' : 'pending';
                $setClauses[] = "status = :newstatus";
                // Make the sender the initiator so the other side gets the request
                $setClauses[] = "initiator_id = :new_init";
                $setClauses[] = "participant_id = :new_part";
                $setClauses[] = "deleted_by_initiator = 0";
                $setClauses[] = "deleted_by_participant = 0";
                $needsUpdate  = true;
            }

            if ($needsUpdate) {
                $params = ['id' => $convo['id']];
                if (in_array("status = :newstatus", $setClauses)) {
                    $params['newstatus'] = $newStatus;
                    $params['new_init']  = $userId;
                    $params['new_part']  = $otherUserId;
                }
                $this->execute(
                    "UPDATE conversations SET " . implode(', ', $setClauses) . ", updated_at = NOW() WHERE id = :id",
                    $params
                );
                // Re-fetch fresh state
                $stmt2 = $this->db->prepare("SELECT * FROM conversations WHERE id = :id");
                $stmt2->execute(['id' => $convo['id']]);
                $convo = $stmt2->fetch(PDO::FETCH_ASSOC);
            }

            return $convo;
        }

        // ── Brand new conversation
        $hasBooking = $this->hasSharedBooking($userId, $otherUserId);
        $status     = $hasBooking ? 'accepted' : 'pending';

        $sql = "INSERT INTO conversations (initiator_id, participant_id, status) VALUES (:init, :part, :status)";
        $this->execute($sql, ['init' => $userId, 'part' => $otherUserId, 'status' => $status]);

        $newId = $this->db->lastInsertId();
        return [
            'id'                     => $newId,
            'initiator_id'           => $userId,
            'participant_id'         => $otherUserId,
            'status'                 => $status,
            'deleted_by_initiator'   => 0,
            'deleted_by_participant' => 0,
        ];
    }

    /**
     * Get a conversation between two users (if exists).
     */
    public function getConversationBetween($userId, $otherUserId)
    {
        $sql = "SELECT * FROM conversations 
                WHERE (initiator_id = :u1 AND participant_id = :u2)
                   OR (initiator_id = :u3 AND participant_id = :u4)
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['u1' => $userId, 'u2' => $otherUserId, 'u3' => $otherUserId, 'u4' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Accept a conversation request.
     */
    public function acceptConversation($conversationId, $userId)
    {
        $sql = "UPDATE conversations SET status = 'accepted', updated_at = NOW() 
                WHERE id = :id AND participant_id = :uid AND status = 'pending'";
        return $this->execute($sql, ['id' => $conversationId, 'uid' => $userId]);
    }

    /**
     * Decline a conversation request.
     */
    public function declineConversation($conversationId, $userId)
    {
        $sql = "UPDATE conversations SET status = 'declined', updated_at = NOW() 
                WHERE id = :id AND participant_id = :uid AND status = 'pending'";
        return $this->execute($sql, ['id' => $conversationId, 'uid' => $userId]);
    }

    /**
     * Auto-promote pending conversations when a booking is created.
     */
    public function autoPromoteOnBooking($userId1, $userId2)
    {
        $sql = "UPDATE conversations SET status = 'accepted', updated_at = NOW() 
                WHERE status = 'pending' 
                AND ((initiator_id = :u1 AND participant_id = :u2)
                  OR (initiator_id = :u3 AND participant_id = :u4))";
        return $this->execute($sql, ['u1' => $userId1, 'u2' => $userId2, 'u3' => $userId2, 'u4' => $userId1]);
    }

    // ─── PIN / MUTE / DELETE / FOLDER ───────────────────────────

    /**
     * Toggle pin for a user on a conversation.
     */
    public function togglePin($conversationId, $userId)
    {
        $convo = $this->getConversationById($conversationId);
        if (!$convo) return false;

        if ($convo['initiator_id'] == $userId) {
            $current = (bool)$convo['is_pinned_by_initiator'];
            return $this->execute(
                "UPDATE conversations SET is_pinned_by_initiator = :val WHERE id = :id",
                ['val' => $current ? 0 : 1, 'id' => $conversationId]
            );
        } else {
            $current = (bool)$convo['is_pinned_by_participant'];
            return $this->execute(
                "UPDATE conversations SET is_pinned_by_participant = :val WHERE id = :id",
                ['val' => $current ? 0 : 1, 'id' => $conversationId]
            );
        }
    }

    /**
     * Toggle mute for a user on a conversation.
     */
    public function toggleMute($conversationId, $userId)
    {
        $convo = $this->getConversationById($conversationId);
        if (!$convo) return false;

        if ($convo['initiator_id'] == $userId) {
            $current = (bool)$convo['is_muted_by_initiator'];
            return $this->execute(
                "UPDATE conversations SET is_muted_by_initiator = :val WHERE id = :id",
                ['val' => $current ? 0 : 1, 'id' => $conversationId]
            );
        } else {
            $current = (bool)$convo['is_muted_by_participant'];
            return $this->execute(
                "UPDATE conversations SET is_muted_by_participant = :val WHERE id = :id",
                ['val' => $current ? 0 : 1, 'id' => $conversationId]
            );
        }
    }

    /**
     * Soft-delete a conversation for a specific user.
     */
    public function softDeleteForUser($conversationId, $userId)
    {
        $convo = $this->getConversationById($conversationId);
        if (!$convo) return false;

        if ($convo['initiator_id'] == $userId) {
            return $this->execute(
                "UPDATE conversations SET deleted_by_initiator = 1 WHERE id = :id",
                ['id' => $conversationId]
            );
        } else {
            return $this->execute(
                "UPDATE conversations SET deleted_by_participant = 1 WHERE id = :id",
                ['id' => $conversationId]
            );
        }
    }

    /**
     * Set folder (primary/general) for a user on a conversation.
     */
    public function setFolder($conversationId, $userId, $folder)
    {
        $folder = in_array($folder, ['primary', 'general']) ? $folder : 'primary';
        $convo  = $this->getConversationById($conversationId);
        if (!$convo) return false;

        if ($convo['initiator_id'] == $userId) {
            return $this->execute(
                "UPDATE conversations SET folder_for_initiator = :f WHERE id = :id",
                ['f' => $folder, 'id' => $conversationId]
            );
        } else {
            return $this->execute(
                "UPDATE conversations SET folder_for_participant = :f WHERE id = :id",
                ['f' => $folder, 'id' => $conversationId]
            );
        }
    }

    /**
     * Mark all messages in a conversation as read for a user.
     */
    public function markConversationRead($conversationId, $userId)
    {
        $sql = "UPDATE messages SET is_read = 1 
                WHERE conversation_id = :cid AND receiver_id = :uid AND is_read = 0";
        return $this->execute($sql, ['cid' => $conversationId, 'uid' => $userId]);
    }

    // ─── CONVERSATION LISTING ───────────────────────────────────

    /**
     * Get accepted conversations for a user, split by folder.
     * Pinned conversations float to the top.
     * Excludes soft-deleted conversations.
     */
    public function getAcceptedConversations($userId)
    {
        $sql = "SELECT 
                    c.id as conversation_id,
                    c.status as conversation_status,
                    c.initiator_id,
                    c.participant_id,
                    CASE WHEN c.initiator_id = :uid1 THEN c.participant_id ELSE c.initiator_id END AS other_user_id,
                    u.first_name, u.last_name, u.profile_picture, u.role, u.username,
                    cp.service_category, cp.is_verified,
                    lm.id as last_message_id,
                    lm.message_body as last_message,
                    lm.sender_id as last_sender_id,
                    lm.created_at as last_message_at,
                    (SELECT COUNT(*) FROM messages m2 
                     WHERE m2.conversation_id = c.id 
                     AND m2.receiver_id = :uid2 
                     AND m2.is_read = 0
                    ) as unread_count,
                    CASE WHEN c.initiator_id = :uid3 
                         THEN c.is_pinned_by_initiator 
                         ELSE c.is_pinned_by_participant 
                    END AS is_pinned,
                    CASE WHEN c.initiator_id = :uid4 
                         THEN c.is_muted_by_initiator 
                         ELSE c.is_muted_by_participant 
                    END AS is_muted,
                    CASE WHEN c.initiator_id = :uid5 
                         THEN c.folder_for_initiator 
                         ELSE c.folder_for_participant 
                    END AS folder
                FROM conversations c
                LEFT JOIN users u ON u.id = (CASE WHEN c.initiator_id = :uid6 THEN c.participant_id ELSE c.initiator_id END)
                LEFT JOIN craftsmen_profiles cp ON cp.user_id = u.id
                LEFT JOIN messages lm ON lm.id = (
                    SELECT MAX(m3.id) FROM messages m3 WHERE m3.conversation_id = c.id
                )
                WHERE (
                    (c.initiator_id = :uid7 AND c.status IN ('accepted', 'pending') AND c.deleted_by_initiator = 0)
                    OR 
                    (c.participant_id = :uid8 AND c.status = 'accepted' AND c.deleted_by_participant = 0)
                )
                AND lm.id IS NOT NULL
                ORDER BY 
                    CASE WHEN c.initiator_id = :uid9 THEN c.is_pinned_by_initiator ELSE c.is_pinned_by_participant END DESC,
                    lm.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'uid1' => $userId, 'uid2' => $userId, 'uid3' => $userId,
            'uid4' => $userId, 'uid5' => $userId, 'uid6' => $userId,
            'uid7' => $userId, 'uid8' => $userId, 'uid9' => $userId
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get pending message requests for a user (requests inbox).
     * Only shows conversations where the user is the RECIPIENT.
     */
    public function getPendingRequests($userId)
    {
        $sql = "SELECT 
                    c.id as conversation_id,
                    c.status as conversation_status,
                    c.initiator_id,
                    c.participant_id,
                    c.initiator_id AS other_user_id,
                    u.first_name, u.last_name, u.profile_picture, u.role, u.username,
                    cp.service_category, cp.is_verified,
                    lm.id as last_message_id,
                    lm.message_body as last_message,
                    lm.sender_id as last_sender_id,
                    lm.created_at as last_message_at,
                    (SELECT COUNT(*) FROM messages m2 
                     WHERE m2.conversation_id = c.id
                    ) as message_count
                FROM conversations c
                LEFT JOIN users u ON u.id = c.initiator_id
                LEFT JOIN craftsmen_profiles cp ON cp.user_id = u.id
                LEFT JOIN messages lm ON lm.id = (
                    SELECT MAX(m3.id) FROM messages m3 WHERE m3.conversation_id = c.id
                )
                WHERE c.participant_id = :uid
                AND c.status = 'pending'
                AND c.deleted_by_participant = 0
                AND lm.id IS NOT NULL
                ORDER BY lm.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ─── MESSAGES ───────────────────────────────────────────────

    /**
     * Get all messages in a conversation.
     */
    public function getMessagesByConversation($conversationId)
    {
        $sql = "SELECT m.*, u.first_name, u.last_name, u.profile_picture, cp.is_verified
                FROM messages m
                JOIN users u ON u.id = m.sender_id
                LEFT JOIN craftsmen_profiles cp ON u.id = cp.user_id
                WHERE m.conversation_id = :cid
                ORDER BY m.created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['cid' => $conversationId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Send a message within a conversation.
     */
    public function send($data)
    {
        $sql = "INSERT INTO messages (conversation_id, sender_id, receiver_id, message_body) 
                VALUES (:conversation_id, :sender_id, :receiver_id, :message_body)";
        return $this->execute($sql, [
            'conversation_id' => $data['conversation_id'],
            'sender_id'       => $data['sender_id'],
            'receiver_id'     => $data['receiver_id'],
            'message_body'    => $data['message_body']
        ]);
    }

    /**
     * Delete a single message for the sender.
     */
    public function deleteMessage($messageId, $userId)
    {
        // Only allow deleting if the user is the sender
        $sql = "DELETE FROM messages WHERE id = :id AND sender_id = :uid";
        return $this->execute($sql, ['id' => $messageId, 'uid' => $userId]);
    }

    // ─── COUNTS ─────────────────────────────────────────────────

    /**
     * Get count of accepted conversations with unread messages (for navbar badge).
     */
    public function getUnreadConversationCount($userId)
    {
        $sql = "SELECT COUNT(DISTINCT c.id) as count
                FROM conversations c
                INNER JOIN messages m ON m.conversation_id = c.id
                WHERE c.status = 'accepted'
                AND (c.initiator_id = :uid1 OR c.participant_id = :uid2)
                AND m.receiver_id = :uid3
                AND m.is_read = 0
                AND CASE WHEN c.initiator_id = :uid4 THEN c.is_muted_by_initiator ELSE c.is_muted_by_participant END = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['uid1' => $userId, 'uid2' => $userId, 'uid3' => $userId, 'uid4' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }

    /**
     * Get count of pending message requests.
     */
    public function getPendingRequestCount($userId)
    {
        $sql = "SELECT COUNT(*) as count FROM conversations
                WHERE participant_id = :uid AND status = 'pending'
                AND deleted_by_participant = 0
                AND id IN (SELECT DISTINCT conversation_id FROM messages)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['uid' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['count'] ?? 0);
    }

    // ─── HELPERS ────────────────────────────────────────────────

    /**
     * Check if two users share a booking.
     */
    public function hasSharedBooking($userId1, $userId2)
    {
        $sql = "SELECT id FROM requests_bookings 
                WHERE ((homeowner_id = :u1 AND craftsman_id = :u2)
                   OR (homeowner_id = :u3 AND craftsman_id = :u4))
                AND status NOT IN ('requested', 'cancelled')
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['u1' => $userId1, 'u2' => $userId2, 'u3' => $userId2, 'u4' => $userId1]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    /**
     * Check if user is part of a conversation.
     */
    public function isParticipant($conversationId, $userId)
    {
        $sql = "SELECT id FROM conversations 
                WHERE id = :cid AND (initiator_id = :u1 OR participant_id = :u2)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['cid' => $conversationId, 'u1' => $userId, 'u2' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    /**
     * Get conversation by ID.
     */
    public function getConversationById($id)
    {
        $sql = "SELECT * FROM conversations WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}