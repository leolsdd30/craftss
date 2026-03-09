<!-- Messages Inbox -->
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 flex items-center">
                    <svg class="h-8 w-8 mr-3 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Messages
                </h1>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-6" id="msg-tabs">
                <button onclick="switchMsgTab('messages')" data-msg-tab="messages"
                    class="msg-tab-btn border-indigo-500 text-indigo-600 whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center">
                    Messages
                    <?php if ($unreadCount > 0): ?>
                    <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700"><?= $unreadCount ?></span>
                    <?php endif; ?>
                </button>
                <button onclick="switchMsgTab('requests')" data-msg-tab="requests"
                    class="msg-tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center">
                    Requests
                    <?php if ($requestCount > 0): ?>
                    <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700"><?= $requestCount ?></span>
                    <?php endif; ?>
                </button>
            </nav>
        </div>

        <!-- Tab: Messages (Accepted Conversations) -->
        <div id="msg-tab-messages" class="msg-tab-content">
            <?php if (!empty($conversations)): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden divide-y divide-gray-100">
                <?php foreach ($conversations as $convo): ?>
                <?php $isUnread = $convo['unread_count'] > 0; ?>
                <a href="<?= APP_URL ?>/messages/conversation?with=<?= $convo['other_user_id'] ?>" 
                   class="flex items-center px-6 py-5 hover:bg-indigo-50/50 transition-colors duration-200 group <?= $isUnread ? 'bg-indigo-50/30' : '' ?>">
                    
                    <div class="relative flex-shrink-0">
                        <img src="<?= get_profile_picture_url($convo['profile_picture'] ?? 'default.png', $convo['first_name'], $convo['last_name']) ?>" 
                             alt="<?= htmlspecialchars($convo['first_name']) ?>" 
                             class="h-14 w-14 rounded-full object-cover border-2 <?= $isUnread ? 'border-indigo-300 ring-2 ring-indigo-100' : 'border-gray-100' ?> shadow-sm">
                        <?php if ($isUnread): ?>
                        <span class="absolute top-0 right-0 h-4 w-4 bg-indigo-500 rounded-full border-2 border-white"></span>
                        <?php endif; ?>
                    </div>

                    <div class="ml-4 flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <h3 class="text-sm font-bold <?= $isUnread ? 'text-gray-900' : 'text-gray-700' ?> truncate">
                                    <?= htmlspecialchars($convo['first_name'] . ' ' . $convo['last_name']) ?>
                                </h3>
                                <?php if ($convo['role'] === 'craftsman' && !empty($convo['service_category'])): ?>
                                <span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                    <?= htmlspecialchars($convo['service_category']) ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <span class="text-xs <?= $isUnread ? 'text-indigo-600 font-bold' : 'text-gray-400' ?> flex-shrink-0 ml-2">
                                <?php
                                $msgTime = strtotime($convo['last_message_at']);
                                $diff = time() - $msgTime;
                                if ($diff < 60) echo 'Just now';
                                elseif ($diff < 3600) echo floor($diff / 60) . 'm ago';
                                elseif ($diff < 86400) echo floor($diff / 3600) . 'h ago';
                                elseif ($diff < 604800) echo date('D', $msgTime);
                                else echo date('M d', $msgTime);
                                ?>
                            </span>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-sm <?= $isUnread ? 'text-gray-800 font-medium' : 'text-gray-500' ?> truncate max-w-md">
                                <?php if ($convo['last_sender_id'] == $_SESSION['user_id']): ?>
                                <span class="text-gray-400">You: </span>
                                <?php endif; ?>
                                <?= htmlspecialchars(mb_strimwidth($convo['last_message'], 0, 80, '...')) ?>
                            </p>
                            <?php if ($isUnread): ?>
                            <span class="ml-2 flex-shrink-0 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-500 text-white min-w-[20px]">
                                <?= $convo['unread_count'] ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <svg class="ml-3 h-5 w-5 text-gray-300 group-hover:text-indigo-400 transition-colors flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="bg-white rounded-2xl shadow-sm border-2 border-dashed border-gray-200 p-16 text-center">
                <div class="mx-auto h-20 w-20 bg-indigo-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="h-10 w-10 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">No conversations yet</h3>
                <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">
                    Start a conversation by visiting a craftsman's profile and clicking "Send Message". Conversations with active bookings appear here automatically.
                </p>
                <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="<?= APP_URL ?>/search" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150 shadow-md">
                        Find Craftsmen
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Tab: Requests (Pending) -->
        <div id="msg-tab-requests" class="msg-tab-content hidden">
            <?php if (!empty($requests)): ?>
            <div class="mb-4 bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-yellow-500 mr-2 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm text-yellow-800">These are message requests from people you haven't interacted with yet. Accept to start a conversation or decline to remove.</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden divide-y divide-gray-100">
                <?php foreach ($requests as $req): ?>
                <div class="flex items-center px-6 py-5" id="request-<?= $req['other_user_id'] ?>">
                    <div class="relative flex-shrink-0">
                        <img src="<?= get_profile_picture_url($req['profile_picture'] ?? 'default.png', $req['first_name'], $req['last_name']) ?>" 
                             alt="<?= htmlspecialchars($req['first_name']) ?>" 
                             class="h-14 w-14 rounded-full object-cover border-2 border-yellow-200 ring-2 ring-yellow-50 shadow-sm">
                        <span class="absolute top-0 right-0 h-4 w-4 bg-yellow-400 rounded-full border-2 border-white"></span>
                    </div>

                    <div class="ml-4 flex-1 min-w-0">
                        <div class="flex items-center space-x-2">
                            <h3 class="text-sm font-bold text-gray-900 truncate">
                                <?= htmlspecialchars($req['first_name'] . ' ' . $req['last_name']) ?>
                            </h3>
                            <?php if ($req['role'] === 'craftsman' && !empty($req['service_category'])): ?>
                            <span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                <?= htmlspecialchars($req['service_category']) ?>
                            </span>
                            <?php elseif ($req['role'] === 'homeowner'): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Homeowner</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-sm text-gray-500 truncate mt-1">
                            <?= htmlspecialchars(mb_strimwidth($req['last_message'], 0, 80, '...')) ?>
                        </p>
                        <p class="text-xs text-gray-400 mt-1"><?= $req['message_count'] ?> message<?= $req['message_count'] != 1 ? 's' : '' ?></p>
                    </div>

                    <div class="flex items-center space-x-2 flex-shrink-0 ml-4">
                        <a href="<?= APP_URL ?>/messages/conversation?with=<?= $req['other_user_id'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-lg text-gray-600 bg-gray-100 hover:bg-gray-200 transition duration-150">
                            View
                        </a>
                        <button type="button" onclick="acceptRequest(<?= $req['other_user_id'] ?>)" class="px-3 py-1.5 text-xs font-bold rounded-lg text-white bg-green-600 hover:bg-green-700 transition duration-150 shadow-sm">
                            Accept
                        </button>
                        <button type="button" onclick="declineRequest(<?= $req['other_user_id'] ?>)" class="px-3 py-1.5 text-xs font-bold rounded-lg text-red-600 bg-red-50 hover:bg-red-100 transition duration-150">
                            Decline
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="bg-white rounded-2xl shadow-sm border-2 border-dashed border-gray-200 p-16 text-center">
                <div class="mx-auto h-20 w-20 bg-yellow-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="h-10 w-10 text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">No message requests</h3>
                <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">
                    You're all caught up! New message requests from users without a booking will appear here.
                </p>
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
const appUrl = '<?= APP_URL ?>';
let pendingRequestUserId = null;
let pendingRequestAction = null;

function switchMsgTab(tabName) {
    document.querySelectorAll('.msg-tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.msg-tab-btn').forEach(btn => {
        btn.classList.remove('border-indigo-500', 'text-indigo-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    document.getElementById('msg-tab-' + tabName).classList.remove('hidden');
    const activeBtn = document.querySelector('[data-msg-tab="' + tabName + '"]');
    activeBtn.classList.remove('border-transparent', 'text-gray-500');
    activeBtn.classList.add('border-indigo-500', 'text-indigo-600');
}

function acceptRequest(userId) {
    showMsgModal('accept', userId);
}

function declineRequest(userId) {
    showMsgModal('decline', userId);
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
                var el = document.getElementById('request-' + userId);
                if (el) el.remove();
            }
        } else {
            alert(data.message || 'Action failed.');
        }
    } catch (e) {
        alert('An error occurred.');
    }
}

// Auto-switch to requests tab if there are pending requests and no messages
<?php if (empty($conversations) && !empty($requests)): ?>
switchMsgTab('requests');
<?php endif; ?>
</script>
