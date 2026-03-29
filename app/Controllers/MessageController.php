<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Message;
use App\Models\User;
use App\Auth\Middleware;

class MessageController extends Controller
{
    /**
     * Return basic user info + conversation ID for a given user (GET — AJAX).
     * Used by the inbox JS to inject a new convo row after first message send.
     */
    public function userInfo()
    {
        Middleware::requireLogin();
        $userId      = $_SESSION['user_id'];
        $otherUserId = $_GET['user_id'] ?? null;

        if (!$otherUserId || $otherUserId == $userId) {
            $this->json(['success' => false], 400);
            return;
        }

        $userModel = new User();
        $other     = $userModel->findById($otherUserId);
        if (!$other) {
            $this->json(['success' => false], 404);
            return;
        }

        // Resolve profile picture URL exactly as PHP views do
        // get_profile_picture_url already returns the correct value:
        // — UI Avatars URL (https://...) for default.png
        // — Full external URL for OAuth photos
        // — APP_URL . '/uploads/profile/...' for uploaded photos
        $picUrl = get_profile_picture_url(
            $other['profile_picture'] ?? 'default.png',
            $other['first_name'],
            $other['last_name']
        );

        // Get verified status for craftsmen
        $isVerified = false;
        if ($other['role'] === 'craftsman') {
            $db   = \App\Database\Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT is_verified FROM craftsmen_profiles WHERE user_id = :uid");
            $stmt->execute(['uid' => $otherUserId]);
            $isVerified = (bool)$stmt->fetchColumn();
        }

        $messageModel   = new Message();
        $convo          = $messageModel->getConversationBetween($userId, $otherUserId);
        $conversationId = $convo ? (int)$convo['id'] : 0;

        $this->json([
            'success'         => true,
            'conversation_id' => $conversationId,
            'user'            => [
                'id'              => (int)$other['id'],
                'first_name'      => htmlspecialchars($other['first_name'], ENT_QUOTES, 'UTF-8'),
                'last_name'       => htmlspecialchars($other['last_name'], ENT_QUOTES, 'UTF-8'),
                'username'        => $other['username'] ?? '',
                'role'            => $other['role'],
                'profile_picture' => $other['profile_picture'] ?? '',
                'pic_url'         => $picUrl,   // fully resolved URL
                'is_verified'     => $isVerified,
            ]
        ]);
    }

    /**
     * Poll the inbox for new/updated conversations (GET — AJAX).
     * Returns conversations whose last message is newer than ?since= timestamp.
     * Used to update the left panel without page reload.
     */
    public function pollInbox()
    {
        Middleware::requireLogin();
        $userId = $_SESSION['user_id'];
        $since  = (int)($_GET['since'] ?? 0); // Unix timestamp of last known update

        $db  = \App\Database\Database::getInstance()->getConnection();

        // Get conversations with activity after $since
        $sql = "SELECT
                    c.id as conversation_id,
                    c.status as conversation_status,
                    c.initiator_id,
                    c.participant_id,
                    CASE WHEN c.initiator_id = :uid1 THEN c.participant_id ELSE c.initiator_id END AS other_user_id,
                    u.first_name, u.last_name, u.profile_picture, u.role, u.username,
                    cp.service_category, cp.is_verified,
                    CASE WHEN c.initiator_id = :uid2 THEN c.folder_for_initiator ELSE c.folder_for_participant END AS folder,
                    CASE WHEN c.initiator_id = :uid3 THEN c.is_muted_by_initiator ELSE c.is_muted_by_participant END AS is_muted,
                    lm.id as last_message_id,
                    lm.message_body as last_message,
                    lm.sender_id as last_sender_id,
                    lm.created_at as last_message_at,
                    (SELECT COUNT(*) FROM messages m2
                     WHERE m2.conversation_id = c.id
                     AND m2.receiver_id = :uid4
                     AND m2.is_read = 0
                    ) as unread_count
                FROM conversations c
                LEFT JOIN users u ON u.id = (CASE WHEN c.initiator_id = :uid5 THEN c.participant_id ELSE c.initiator_id END)
                LEFT JOIN craftsmen_profiles cp ON cp.user_id = u.id
                LEFT JOIN messages lm ON lm.id = (
                    SELECT MAX(m3.id) FROM messages m3 WHERE m3.conversation_id = c.id
                )
                WHERE (
                    (c.initiator_id = :uid6 AND c.status IN ('accepted','pending') AND c.deleted_by_initiator = 0)
                    OR
                    (c.participant_id = :uid7 AND c.status = 'accepted' AND c.deleted_by_participant = 0)
                )
                AND lm.id IS NOT NULL
                AND UNIX_TIMESTAMP(c.updated_at) > :since
                ORDER BY lm.created_at DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            'uid1' => $userId, 'uid2' => $userId, 'uid3' => $userId,
            'uid4' => $userId, 'uid5' => $userId, 'uid6' => $userId,
            'uid7' => $userId, 'since' => $since
        ]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Resolve profile picture URLs and Escape output for XSS prevention
        foreach ($rows as &$row) {
            $row['pic_url'] = get_profile_picture_url(
                $row['profile_picture'] ?? 'default.png',
                $row['first_name'],
                $row['last_name']
            );
            $row['first_name'] = htmlspecialchars((string)$row['first_name'], ENT_QUOTES, 'UTF-8');
            $row['last_name'] = htmlspecialchars((string)$row['last_name'], ENT_QUOTES, 'UTF-8');
            $row['username'] = htmlspecialchars((string)($row['username'] ?? ''), ENT_QUOTES, 'UTF-8');
            $row['role'] = htmlspecialchars((string)($row['role'] ?? ''), ENT_QUOTES, 'UTF-8');
            $row['service_category'] = htmlspecialchars((string)($row['service_category'] ?? ''), ENT_QUOTES, 'UTF-8');
            if (isset($row['last_message'])) {
                $row['last_message'] = htmlspecialchars((string)$row['last_message'], ENT_QUOTES, 'UTF-8');
            }
        }
        unset($row);

