<!-- Conversation View -->
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto py-4 px-4 sm:px-6 lg:px-8">

        <!-- Chat Container -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col" style="height: calc(100vh - 140px);">

            <!-- Chat Header -->
            <div class="px-6 py-4 border-b border-gray-100 bg-white flex items-center justify-between flex-shrink-0">
                <div class="flex items-center space-x-4">
                    <a href="<?= APP_URL ?>/messages" class="p-2 -ml-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <img src="<?= get_profile_picture_url($otherUser['profile_picture'] ?? 'default.png', $otherUser['first_name'], $otherUser['last_name']) ?>" 
                         alt="<?= htmlspecialchars($otherUser['first_name']) ?>" 
                         class="h-11 w-11 rounded-full object-cover border-2 border-gray-100 shadow-sm">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">
                            <?= htmlspecialchars($otherUser['first_name'] . ' ' . $otherUser['last_name']) ?>
                        </h2>
                        <p class="text-xs text-gray-500 font-medium capitalize"><?= htmlspecialchars($otherUser['role']) ?></p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <?php if ($isRequest && !$isRecipient): ?>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">
                        <svg class="h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.414L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        Pending Request
                    </span>
                    <?php endif; ?>
                    <a href="<?= APP_URL ?>/profile/<?= $otherUser['username'] ?>" class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition duration-150">
                        View Profile
                    </a>
                </div>
            </div>

            <!-- Request Banner (for recipient of a pending request) -->
            <?php if ($isRequest && $isRecipient): ?>
            <div id="request-banner" class="px-6 py-4 bg-gradient-to-r from-yellow-50 to-amber-50 border-b border-yellow-200 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 rounded-full p-2 mr-3">
                            <svg class="h-5 w-5 text-yellow-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-yellow-800">Message Request</p>
                            <p class="text-xs text-yellow-700"><?= htmlspecialchars($otherUser['first_name']) ?> wants to chat with you. You have no active booking together.</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 flex-shrink-0 ml-4">
                        <button type="button" onclick="handleAcceptRequest()" class="px-4 py-2 text-xs font-bold rounded-lg text-white bg-green-600 hover:bg-green-700 transition duration-150 shadow-sm">
                            Accept
                        </button>
                        <button type="button" onclick="handleDeclineRequest()" class="px-4 py-2 text-xs font-bold rounded-lg text-red-600 bg-red-50 hover:bg-red-100 transition duration-150">
                            Decline
                        </button>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Request Sent Banner (for initiator)  -->
            <?php if ($isRequest && !$isRecipient): ?>
            <div class="px-6 py-3 bg-blue-50 border-b border-blue-100 flex-shrink-0">
                <div class="flex items-center">
                    <svg class="h-4 w-4 text-blue-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-xs text-blue-700">Your message was sent as a <span class="font-bold">request</span>. <?= htmlspecialchars($otherUser['first_name']) ?> needs to accept before a full conversation begins.</p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Messages Area -->
            <div id="messages-container" class="flex-1 overflow-y-auto px-6 py-6 space-y-1" style="scroll-behavior: smooth;">
                
                <?php if (empty($messages)): ?>
                <div class="flex flex-col items-center justify-center h-full text-center">
                    <div class="bg-indigo-50 rounded-full p-5 mb-4">
                        <svg class="h-10 w-10 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900">Start the conversation</h3>
                    <p class="text-sm text-gray-500 mt-1 max-w-xs">
                        Say hello to <?= htmlspecialchars($otherUser['first_name']) ?>! Send your first message below.
                    </p>
                </div>
                <?php else: ?>
                
                <?php 
                $lastDate = '';
                $lastSenderId = null;
                foreach ($messages as $index => $msg): 
                    $msgDate = date('Y-m-d', strtotime($msg['created_at']));
                    $isMe = $msg['sender_id'] == $_SESSION['user_id'];
                    $showAvatar = $msg['sender_id'] !== $lastSenderId;
                    $lastSenderId = $msg['sender_id'];
                ?>
                
                <?php if ($msgDate !== $lastDate): ?>
                <div class="flex items-center justify-center py-4">
                    <div class="border-t border-gray-200 flex-1"></div>
                    <span class="mx-4 text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <?php 
                        $today = date('Y-m-d');
                        $yesterday = date('Y-m-d', strtotime('-1 day'));
                        if ($msgDate === $today) echo 'Today';
                        elseif ($msgDate === $yesterday) echo 'Yesterday';
                        else echo date('M d, Y', strtotime($msgDate));
                        ?>
                    </span>
                    <div class="border-t border-gray-200 flex-1"></div>
                </div>
                <?php $lastDate = $msgDate; endif; ?>

                <div class="flex <?= $isMe ? 'justify-end' : 'justify-start' ?> <?= $showAvatar ? 'mt-4' : 'mt-1' ?>" data-message-id="<?= $msg['id'] ?>">
                    <?php if (!$isMe && $showAvatar): ?>
                    <img src="<?= get_profile_picture_url($msg['profile_picture'] ?? 'default.png', $msg['first_name'], $msg['last_name']) ?>" 
                         alt="" class="h-8 w-8 rounded-full object-cover flex-shrink-0 mt-1 shadow-sm border border-gray-100">
                    <?php elseif (!$isMe): ?>
                    <div class="w-8 flex-shrink-0"></div>
                    <?php endif; ?>
                    
                    <div class="<?= $isMe ? '' : 'ml-2.5' ?> max-w-[75%]">
                        <?php if ($showAvatar && !$isMe): ?>
                        <p class="text-xs font-semibold text-gray-500 mb-1 ml-1"><?= htmlspecialchars($msg['first_name']) ?></p>
                        <?php endif; ?>
                        <div class="<?= $isMe 
                            ? 'bg-indigo-600 text-white rounded-2xl rounded-br-md' 
                            : 'bg-gray-100 text-gray-800 rounded-2xl rounded-bl-md' ?> px-4 py-2.5 shadow-sm">
                            <p class="text-sm leading-relaxed whitespace-pre-wrap"><?= nl2br(htmlspecialchars($msg['message_body'])) ?></p>
                        </div>
                        <p class="text-[10px] <?= $isMe ? 'text-right' : 'text-left' ?> text-gray-400 mt-1 px-1">
                            <?= date('g:i A', strtotime($msg['created_at'])) ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Message Input -->
            <?php if ($canSend): ?>
            <div class="border-t border-gray-100 bg-white px-4 py-4 flex-shrink-0">
                <form id="message-form" class="flex items-end space-x-3">
                    <div class="flex-1 relative">
                        <textarea id="message-input" name="message" rows="1" 
                                  placeholder="Type your message..." 
                                  class="block w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-3 pr-12 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100 focus:bg-white resize-none transition-all duration-200"
                                  style="max-height: 120px; min-height: 44px;"
                                  oninput="this.style.height = 'auto'; this.style.height = Math.min(this.scrollHeight, 120) + 'px';"
                        ></textarea>
                    </div>
                    <button type="submit" id="send-btn" 
                            class="inline-flex items-center justify-center p-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex-shrink-0">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                        </svg>
                    </button>
                </form>
            </div>
            <?php elseif ($isRequest && $isRecipient): ?>
            <!-- Can't reply until accepted -->
            <div class="border-t border-gray-100 bg-gray-50 px-6 py-4 flex-shrink-0 text-center flex items-center justify-center space-x-2">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
                <p class="text-sm font-medium text-gray-500">Accept the message request above to reply</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Message Request Modal -->
