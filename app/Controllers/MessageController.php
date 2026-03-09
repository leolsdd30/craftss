<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Message;
use App\Models\User;
use App\Auth\Middleware;

class MessageController extends Controller
{
    /**
     * Show the inbox with two tabs: Messages & Requests.
     */
    public function inbox()
    {
        Middleware::requireLogin();

        $userId = $_SESSION['user_id'];
        $messageModel = new Message();

        $conversations = $messageModel->getAcceptedConversations($userId);
        $requests = $messageModel->getPendingRequests($userId);
        $unreadCount = $messageModel->getUnreadConversationCount($userId);
        $requestCount = $messageModel->getPendingRequestCount($userId);

        $this->view('layouts/app', [
            'pageTitle' => 'Messages - CraftConnect',
            'contentView' => 'messages/inbox',
            'conversations' => $conversations,
            'requests' => $requests,
            'unreadCount' => $unreadCount,
            'requestCount' => $requestCount
        ]);
    }

    /**
     * Show a conversation thread with a specific user.
     */
    public function conversation()
    {
        Middleware::requireLogin();

        $userId = $_SESSION['user_id'];
        $otherUserId = $_GET['with'] ?? null;

        if (!$otherUserId || $otherUserId == $userId) {
            $this->redirect(APP_URL . '/messages');
            return;
        }

        $userModel = new User();
        $otherUser = $userModel->findById($otherUserId);

        if (!$otherUser) {
            $this->redirect(APP_URL . '/messages');
            return;
        }

        $messageModel = new Message();

        // Get or check conversation
        $convo = $messageModel->getConversationBetween($userId, $otherUserId);

        // Determine if this is a request view
        $isRequest = false;
        $isRecipient = false;
        $canSend = true;

        if ($convo) {
            $isRequest = ($convo['status'] === 'pending');
            $isRecipient = ($convo['participant_id'] == $userId);
            
            // If declined, don't show
            if ($convo['status'] === 'declined') {
                $this->redirect(APP_URL . '/messages');
                return;
            }

            // Mark messages as read if accepted
            if ($convo['status'] === 'accepted') {
                $messageModel->markConversationRead($convo['id'], $userId);
            }

            $messages = $messageModel->getMessagesByConversation($convo['id']);
            
            // Recipient of a request can read but must accept first to reply
            if ($isRequest && $isRecipient) {
                $canSend = false;
            }
        } else {
            // No conversation yet — user is about to start one
            $messages = [];
            $canSend = true;
        }

        $unreadCount = $messageModel->getUnreadConversationCount($userId);
        $requestCount = $messageModel->getPendingRequestCount($userId);

        $this->view('layouts/app', [
            'pageTitle' => 'Chat with ' . $otherUser['first_name'] . ' - CraftConnect',
            'contentView' => 'messages/conversation',
            'otherUser' => $otherUser,
            'messages' => $messages,
            'conversation' => $convo,
            'isRequest' => $isRequest,
            'isRecipient' => $isRecipient,
            'canSend' => $canSend,
            'unreadCount' => $unreadCount,
            'requestCount' => $requestCount
        ]);
    }

