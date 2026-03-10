<!-- Notifications Page -->
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Notifications</h1>
                <p class="text-sm text-gray-500 mt-1">
                    <?php if ($unreadCount > 0): ?>
                        You have <span class="font-bold text-indigo-600"><?= $unreadCount ?></span> unread notification<?= $unreadCount !== 1 ? 's' : '' ?>
                    <?php else: ?>
                        You're all caught up!
                    <?php endif; ?>
                </p>
            </div>
            <?php if ($unreadCount > 0): ?>
            <button onclick="markAllRead()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition duration-150">
                <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Mark all as read
            </button>
            <?php endif; ?>
        </div>

        <!-- Notifications List -->
        <?php if (empty($notifications)): ?>
        <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900">No notifications yet</h3>
            <p class="mt-1 text-sm text-gray-500">When something happens, you'll see it here.</p>
        </div>
        <?php else: ?>
        <div class="space-y-2" id="notifications-list">
            <?php foreach ($notifications as $notif): ?>
            <?php
                // Icon & color mapping based on type
                $iconMap = [
                    'booking_new' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />'],
                    'booking_accepted' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                    'booking_declined' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                    'booking_completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />'],
                    'quote_new' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />'],
                    'quote_accepted' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                    'quote_rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                    'review_new' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />'],
                    'message_new' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />'],
                    'message_request' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />'],
                ];
                $default = ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />'];
                $style = $iconMap[$notif['type']] ?? $default;

                // Time ago
                $diff = time() - strtotime($notif['created_at']);
                if ($diff < 60) $timeAgo = 'Just now';
                elseif ($diff < 3600) $timeAgo = floor($diff / 60) . 'm ago';
                elseif ($diff < 86400) $timeAgo = floor($diff / 3600) . 'h ago';
                elseif ($diff < 604800) $timeAgo = floor($diff / 86400) . 'd ago';
                else $timeAgo = date('M d', strtotime($notif['created_at']));
            ?>
            <div class="bg-white rounded-xl shadow-sm border <?= $notif['is_read'] ? 'border-gray-100' : 'border-indigo-200 bg-indigo-50/30' ?> p-4 flex items-start space-x-4 hover:shadow-md transition-shadow duration-200 group <?= !$notif['is_read'] ? 'ring-1 ring-indigo-100' : '' ?>"
                 id="notif-<?= $notif['id'] ?>">
                <!-- Icon -->
                <div class="flex-shrink-0 <?= $style['bg'] ?> rounded-full p-2.5">
                    <svg class="h-5 w-5 <?= $style['text'] ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <?= $style['icon'] ?>
                    </svg>
                </div>
                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-bold text-gray-900 <?= !$notif['is_read'] ? '' : 'font-medium' ?>">
                                <?= htmlspecialchars($notif['title']) ?>
                                <?php if (!$notif['is_read']): ?>
                                <span class="inline-block w-2 h-2 bg-indigo-500 rounded-full ml-1 align-middle"></span>
                                <?php endif; ?>
                            </p>
                            <p class="text-sm text-gray-600 mt-0.5"><?= htmlspecialchars($notif['message']) ?></p>
                        </div>
                        <span class="text-xs text-gray-400 whitespace-nowrap ml-4 mt-0.5"><?= $timeAgo ?></span>
                    </div>
                    <?php if (!empty($notif['link'])): ?>
                    <?php
                        // Split link into base URL and hash fragment so urlencode doesn't destroy the #
                        $linkParts = explode('#', $notif['link'], 2);
                        $linkBase = $linkParts[0];
                        $linkHash = $linkParts[1] ?? '';
                        $notifHref = APP_URL . '/notifications/read?id=' . $notif['id'] . '&redirect=' . urlencode($linkBase);
                        if (!empty($linkHash)) {
                            $notifHref .= '&hash=' . urlencode($linkHash);
                        }
                    ?>
                    <a href="<?= $notifHref ?>" 
                       class="inline-flex items-center mt-2 text-xs font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                        View Details
                        <svg class="ml-1 h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

    </div>
</div>

<script>
async function markAllRead() {
    try {
        const response = await fetch('<?= APP_URL ?>/notifications/mark-all-read', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        if (data.success) {
            window.location.reload();
        }
    } catch (e) {
        console.error('Error:', e);
    }
}
</script>