<div id="msg-request-modal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" onclick="hideMsgModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div id="msg-modal-icon-accept" class="hidden flex-shrink-0 bg-green-100 rounded-full p-2 mr-3">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div id="msg-modal-icon-decline" class="hidden flex-shrink-0 bg-red-100 rounded-full p-2 mr-3">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 id="msg-modal-title" class="text-lg font-bold text-gray-900"></h3>
                </div>
                <p id="msg-modal-message" class="text-sm text-gray-600 mb-6"></p>
                <div class="flex justify-end space-x-3">
                    <button onclick="hideMsgModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-150">Cancel</button>
                    <button id="msg-modal-confirm" class="px-4 py-2 text-sm font-medium text-white rounded-lg transition duration-150">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const currentUserId = <?= $_SESSION['user_id'] ?>;
const otherUserId = <?= $otherUser['id'] ?>;
const appUrl = '<?= APP_URL ?>';
let lastMessageId = <?= !empty($messages) ? end($messages)['id'] : 0 ?>;
let conversationAccepted = <?= (!$isRequest) ? 'true' : 'false' ?>;
let pendingRequestUserId = null;
let pendingRequestAction = null;

// Scroll to bottom on load
const container = document.getElementById('messages-container');
container.scrollTop = container.scrollHeight;