    /**
     * Send a message (POST — AJAX).
     */
    public function send()
    {
        Middleware::requireLogin();

        $userId = $_SESSION['user_id'];

        $inputRaw = file_get_contents('php://input');
        $input = $inputRaw ? json_decode($inputRaw, true) : null;

        $receiverId = null;
        $messageBody = null;

        if (is_array($input)) {
            $receiverId = $input['receiver_id'] ?? null;
            $messageBody = trim($input['message'] ?? '');
        } else {
            $receiverId = $_POST['receiver_id'] ?? null;
            $messageBody = trim($_POST['message'] ?? '');
        }

        if (!$receiverId || empty($messageBody)) {
            $this->json(['success' => false, 'message' => 'Missing required fields.'], 400);
            return;
        }

        if ($receiverId == $userId) {
            $this->json(['success' => false, 'message' => 'Cannot send messages to yourself.'], 400);
            return;
        }

        $messageModel = new Message();

        // Find or create conversation
        $convo = $messageModel->findOrCreateConversation($userId, $receiverId);

        // Check: if pending and user is the recipient, they can't send until they accept
        if ($convo['status'] === 'pending' && $convo['participant_id'] == $userId) {
            $this->json(['success' => false, 'message' => 'You must accept this message request before replying.'], 400);
            return;
        }

        // Check: if declined
        if ($convo['status'] === 'declined') {
            $this->json(['success' => false, 'message' => 'This conversation has been declined.'], 400);
            return;
        }

        $messageModel->send([
            'conversation_id' => $convo['id'],
            'sender_id' => $userId,
            'receiver_id' => $receiverId,
            'message_body' => $messageBody
        ]);

        $this->json([
            'success' => true,
            'conversation_status' => $convo['status'],
            'message_data' => [
                'sender_id' => $userId,
                'first_name' => $_SESSION['first_name'] ?? '',
                'last_name' => $_SESSION['last_name'] ?? '',
                'message_body' => htmlspecialchars($messageBody),
                'created_at' => date('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * Accept a message request (POST — AJAX).
     */
    public function acceptRequest()
    {
        Middleware::requireLogin();

        $userId = $_SESSION['user_id'];

        $inputRaw = file_get_contents('php://input');
        $input = $inputRaw ? json_decode($inputRaw, true) : null;

        $otherUserId = is_array($input) ? ($input['user_id'] ?? null) : ($_POST['user_id'] ?? null);

        if (!$otherUserId) {
            $this->json(['success' => false, 'message' => 'Missing user ID.'], 400);
            return;
        }

        $messageModel = new Message();
        $convo = $messageModel->getConversationBetween($userId, $otherUserId);

        if (!$convo || $convo['participant_id'] != $userId) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 400);
            return;
        }

        $messageModel->acceptConversation($convo['id'], $userId);

        $this->json(['success' => true, 'message' => 'Message request accepted.']);
    }

    /**
     * Decline a message request (POST — AJAX).
     */
    public function declineRequest()
    {
        Middleware::requireLogin();

        $userId = $_SESSION['user_id'];

        $inputRaw = file_get_contents('php://input');
        $input = $inputRaw ? json_decode($inputRaw, true) : null;

        $otherUserId = is_array($input) ? ($input['user_id'] ?? null) : ($_POST['user_id'] ?? null);

        if (!$otherUserId) {
            $this->json(['success' => false, 'message' => 'Missing user ID.'], 400);
            return;
        }

        $messageModel = new Message();
        $convo = $messageModel->getConversationBetween($userId, $otherUserId);

        if (!$convo || $convo['participant_id'] != $userId) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 400);
            return;
        }

        $messageModel->declineConversation($convo['id'], $userId);

        $this->json(['success' => true, 'message' => 'Message request declined.']);
    }

    /**
     * Poll for new messages in a conversation (GET — AJAX).
     */
    public function poll()
    {
        Middleware::requireLogin();

        $userId = $_SESSION['user_id'];
        $otherUserId = $_GET['with'] ?? null;
        $lastId = $_GET['after'] ?? 0;

        if (!$otherUserId) {
            $this->json(['success' => false], 400);
            return;
        }

        $messageModel = new Message();
        $convo = $messageModel->getConversationBetween($userId, $otherUserId);

        if (!$convo) {
            $this->json(['success' => true, 'messages' => [], 'unread_count' => 0]);
            return;
        }

        // Mark as read if accepted
        if ($convo['status'] === 'accepted') {
            $messageModel->markConversationRead($convo['id'], $userId);
        }

        // Get new messages
        $db = \App\Database\Database::getInstance()->getConnection();
        $sql = "SELECT m.*, u.first_name, u.last_name, u.profile_picture
                FROM messages m
                JOIN users u ON u.id = m.sender_id
                WHERE m.conversation_id = :cid AND m.id > :last_id
                ORDER BY m.created_at ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute(['cid' => $convo['id'], 'last_id' => $lastId]);
        $newMessages = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->json([
            'success' => true,
            'messages' => $newMessages,
            'conversation_status' => $convo['status'],
            'unread_count' => $messageModel->getUnreadConversationCount($userId)
        ]);
    }

    /**
     * Get unread conversation count (GET — AJAX, for navbar badge).
     */
    public function unreadCount()
    {
        Middleware::requireLogin();

        $messageModel = new Message();
        $count = $messageModel->getUnreadConversationCount($_SESSION['user_id']);
        $requestCount = $messageModel->getPendingRequestCount($_SESSION['user_id']);

        $this->json(['success' => true, 'count' => $count, 'requests' => $requestCount]);
    }
}
