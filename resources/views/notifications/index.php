<!-- Notifications Page -->
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 sm:gap-0 mb-6">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white"><?= __('notifications.title') ?></h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    <?php if ($unreadCount > 0): ?>
                        <?= __('notifications.unread_count', ['count' => $unreadCount]) ?>
                    <?php else: ?>
                        <?= __('notifications.all_caught_up') ?>
                    <?php endif; ?>
                </p>
            </div>
            <?php if (!empty($notifications)): ?>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <?php if ($unreadCount > 0): ?>
                <button onclick="markAllRead()"
                        class="flex-1 sm:flex-none justify-center inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition duration-150 whitespace-nowrap dark:bg-indigo-900/30 dark:border-indigo-800 dark:text-indigo-400 dark:hover:bg-indigo-900/50">
                    <svg class="h-4 w-4 <?= __('lang') === 'ar' ? 'ml-1.5' : 'mr-1.5' ?> flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <?= __('notifications.mark_all_read') ?>
                </button>
                <?php endif; ?>
                <button onclick="clearAll()"
                        class="flex-1 sm:flex-none justify-center inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-red-50 hover:text-red-600 hover:border-red-200 transition duration-150 whitespace-nowrap dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-red-900/30 dark:hover:text-red-400 dark:hover:border-red-800">
                    <svg class="h-4 w-4 <?= __('lang') === 'ar' ? 'ml-1.5' : 'mr-1.5' ?> flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <?= __('notifications.clear_all') ?>
                </button>
            </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($notifications) && $unreadCount > 0): ?>
        <!-- Unread Only Toggle -->
        <div class="flex items-center gap-2 mb-6">
            <button id="filter-all-btn"
                    onclick="setFilter('all')"
                    class="px-3 py-1.5 text-xs font-semibold rounded-full transition duration-150 bg-indigo-600 dark:bg-indigo-500 text-white">
                <?= __('notifications.filter_all') ?>
            </button>
            <button id="filter-unread-btn"
                    onclick="setFilter('unread')"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full transition duration-150 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:border-indigo-300 dark:hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400">
                <?= __('notifications.filter_unread') ?>
                <span class="<?= __('lang') === 'ar' ? 'mr-1.5' : 'ml-1.5' ?> inline-flex items-center justify-center w-4 h-4 text-xs font-bold rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300"><?= $unreadCount ?></span>
            </button>
        </div>
        <?php endif; ?>

        <!-- Notifications List -->
        <?php if (empty($notifications)): ?>
        <div class="bg-white dark:bg-gray-800 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 p-16 text-center">
            <div class="mx-auto h-16 w-16 bg-gray-50 dark:bg-gray-700/50 rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-gray-300 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900 dark:text-white"><?= __('notifications.empty_title') ?></h3>
            <p class="mt-1 text-sm text-gray-400 dark:text-gray-500"><?= __('notifications.empty_desc') ?></p>
        </div>

        <?php else:

            // ── Group notifications by date ──────────────────────────────
            $groups = [];
            $today     = date('Y-m-d');
            $yesterday = date('Y-m-d', strtotime('-1 day'));

            foreach ($notifications as $notif) {
                $date = date('Y-m-d', strtotime($notif['created_at']));
                if ($date === $today) {
                    $labelKey = 'today';
                } elseif ($date === $yesterday) {
                    $labelKey = 'yesterday';
                } elseif (strtotime($notif['created_at']) >= strtotime('-7 days')) {
                    $labelKey = 'this_week';
                } else {
                    $labelKey = 'earlier';
                }
                $groups[$labelKey][] = $notif;
            }

            $orderedLabels = ['today', 'yesterday', 'this_week', 'earlier'];

            $iconMap = [
                'booking_accepted'  => ['bg' => 'bg-green-100 dark:bg-green-900/40',  'text' => 'text-green-600 dark:text-green-400',  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                'booking_declined'  => ['bg' => 'bg-red-100 dark:bg-red-900/40',    'text' => 'text-red-500 dark:text-red-400',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                'booking_pending'   => ['bg' => 'bg-amber-100 dark:bg-amber-900/40',  'text' => 'text-amber-600 dark:text-amber-400',  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                'booking_completed' => ['bg' => 'bg-green-100 dark:bg-green-900/40',  'text' => 'text-green-600 dark:text-green-400',  'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'],
                'quote_new'         => ['bg' => 'bg-blue-100 dark:bg-blue-900/40',   'text' => 'text-blue-600 dark:text-blue-400',   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>'],
                'counter_offered'   => ['bg' => 'bg-orange-100 dark:bg-orange-900/40', 'text' => 'text-orange-600 dark:text-orange-400', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>'],
                'counter_rejected'  => ['bg' => 'bg-red-100 dark:bg-red-900/40',    'text' => 'text-red-500 dark:text-red-400',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                'review_new'        => ['bg' => 'bg-yellow-100 dark:bg-yellow-900/40', 'text' => 'text-yellow-600 dark:text-yellow-400', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>'],
                'message_new'       => ['bg' => 'bg-indigo-100 dark:bg-indigo-900/40', 'text' => 'text-indigo-600 dark:text-indigo-400', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 12.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>'],
                'message_request'   => ['bg' => 'bg-purple-100 dark:bg-purple-900/40', 'text' => 'text-purple-600 dark:text-purple-400', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
            ];
            $defaultIcon = ['bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-500 dark:text-gray-400', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>'];
        ?>

        <div class="space-y-6" id="notifications-list">
            <?php foreach ($orderedLabels as $label):
                if (empty($groups[$label])) continue;
                $groupUnreadCount = count(array_filter($groups[$label], fn($n) => !$n['is_read']));
            ?>
            <div data-group="<?= strtolower(str_replace(' ', '-', $label)) ?>">

                <!-- Group Label -->
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider"><?= __('notifications.' . $label) ?></span>
                    <?php if ($groupUnreadCount > 0): ?>
                    <span class="group-unread-badge inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300">
                        <?= $groupUnreadCount ?> <?= __('notifications.unread_badge') ?>
                    </span>
                    <?php endif; ?>
                    <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>
                </div>

                <div class="space-y-2">
                    <?php foreach ($groups[$label] as $notif):
                        $style   = $iconMap[$notif['type']] ?? $defaultIcon;
                        $diff    = time() - strtotime($notif['created_at']);
                        if      ($diff < 60)     $timeAgo = __('time.just_now');
                        elseif  ($diff < 3600)   $timeAgo = str_replace(':m', floor($diff / 60), __('time.m_ago'));
                        elseif  ($diff < 86400)  $timeAgo = str_replace(':h', floor($diff / 3600), __('time.h_ago'));
                        elseif  ($diff < 604800) $timeAgo = str_replace(':d', floor($diff / 86400), __('time.d_ago'));
                        else                     $timeAgo = date('Y/m/d', strtotime($notif['created_at']));

                        $notifHref = null;
                        if (!empty($notif['link'])) {
                            $linkParts = explode('#', $notif['link'], 2);
                            $linkBase  = $linkParts[0];
                            $linkHash  = $linkParts[1] ?? '';
                            $notifHref = APP_URL . '/notifications/read?id=' . $notif['id'] . '&redirect=' . urlencode($linkBase);
                            if (!empty($linkHash)) $notifHref .= '&hash=' . urlencode($linkHash);
                        }
                    ?>
                    <div class="group relative bg-white dark:bg-gray-800 rounded-xl shadow-sm border <?= $notif['is_read'] ? 'border-gray-100 dark:border-gray-700' : 'border-indigo-200 dark:border-indigo-800 bg-indigo-50/20 dark:bg-indigo-900/20 ring-1 ring-indigo-100 dark:ring-indigo-900/50' ?> p-4 flex items-start gap-4 hover:shadow-md transition-all duration-200"
                         id="notif-<?= $notif['id'] ?>"
                         data-read="<?= $notif['is_read'] ? '1' : '0' ?>">

                        <!-- Type Icon -->
                        <div class="flex-shrink-0 <?= $style['bg'] ?> rounded-xl p-2.5 mt-0.5">
                            <svg class="h-5 w-5 <?= $style['text'] ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <?= $style['icon'] ?>
                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0 <?= __('lang') === 'ar' ? 'pl-8' : 'pr-8' ?>">
                            <div class="flex items-start justify-between gap-2">
                                <?php
                                    $translatedTitle = __('notif_types.' . $notif['type']);
                                    if ($translatedTitle === 'notif_types.' . $notif['type']) {
                                        $translatedTitle = htmlspecialchars($notif['title']);
                                    }

                                    $msg = htmlspecialchars($notif['message']);

                                    // Bold the user's name if the message starts with it
                                    $userNamesSuffixes = [
                                        ' sent you a booking request.',
                                        ' has sent a counter-offer for your booking. Please review the changes.',
                                        ' accepted your counter-offer. The job is now in progress!',
                                        ' has declined your counter-offer. The booking has been cancelled.',
                                        ' has confirmed the work is done. Great job!',
                                        ' submitted a quote of ',
                                        ' left you a ',
                                        ' sent you a new message.',
                                        ' wants to send you a message.'
                                    ];
                                    foreach ($userNamesSuffixes as $suffix) {
                                        if (($pos = strpos($msg, $suffix)) !== false && $pos > 0 && $pos < 30) {
                                            $name = substr($msg, 0, $pos);
                                            $rest = substr($msg, $pos);
                                            $msg = '<span class="font-bold text-gray-900 dark:text-white">' . $name . '</span>' . $rest;
                                            break;
                                        }
                                    }

                                    if (__('lang') === 'ar') {
                                        $arMsg = [
                                            ' sent you a booking request.' => ' أرسل لك طلب حجز.',
                                            'Your booking request has been accepted. The job is now in progress.' => 'تم قبول طلب الحجز الخاص بك. العمل قيد التنفيذ الآن.',
                                            ' has sent a counter-offer for your booking. Please review the changes.' => ' أرسل عرضًا مضادًا لحجزك. يرجى مراجعة التغييرات.',
                                            ' accepted your counter-offer. The job is now in progress!' => ' قبل العرض المضاد. العمل قيد التنفيذ الآن!',
                                            ' has declined your counter-offer. The booking has been cancelled.' => ' رفض العرض المضاد. تم إلغاء الحجز.',
                                            'Unfortunately, your booking request was declined by the craftsman.' => 'للأسف، رفض الحرفي طلب الحجز الخاص بك.',
                                            'The craftsman has marked the job as complete. Please confirm the work is done.' => 'حدد الحرفي الوظيفة كمكتملة. يرجى تأكيد إنجاز العمل.',
                                            ' has confirmed the work is done. Great job!' => ' أكد أن العمل قد اكتمل. عمل رائع!',
                                            ' submitted a quote of ' => ' قدم عرض سعر بقيمة ',
                                            ' DZD on your job: ' => ' دينار جزائري على وظيفتك: ',
                                            'Your quote on &quot;' => 'تم قبول عرض السعر الخاص بك على &quot;',
                                            '&quot; has been accepted!' => '&quot;!',
                                            '&quot; was not accepted.' => '&quot; لم يتم قبوله.',
                                            ' left you a ' => ' ترك لك تقييم ',
                                            '-star review!' => ' نجوم!',
                                            ' sent you a new message.' => ' أرسل لك رسالة جديدة.',
                                            ' wants to send you a message.' => ' يريد إرسال رسالة إليك.'
                                        ];
                                        $msg = str_replace(array_keys($arMsg), array_values($arMsg), $msg);
                                    }
                                ?>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white leading-snug">
                                    <?= $translatedTitle ?>
                                    <?php if (!$notif['is_read']): ?>
                                    <span class="inline-block w-2 h-2 bg-indigo-500 dark:bg-indigo-400 rounded-full <?= __('lang') === 'ar' ? 'mr-1' : 'ml-1' ?> align-middle" id="unread-dot-<?= $notif['id'] ?>"></span>
                                    <?php endif; ?>
                                </p>
                                <span class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap flex-shrink-0"><?= $timeAgo ?></span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 leading-relaxed break-words"><?= $msg ?></p>

                            <div class="flex items-center gap-4 mt-2">
                                <?php if ($notifHref): ?>
                                <a href="<?= $notifHref ?>"
                                   class="inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors">
                                    <?= __('notifications.view_details') ?>
                                    <svg class="<?= __('lang') === 'ar' ? 'mr-1' : 'ml-1' ?> h-3 w-3 <?= __('lang') === 'ar' ? 'transform rotate-180' : '' ?>" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </a>
                                <?php endif; ?>

                                <?php if (!$notif['is_read']): ?>
                                <button onclick="markOneRead(<?= $notif['id'] ?>)"
                                        id="mark-read-btn-<?= $notif['id'] ?>"
                                        class="inline-flex items-center text-xs font-medium text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    <svg class="h-3 w-3 <?= __('lang') === 'ar' ? 'ml-1' : 'mr-1' ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <?= __('notifications.mark_as_read') ?>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Dismiss button — visible on hover -->
                        <button onclick="dismissNotification(<?= $notif['id'] ?>)"
                                title="<?= __('notifications.dismiss') ?>"
                                class="absolute top-3 <?= __('lang') === 'ar' ? 'left-3' : 'right-3' ?> p-1 rounded-lg text-gray-500 hover:text-red-500 hover:bg-red-100 dark:hover:bg-red-900/30 opacity-0 group-hover:opacity-100 transition-all duration-150">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty state shown by JS when unread filter is active but no unread remain -->
        <div id="unread-empty" class="hidden bg-white dark:bg-gray-800 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 p-12 text-center mt-4">
            <div class="mx-auto h-14 w-14 bg-indigo-50 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mb-3">
                <svg class="h-7 w-7 text-indigo-300 dark:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white"><?= __('notifications.no_unread_title') ?></h3>
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500"><?= __('notifications.all_caught_up') ?></p>
        </div>

        <?php endif; ?>

    </div>
</div>

<script>
const appUrl = '<?= APP_URL ?>';
const csrf   = '<?= $_SESSION['csrf_token'] ?? '' ?>';

// ── Filter: All / Unread ─────────────────────────────────────────────
let currentFilter = 'all';

function setFilter(filter) {
    currentFilter = filter;
    const allBtn    = document.getElementById('filter-all-btn');
    const unreadBtn = document.getElementById('filter-unread-btn');
    if (!allBtn || !unreadBtn) return;

    if (filter === 'all') {
        allBtn.className    = 'px-3 py-1.5 text-xs font-semibold rounded-full transition duration-150 bg-indigo-600 dark:bg-indigo-500 text-white';
        unreadBtn.className = 'inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full transition duration-150 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:border-indigo-300 dark:hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400';
    } else {
        unreadBtn.className = 'inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full transition duration-150 bg-indigo-600 dark:bg-indigo-500 text-white';
        allBtn.className    = 'px-3 py-1.5 text-xs font-semibold rounded-full transition duration-150 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 hover:border-indigo-300 dark:hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400';
    }
    applyFilter();
}

function applyFilter() {
    const items = document.querySelectorAll('#notifications-list [id^="notif-"]');
    let visibleUnread = 0;

    items.forEach(el => {
        const isRead = el.dataset.read === '1';
        el.style.display = (currentFilter === 'unread' && isRead) ? 'none' : '';
        if (!isRead && el.style.display !== 'none') visibleUnread++;
    });

    // Hide groups with no visible items
    document.querySelectorAll('#notifications-list > div[data-group]').forEach(group => {
        const hasVisible = Array.from(group.querySelectorAll('[id^="notif-"]')).some(el => el.style.display !== 'none');
        group.style.display = hasVisible ? '' : 'none';
    });

    // Show unread-empty state if filtering unread and nothing is left
    const emptyEl = document.getElementById('unread-empty');
    if (emptyEl) emptyEl.classList.toggle('hidden', !(currentFilter === 'unread' && visibleUnread === 0));
}

// ── Mark single as read ──────────────────────────────────────────────
async function markOneRead(id) {
    try {
        await fetch(appUrl + '/notifications/read?id=' + id, {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
    } catch (e) { console.error(e); }

    const el = document.getElementById('notif-' + id);
    if (el) {
        el.dataset.read = '1';
        el.classList.remove('border-indigo-200', 'dark:border-indigo-800', 'bg-indigo-50/20', 'dark:bg-indigo-900/20', 'ring-1', 'ring-indigo-100', 'dark:ring-indigo-900/50');
        el.classList.add('border-gray-100', 'dark:border-gray-700');
    }
    const dot = document.getElementById('unread-dot-' + id);
    if (dot) dot.remove();
    const btn = document.getElementById('mark-read-btn-' + id);
    if (btn) btn.remove();

    updateGroupBadges();
    applyFilter();
}
    
// ── Mark all read ────────────────────────────────────────────────────
async function markAllRead() {
    try {
        const res  = await fetch(appUrl + '/notifications/mark-all-read', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ csrf_token: csrf })
        });
        const data = await res.json();
        if (data.success) window.location.reload();
    } catch (e) { console.error(e); }
}

// ── Dismiss single ───────────────────────────────────────────────────
async function dismissNotification(id) {
    const el = document.getElementById('notif-' + id);
    if (!el) return;
    el.style.transition = 'all 0.2s ease';
    el.style.opacity    = '0';
    el.style.transform  = 'translateX(12px)';

    try {
        await fetch(appUrl + '/notifications/delete', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ notification_id: id, csrf_token: csrf })
        });
    } catch (e) { console.error(e); }

    setTimeout(() => {
        el.remove();
        cleanupEmptyGroups();
        updateGroupBadges();
    }, 200);
}

// ── Clear all ────────────────────────────────────────────────────────
function clearAll() {
    showConfirmModal(
        '<?= addslashes(__('notifications.modal_clear_title')) ?>',
        '<?= addslashes(__('notifications.modal_clear_desc')) ?>',
        'decline'
    );
}

async function doClearAll() {
    hideConfirmModal();
    try {
        await fetch(appUrl + '/notifications/delete-all', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ csrf_token: csrf })
        });
        window.location.reload();
    } catch (e) { console.error(e); }
}

// ── Update group unread badges after JS actions ──────────────────────
function updateGroupBadges() {
    document.querySelectorAll('#notifications-list > div[data-group]').forEach(group => {
        const unreadCount = group.querySelectorAll('[id^="notif-"][data-read="0"]').length;
        const badge = group.querySelector('.group-unread-badge');
        if (badge) {
            badge.textContent = unreadCount + ' <?= addslashes(__('notifications.unread_badge')) ?>';
            badge.style.display = unreadCount > 0 ? '' : 'none';
        }
    });
}

// ── Remove empty date groups ─────────────────────────────────────────
function cleanupEmptyGroups() {
    document.querySelectorAll('#notifications-list > div[data-group]').forEach(group => {
        if (group.querySelectorAll('[id^="notif-"]').length === 0) group.remove();
    });
    const list = document.getElementById('notifications-list');
    if (list && list.querySelectorAll('[id^="notif-"]').length === 0) window.location.reload();
}

// ── Confirm Modal ────────────────────────────────────────────────────
function showConfirmModal(title, message, type) {
    document.getElementById('modal-title').textContent   = title;
    document.getElementById('modal-message').textContent = message;
    var btn = document.getElementById('modal-confirm-btn');
    var ia  = document.getElementById('modal-icon-accept');
    var id  = document.getElementById('modal-icon-decline');
    if (type === 'accept') {
        btn.className   = 'px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition duration-150';
        btn.textContent = '<?= addslashes(__('notifications.confirm')) ?>';
        ia.classList.remove('hidden'); id.classList.add('hidden');
    } else {
        btn.className   = 'px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition duration-150';
        btn.textContent = '<?= addslashes(__('notifications.yes_clear_all')) ?>';
        ia.classList.add('hidden'); id.classList.remove('hidden');
    }
    document.getElementById('confirm-modal').classList.remove('hidden');
}

function hideConfirmModal() {
    document.getElementById('confirm-modal').classList.add('hidden');
}

function confirmAction() {
    hideConfirmModal();
    doClearAll();
}
</script>

<!-- Confirm Modal -->
<div id="confirm-modal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" onclick="hideConfirmModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full transform transition-all">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div id="modal-icon-accept" class="hidden flex-shrink-0 bg-green-100 rounded-full p-2 <?= __('lang') === 'ar' ? 'ml-3' : 'mr-3' ?>">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div id="modal-icon-decline" class="hidden flex-shrink-0 bg-red-100 rounded-full p-2 <?= __('lang') === 'ar' ? 'ml-3' : 'mr-3' ?>">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 id="modal-title" class="text-lg font-bold text-gray-900 dark:text-white"></h3>
                </div>
                <p id="modal-message" class="text-sm text-gray-600 dark:text-gray-400 mb-6"></p>
                <div class="flex justify-end <?= __('lang') === 'ar' ? 'space-x-reverse space-x-3' : 'space-x-3' ?>">
                    <button onclick="hideConfirmModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 dark:text-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg transition duration-150">
                        <?= __('forms.cancel') ?>
                    </button>
                    <button id="modal-confirm-btn" onclick="confirmAction()"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 dark:bg-red-500 hover:bg-red-700 dark:hover:bg-red-600 rounded-lg transition duration-150">
                        <?= __('notifications.confirm') ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>