// Handle form submission
const form = document.getElementById('message-form');
if (form) {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const input = document.getElementById('message-input');
        const message = input.value.trim();
        const sendBtn = document.getElementById('send-btn');
        
        if (!message) return;
        
        sendBtn.disabled = true;
        
        const now = new Date();
        const timeStr = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
        
        const emptyState = container.querySelector('.flex.flex-col.items-center.justify-center');
        if (emptyState) emptyState.remove();
        
        const msgHtml = `
            <div class="flex justify-end mt-1">
                <div class="max-w-[75%]">
                    <div class="bg-indigo-600 text-white rounded-2xl rounded-br-md px-4 py-2.5 shadow-sm">
                        <p class="text-sm leading-relaxed whitespace-pre-wrap">${escapeHtml(message)}</p>
                    </div>
                    <p class="text-[10px] text-right text-gray-400 mt-1 px-1">${timeStr}</p>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', msgHtml);
        container.scrollTop = container.scrollHeight;
        
        input.value = '';
        input.style.height = 'auto';
        
        try {
            const response = await fetch(appUrl + '/messages/send', {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ receiver_id: otherUserId, message: message })
            });
            
            const data = await response.json();
            if (!data.success) {
                alert(data.message || 'Failed to send message.');
            }
        } catch (err) {
            console.error('Send error:', err);
            alert('Failed to send message. Please try again.');
        }
        
        sendBtn.disabled = false;
        input.focus();
    });
}

// Enter to send
const msgInput = document.getElementById('message-input');
if (msgInput) {
    msgInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('message-form').dispatchEvent(new Event('submit'));
        }
    });
}

// Poll for new messages
setInterval(async function() {
    try {
        const response = await fetch(appUrl + '/messages/poll?with=' + otherUserId + '&after=' + lastMessageId, {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' }
        });
        const data = await response.json();
        
        if (data.success && data.messages && data.messages.length > 0) {
            const wasAtBottom = container.scrollHeight - container.scrollTop - container.clientHeight < 100;
            
            data.messages.forEach(function(msg) {
                if (msg.sender_id == currentUserId) return;
                
                lastMessageId = Math.max(lastMessageId, msg.id);
                
                const time = new Date(msg.created_at).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                const profilePicUrl = `${appUrl}/uploads/profile/${msg.profile_picture || 'default.png'}`;
                const fallbackUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(msg.first_name + '+' + msg.last_name)}&background=6366f1&color=fff&size=64`;
                
                const html = `
                    <div class="flex justify-start mt-4" data-message-id="${msg.id}">
                        <img src="${profilePicUrl}" 
                             alt="" class="h-8 w-8 rounded-full object-cover flex-shrink-0 mt-1 shadow-sm border border-gray-100"
                             onerror="this.src='${fallbackUrl}'">
                        <div class="ml-2.5 max-w-[75%]">
                            <p class="text-xs font-semibold text-gray-500 mb-1 ml-1">${escapeHtml(msg.first_name)}</p>
                            <div class="bg-gray-100 text-gray-800 rounded-2xl rounded-bl-md px-4 py-2.5 shadow-sm">
                                <p class="text-sm leading-relaxed whitespace-pre-wrap">${escapeHtml(msg.message_body)}</p>
                            </div>
                            <p class="text-[10px] text-left text-gray-400 mt-1 px-1">${time}</p>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', html);
            });
            
            if (wasAtBottom) {
                container.scrollTop = container.scrollHeight;
            }
        }
        
        // Check if conversation was promoted (auto-accepted due to booking)
        if (data.conversation_status === 'accepted' && !conversationAccepted) {
            window.location.reload(); // Reload to show the send form
        }
    } catch (err) { /* Silent */ }
}, 3000);

// Accept/Decline request handlers
function handleAcceptRequest() {
    showMsgModal('accept', otherUserId);
}

function handleDeclineRequest() {
    showMsgModal('decline', otherUserId);
}

function showMsgModal(action, userId) {
    window._pendingUserId = userId;
    window._pendingAction = action;

    var iconAccept = document.getElementById('msg-modal-icon-accept');
    var iconDecline = document.getElementById('msg-modal-icon-decline');
    var title = document.getElementById('msg-modal-title');
    var message = document.getElementById('msg-modal-message');
    var confirmBtn = document.getElementById('msg-modal-confirm');

    if (action === 'accept') {
        iconAccept.classList.remove('hidden');
        iconDecline.classList.add('hidden');
        title.textContent = 'Accept Message Request';
        message.textContent = 'This will allow you to have a full conversation with this user. They will be moved to your main messages.';
        confirmBtn.textContent = 'Accept Request';
        confirmBtn.className = 'px-4 py-2 text-sm font-medium text-white rounded-lg transition duration-150 bg-green-600 hover:bg-green-700';
    } else {
        iconAccept.classList.add('hidden');
        iconDecline.classList.remove('hidden');
        title.textContent = 'Decline Message Request';
        message.textContent = 'Are you sure? This person will not be able to message you again unless you have a booking together.';
        confirmBtn.textContent = 'Decline Request';
        confirmBtn.className = 'px-4 py-2 text-sm font-medium text-white rounded-lg transition duration-150 bg-red-600 hover:bg-red-700';
    }

    confirmBtn.onclick = confirmMsgAction;
    document.getElementById('msg-request-modal').classList.remove('hidden');
}

function hideMsgModal() {
    document.getElementById('msg-request-modal').classList.add('hidden');
    window._pendingUserId = null;
    window._pendingAction = null;
}

async function confirmMsgAction() {
    var action = window._pendingAction;
    var userId = window._pendingUserId;
    if (!action || !userId) return;
    hideMsgModal();

    var endpoint = action === 'accept' ? '/messages/accept-request' : '/messages/decline-request';
    try {
        var response = await fetch(appUrl + endpoint, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ user_id: userId })
        });
        var data = await response.json();
        if (data.success) {
            if (action === 'accept') {
                window.location.reload();
            } else {
                window.location.href = appUrl + '/messages';
            }
        } else {
            alert(data.message || 'Action failed.');
        }
    } catch (e) {
        alert('An error occurred.');
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