        $this->json([
            'success'      => true,
            'conversations' => $rows,
            'server_time'  => time(),
        ]);
    }

    /**
     * Standalone requests page — shows all pending message requests.
     */
    public function requests()
    {
        Middleware::requireLogin();

        $userId       = $_SESSION['user_id'];
        $messageModel = new Message();

        $requests     = $messageModel->getPendingRequests($userId);
        $requestCount = count($requests);

        $this->view('layouts/app', [
            'pageTitle'    => 'Message Requests - Crafts',
            'contentView'  => 'messages/requests',
            'requests'     => $requests,
            'requestCount' => $requestCount,
        ]);
    }

    /**
     * Show the unified messages page (split-pane inbox + optional open convo).
     */
    public function inbox($username = null)
    {
        Middleware::requireLogin();

        $userId       = $_SESSION['user_id'];
        $messageModel = new Message();

        $withUsername = $username ?? $_GET['u'] ?? null;
        $withUserId   = null;

        if ($withUsername) {
            $userModel = new User();
            $withUser  = $userModel->findByUsername($withUsername);
            if ($withUser) {
                $withUserId = $withUser['id'];
                // ── If a ?u= param exists and matches a user, mark that conversation as read FIRST
                if ($withUserId != $userId) {
                    $earlyConvo = $messageModel->getConversationBetween($userId, $withUserId);
                    if ($earlyConvo && $earlyConvo['status'] === 'accepted') {
                        $messageModel->markConversationRead($earlyConvo['id'], $userId);
                    }
                }
            }
        }

        $allConversations = $messageModel->getAcceptedConversations($userId);
        $requests         = $messageModel->getPendingRequests($userId);
        $unreadCount      = $messageModel->getUnreadConversationCount($userId);
        $requestCount     = $messageModel->getPendingRequestCount($userId);

        // Split conversations into Primary and General folders
        $primaryConvos = array_filter($allConversations, fn($c) => ($c['folder'] ?? 'primary') === 'primary');
        $generalConvos = array_filter($allConversations, fn($c) => ($c['folder'] ?? 'primary') === 'general');

        // Pre-load the open conversation if ?u= is set
        $openConvo    = null;
        $openMessages = [];
        $openUser     = null;
        $isRequest    = false;
        $isRecipient  = false;
        $canSend      = true;

        if ($withUserId && $withUserId != $userId) {
            $userModel = new User();
            $openUser  = $userModel->findById($withUserId);

            if ($openUser) {
                if ($openUser['role'] === 'craftsman') {
                    $db   = \App\Database\Database::getInstance()->getConnection();
                    $stmt = $db->prepare("SELECT is_verified FROM craftsmen_profiles WHERE user_id = :uid");
                    $stmt->execute(['uid' => $withUserId]);
                    $openUser['is_verified'] = (bool)$stmt->fetchColumn();
                } else {
                    $openUser['is_verified'] = false;
                }

                $openConvo = $messageModel->getConversationBetween($userId, $withUserId);

                if ($openConvo) {
                    $isRequest   = ($openConvo['status'] === 'pending');
                    $isRecipient = ($openConvo['participant_id'] == $userId);

                    if ($openConvo['status'] === 'declined') {
                        // Treat declined as a fresh start — show the input, allow sending
                        // findOrCreateConversation will handle reactivation on first send
                        $openConvo['status'] = 'accepted'; // treat as open for UI purposes
                        $isRequest   = false;
                        $isRecipient = false;
                        $openMessages = []; // fresh — don't show old messages
                    } else {
                        // markConversationRead already called above for accepted convos
                        $openMessages = $messageModel->getMessagesByConversation($openConvo['id']);
                        if ($isRequest && $isRecipient) {
                            $canSend = false;
                        }
                    }
                }
            }
        }

        $this->view('layouts/app', [
            'pageTitle'       => 'Messages - Crafts',
            'contentView'     => 'messages/inbox',
            'primaryConvos'   => array_values($primaryConvos),
            'generalConvos'   => array_values($generalConvos),
            'requests'        => $requests,
            'unreadCount'     => $unreadCount,
            'requestCount'    => $requestCount,
            // Open conversation data
            'openUser'        => $openUser,
            'openConvo'       => $openConvo,
            'openMessages'    => $openMessages,
            'isRequest'       => $isRequest,
            'isRecipient'     => $isRecipient,
            'canSend'         => $canSend,
            'withUserId'      => $withUserId,
            'withUsername'    => $withUsername,
        ]);
    }

    /**
     * Show a standalone conversation thread (still supported for direct links).
     */
    public function conversation()
    {
        // Redirect to the unified inbox via vanity url
        $with = $_GET['with'] ?? null;
        if ($with) {
            $userModel = new User();
            $u = $userModel->findById($with);
            if ($u && !empty($u['username'])) {
                header("Location: " . APP_URL . "/messages/" . urlencode($u['username']));
                exit;
            }
        }
        header("Location: " . APP_URL . "/messages");
        exit;
    }

    /**
     * Send a message (POST — AJAX).
     */
    public function send()
    {
        Middleware::requireLogin();
        $input = $this->validateAjaxCsrf();
        if ($input === false) return;
        $userId   = $_SESSION['user_id'];

        $receiverId  = $input['receiver_id'] ?? ($_POST['receiver_id'] ?? null);
        $messageBody = trim((string)($input['message'] ?? ($_POST['message'] ?? '')));

        if (!$receiverId || empty($messageBody) || mb_strlen($messageBody, 'UTF-8') > 5000) {
            $this->json(['success' => false, 'message' => 'Missing required fields.'], 400);
            return;
        }
        if ($receiverId == $userId) {
            $this->json(['success' => false, 'message' => 'Cannot send messages to yourself.'], 400);
            return;
        }

        $messageModel = new Message();
        $convo        = $messageModel->findOrCreateConversation($userId, $receiverId);

        if ($convo['status'] === 'pending' && $convo['participant_id'] == $userId) {
            $this->json(['success' => false, 'message' => 'You must accept this message request before replying.'], 403);
            return;
        }

        $msgId = $messageModel->send([
            'conversation_id' => $convo['id'],
            'sender_id'       => $userId,
            'receiver_id'     => $receiverId,
            'message_body'    => $messageBody
        ]);

        // Update conversation timestamp
        $db = \App\Database\Database::getInstance()->getConnection();
        $db->prepare("UPDATE conversations SET updated_at = NOW() WHERE id = :id")
           ->execute(['id' => $convo['id']]);

        $this->json(['success' => true, 'conversation_id' => $convo['id']]);
    }

    /**
     * Poll for new messages (GET — AJAX).
     */
    public function poll()
    {
        Middleware::requireLogin();
        $userId      = $_SESSION['user_id'];
        $otherUserId = $_GET['with'] ?? null;
        $lastId      = (int)($_GET['after'] ?? 0);

        if (!$otherUserId) {
            $this->json(['success' => false], 400);
            return;
        }

        $messageModel = new Message();
        $convo        = $messageModel->getConversationBetween($userId, $otherUserId);

        if (!$convo) {
            $this->json(['success' => true, 'messages' => [], 'unread_count' => 0]);
            return;
        }

        if ($convo['status'] === 'accepted') {
            $messageModel->markConversationRead($convo['id'], $userId);
        }

        $db   = \App\Database\Database::getInstance()->getConnection();
        $sql  = "SELECT m.*, u.first_name, u.last_name, u.profile_picture, cp.is_verified
                 FROM messages m
                 JOIN users u ON u.id = m.sender_id
                 LEFT JOIN craftsmen_profiles cp ON u.id = cp.user_id
                 WHERE m.conversation_id = :cid AND m.id > :last_id
                 ORDER BY m.created_at ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute(['cid' => $convo['id'], 'last_id' => $lastId]);
        $newMessages = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Resolve profile picture URL and Escape everything against XSS payloads
        foreach ($newMessages as &$msg) {
            $msg['pic_url'] = get_profile_picture_url(
                $msg['profile_picture'] ?? 'default.png',
                $msg['first_name'],
                $msg['last_name']
            );
            $msg['first_name'] = htmlspecialchars($msg['first_name'], ENT_QUOTES, 'UTF-8');
            $msg['last_name'] = htmlspecialchars($msg['last_name'], ENT_QUOTES, 'UTF-8');
            $msg['message_body'] = htmlspecialchars($msg['message_body'], ENT_QUOTES, 'UTF-8');
        }
        unset($msg);

        $this->json([
            'success'             => true,
            'messages'            => $newMessages,
            'conversation_status' => $convo['status'],
            'unread_count'        => $messageModel->getUnreadConversationCount($userId)
        ]);
    }

    /**
     * Get unread conversation count (GET — AJAX, for navbar badge).
     */
    public function unreadCount()
    {
        Middleware::requireLogin();
        $messageModel = new Message();
        $count        = $messageModel->getUnreadConversationCount($_SESSION['user_id']);
        $this->json(['count' => $count]);
    }

    /**
     * Accept a message request (POST — AJAX).
     */
    public function acceptRequest()
    {
        Middleware::requireLogin();
        $input = $this->validateAjaxCsrf();
        if ($input === false) return;
        $userId   = $_SESSION['user_id'];
        $otherUserId = $input['user_id'] ?? ($_POST['user_id'] ?? null);

        if (!$otherUserId) {
            $this->json(['success' => false, 'message' => 'Missing user ID.'], 400);
            return;
        }

        $messageModel = new Message();
        $convo        = $messageModel->getConversationBetween($userId, $otherUserId);

        if (!$convo || $convo['participant_id'] != $userId) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 400);
            return;
        }

        // Accept the conversation
        $messageModel->acceptConversation($convo['id'], $userId);

        // Immediately mark all messages as read so the badge clears right away
        $messageModel->markConversationRead($convo['id'], $userId);

        $this->json(['success' => true, 'message' => 'Message request accepted.']);
    }

    /**
     * Decline a message request (POST — AJAX).
     */
    public function declineRequest()
    {
        Middleware::requireLogin();
        $input = $this->validateAjaxCsrf();
        if ($input === false) return;
        $userId   = $_SESSION['user_id'];
        $otherUserId = $input['user_id'] ?? ($_POST['user_id'] ?? null);

        if (!$otherUserId) {
            $this->json(['success' => false, 'message' => 'Missing user ID.'], 400);
            return;
        }

        $messageModel = new Message();
        $convo        = $messageModel->getConversationBetween($userId, $otherUserId);

        if (!$convo || $convo['participant_id'] != $userId) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 400);
            return;
        }

        $messageModel->declineConversation($convo['id'], $userId);
        $this->json(['success' => true, 'message' => 'Message request declined.']);
    }

    /**
     * Toggle pin on a conversation (POST — AJAX).
     */
    public function pin()
    {
        Middleware::requireLogin();
        $input = $this->validateAjaxCsrf();
        if ($input === false) return;

        $userId   = $_SESSION['user_id'];
        $convoId  = $input['conversation_id'] ?? ($_POST['conversation_id'] ?? null);

        $messageModel = $this->getValidConversation($convoId, $userId);
        if ($messageModel === false) return;

        $messageModel->togglePin($convoId, $userId);

        $convo   = $messageModel->getConversationById($convoId);
        $isPinned = ($convo['initiator_id'] == $userId)
            ? (bool)$convo['is_pinned_by_initiator']
            : (bool)$convo['is_pinned_by_participant'];

        $this->json(['success' => true, 'is_pinned' => $isPinned]);
    }

    /**
     * Toggle mute on a conversation (POST — AJAX).
     */
    public function mute()
    {
        Middleware::requireLogin();
        $input = $this->validateAjaxCsrf();
        if ($input === false) return;

        $userId   = $_SESSION['user_id'];
        $convoId  = $input['conversation_id'] ?? ($_POST['conversation_id'] ?? null);

        $messageModel = $this->getValidConversation($convoId, $userId);
        if ($messageModel === false) return;

        $messageModel->toggleMute($convoId, $userId);

        $convo   = $messageModel->getConversationById($convoId);
        $isMuted = ($convo['initiator_id'] == $userId)
            ? (bool)$convo['is_muted_by_initiator']
            : (bool)$convo['is_muted_by_participant'];

        $this->json(['success' => true, 'is_muted' => $isMuted]);
    }

    /**
     * Soft-delete a conversation for the current user (POST — AJAX).
     */
    public function delete()
    {
        Middleware::requireLogin();
        $input = $this->validateAjaxCsrf();
        if ($input === false) return;

        $userId   = $_SESSION['user_id'];
        $convoId  = $input['conversation_id'] ?? ($_POST['conversation_id'] ?? null);

        $messageModel = $this->getValidConversation($convoId, $userId);
        if ($messageModel === false) return;

        $messageModel->softDeleteForUser($convoId, $userId);
        $this->json(['success' => true]);
    }

    /**
     * Mark a conversation as read (POST — AJAX).
     */
    public function markRead()
    {
        Middleware::requireLogin();
        $input = $this->validateAjaxCsrf();
        if ($input === false) return;

        $userId   = $_SESSION['user_id'];
        $convoId  = $input['conversation_id'] ?? ($_POST['conversation_id'] ?? null);

        $messageModel = $this->getValidConversation($convoId, $userId);
        if ($messageModel === false) return;

        $messageModel->markConversationRead($convoId, $userId);
        $this->json(['success' => true]);
    }

    /**
     * Set folder (primary/general) for a conversation (POST — AJAX).
     */
    public function setFolder()
    {
        Middleware::requireLogin();
        $input = $this->validateAjaxCsrf();
        if ($input === false) return;

        $userId   = $_SESSION['user_id'];
        $convoId  = $input['conversation_id'] ?? ($_POST['conversation_id'] ?? null);
        $folder   = $input['folder'] ?? ($_POST['folder'] ?? 'primary');

        $messageModel = $this->getValidConversation($convoId, $userId);
        if ($messageModel === false) return;

        $messageModel->setFolder($convoId, $userId, $folder);
        $this->json(['success' => true, 'folder' => $folder]);
    }

    /**
     * Delete a single message (POST — AJAX).
     */
    public function deleteSingleMessage()
    {
        Middleware::requireLogin();
        $input = $this->validateAjaxCsrf();
        if ($input === false) return;

        $userId   = $_SESSION['user_id'];
        $msgId    = $input['message_id'] ?? ($_POST['message_id'] ?? null);

        if (!$msgId) {
            $this->json(['success' => false, 'message' => 'Missing message ID.'], 400);
            return;
        }

        $messageModel = new Message();
        $deleted = $messageModel->deleteMessage($msgId, $userId);

        if ($deleted) {
            $this->json(['success' => true, 'message' => 'Message deleted.']);
        } else {
            $this->json(['success' => false, 'message' => 'Failed to delete message.']);
        }
    }

    /**
     * Centralized CSRF and AJAX JSON decoding helper (DRY).
     */
    private function validateAjaxCsrf()
    {
        $inputRaw = file_get_contents('php://input');
        $input    = $inputRaw ? json_decode($inputRaw, true) : [];
        $token    = $input['csrf_token'] ?? ($_POST['csrf_token'] ?? '');

        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            $this->json(['success' => false, 'message' => 'Invalid CSRF token.'], 403);
            return false;
        }

        return sizeof($input) > 0 ? $input : $_POST;
    }

    /**
     * Centralized conversation authorization check (DRY).
     */
    private function getValidConversation($convoId, $userId)
    {
        if (!$convoId) {
            $this->json(['success' => false, 'message' => 'Missing conversation ID.'], 400);
            return false;
        }

        $messageModel = new Message();
        if (!$messageModel->isParticipant($convoId, $userId)) {
            $this->json(['success' => false, 'message' => 'Unauthorized.'], 403);
            return false;
        }

        return $messageModel;
    }
}