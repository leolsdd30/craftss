<?php
// Determine the full current URL for OG tags
$absoluteAppUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
    . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
    . rtrim(parse_url(APP_URL, PHP_URL_PATH) ?? '', '/');
$currentUrl = $absoluteAppUrl . ($_SERVER['REQUEST_URI'] ?? '/');

// Meta defaults — controllers override these by passing their own values
$title = $pageTitle       ?? 'Crafts - Find Skilled Professionals';
$desc  = $metaDescription ?? 'Crafts is the easiest way to find and hire reliable freelance craftsmen and service professionals in Algeria.';
$defaultImg = $absoluteAppUrl . '/public/assets/og-image.png';
$img = $ogImage ?? $defaultImg;
if (!empty($img) && strpos($img, 'http') !== 0) {
    if (strpos($img, APP_URL) === 0) {
        $img = substr($img, strlen(APP_URL));
    }
    $img = $absoluteAppUrl . '/' . ltrim($img, '/');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>

    <meta name="description" content="<?= htmlspecialchars($desc) ?>">

    <?php if (strpos($_SERVER['REQUEST_URI'] ?? '', '/admin') !== false || strpos($_SERVER['REQUEST_URI'] ?? '', '/dashboard') !== false): ?>
    <meta name="robots" content="noindex, nofollow">
    <?php endif; ?>

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= htmlspecialchars($currentUrl) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($desc) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($img) ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= htmlspecialchars($currentUrl) ?>">
    <meta property="twitter:title" content="<?= htmlspecialchars($title) ?>">
    <meta property="twitter:description" content="<?= htmlspecialchars($desc) ?>">
    <meta property="twitter:image" content="<?= htmlspecialchars($img) ?>">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {}
            }
        }
    </script>

    <!-- Instant Dark Mode Applicator (Prevents flash of white) -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Toggle function called by the Sun/Moon button or the mobile switch
        function toggleDarkMode() {
            document.documentElement.classList.add('theme-transitioning');
            document.documentElement.classList.toggle('dark');
            if (document.documentElement.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
            } else {
                localStorage.setItem('theme', 'light');
            }
            setTimeout(function() {
                document.documentElement.classList.remove('theme-transitioning');
            }, 300);
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }

        /* ── Global dark mode transition applied ONLY during toggle ── */
        html.theme-transitioning * {
            transition-property: background-color, border-color, color, fill, stroke, box-shadow !important;
            transition-duration: 200ms !important;
            transition-timing-function: ease !important;
        }

        /* ── Scrollbar ─────────────────────────────────────────── */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background-color: #818cf8; }
        * { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
        html.dark ::-webkit-scrollbar-thumb { background-color: #475569; }
        html.dark * { scrollbar-color: #475569 transparent; }

        /* ── Nav pills ─────────────────────────────────────────── */
        .nav-pill {
            display: inline-flex; align-items: center;
            padding: 0.375rem 0.75rem; border-radius: 0.5rem;
            font-size: 0.875rem; font-weight: 500;
            transition: background-color 0.15s, color 0.15s;
            text-decoration: none; white-space: nowrap;
            border: none; cursor: pointer; background: transparent;
        }
        .nav-pill-active  { background-color: #eef2ff; color: #4338ca; }
        .nav-pill-inactive { color: #6b7280; }
        .nav-pill-inactive:hover { background-color: #f3f4f6; color: #111827; }
        /* Dark mode nav pills */
        html.dark .nav-pill-active  { background-color: rgba(79, 70, 229, 0.15); color: #a5b4fc; }
        html.dark .nav-pill-inactive { color: #9ca3af; }
        html.dark .nav-pill-inactive:hover { background-color: #374151; color: #f3f4f6; }

        /* ── Services dropdown ─────────────────────────────────── */
        .services-item {
            display: flex; flex-direction: column; align-items: center;
            padding: 0.625rem 0.5rem; border-radius: 0.75rem;
            text-decoration: none; transition: background-color 0.15s; gap: 0.375rem;
        }
        .services-item:hover { background-color: #f9fafb; }
        html.dark .services-item:hover { background-color: #374151; }
        .services-icon {
            width: 2.25rem; height: 2.25rem; border-radius: 0.625rem;
            display: flex; align-items: center; justify-content: center;
            transition: background-color 0.15s; flex-shrink: 0;
        }
        .services-label {
            font-size: 0.75rem; font-weight: 600; text-align: center;
        }
        #services-dropdown-menu {
            opacity: 0; transform: scale(0.97) translateY(-6px);
            transition: opacity 0.2s ease, transform 0.2s ease;
            display: block !important; pointer-events: none;
        }
        #services-dropdown-menu.services-open {
            opacity: 1; transform: scale(1) translateY(0); pointer-events: auto;
        }

        .dropdown-item {
            display: flex; align-items: center; padding: 0.5rem 1rem;
            font-size: 0.875rem; color: #374151; transition: background-color 0.15s;
            text-decoration: none; gap: 0.625rem; cursor: pointer;
            background: none; border: none; width: 100%;
        }
        .dropdown-item:hover { background-color: #f9fafb; color: #111827; }
        .dropdown-icon { width: 1rem; height: 1rem; color: #9ca3af; flex-shrink: 0; }
        /* Dark mode dropdown items */
        html.dark .dropdown-item { color: #d1d5db; }
        html.dark .dropdown-item:hover { background-color: #374151; color: #f9fafb; }
        html.dark .dropdown-icon { color: #6b7280; }
        .dropdown-badge {
            margin-left: auto; font-size: 0.7rem; font-weight: 700;
            padding: 0.1rem 0.5rem; border-radius: 9999px;
        }
        @keyframes dropdownIn {
            from { opacity: 0; transform: scale(0.95) translateY(-4px); }
            to   { opacity: 1; transform: scale(1)    translateY(0); }
        }

        /* ════════════════════════════════════════════════════════
           MOBILE DRAWER
        ════════════════════════════════════════════════════════ */

        /* Overlay */
        #mob-overlay {
            position: fixed; inset: 0; z-index: 60;
            background: rgba(0,0,0,0.45);
            opacity: 0; pointer-events: none;
            transition: opacity 0.25s ease;
        }
        #mob-overlay.open { opacity: 1; pointer-events: auto; }

        #mob-drawer {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: 82vw; max-width: 320px;
            background: #fff; z-index: 61;
            display: flex; flex-direction: column;
            transform: translateX(-100%);
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 24px rgba(0,0,0,0.12);
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }
        html.dark #mob-drawer { background: #1f2937; }
        #mob-drawer.open { transform: translateX(0); }

        .mob-header {
            display: flex; align-items: center; justify-content: flex-start;
            gap: 0.5rem;
            padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9;
            flex-shrink: 0;
        }
        html.dark .mob-header { border-bottom-color: #374151; }

        /* Drawer section label */
        .mob-section-label {
            font-size: 0.65rem; font-weight: 700; letter-spacing: 0.08em;
            text-transform: uppercase; color: #9ca3af;
            padding: 1rem 1.25rem 0.375rem;
        }

        /* Drawer nav link */
        .mob-link {
            display: flex; align-items: center; gap: 0.875rem;
            padding: 0.75rem 1.25rem;
            font-size: 0.9375rem; font-weight: 500; color: #374151;
            text-decoration: none; transition: background 0.12s, color 0.12s;
            border-radius: 0; position: relative;
        }
        .mob-link:hover { background: #f8fafc; color: #111827; }
        .mob-link.active {
            color: #4338ca; background: #eef2ff; font-weight: 600;
        }
        .mob-link.active .mob-link-icon { color: #4f46e5; }
        .mob-link-icon { width: 1.125rem; height: 1.125rem; color: #9ca3af; flex-shrink: 0; }
        /* Dark mode mob-link */
        html.dark .mob-link { color: #d1d5db; }
        html.dark .mob-link:hover { background: #374151; color: #f9fafb; }
        html.dark .mob-link.active { color: #a5b4fc; background: rgba(79, 70, 229, 0.15); }
        html.dark .mob-link.active .mob-link-icon { color: #818cf8; }
        html.dark .mob-link-icon { color: #6b7280; }
        .mob-badge {
            margin-left: auto;
            background: #ef4444; color: #fff;
            font-size: 0.65rem; font-weight: 700;
            padding: 0.1rem 0.45rem; border-radius: 9999px;
            min-width: 18px; text-align: center;
        }

        /* Services sub-list inside drawer — scrollable, no scrollbar shown */
        #mob-services-list {
            overflow: hidden;
            max-height: 0;
            transition: max-height 0.3s ease;
        }
        #mob-services-list.open {
            max-height: 260px;
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        #mob-services-list.open::-webkit-scrollbar { display: none; }
        .mob-service-link {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.625rem 1.25rem 0.625rem 3rem;
            font-size: 0.875rem; font-weight: 500; color: #6b7280;
            text-decoration: none; transition: background 0.12s, color 0.12s;
        }
        .mob-service-link:hover { background: #f8fafc; color: #4338ca; }
        html.dark .mob-service-link { color: #9ca3af; }
        html.dark .mob-service-link:hover { background: #374151; color: #a5b4fc; }
        .mob-service-dot {
            width: 0.375rem; height: 0.375rem;
            border-radius: 9999px; flex-shrink: 0;
        }

        /* Divider */
        .mob-divider { height: 1px; background: #f1f5f9; margin: 0.5rem 0; }
        html.dark .mob-divider { background: #374151; }

        /* Drawer footer (logout area) */
        .mob-footer {
            margin-top: auto; border-top: 1px solid #f1f5f9;
            padding: 0.75rem 0;
        }
        html.dark .mob-footer { border-top-color: #374151; }

        /* Guest buttons inside drawer */
        .mob-auth-area {
            padding: 1rem 1.25rem;
            display: flex; flex-direction: column; gap: 0.75rem;
        }

        /* Services chevron rotation */
        #mob-services-chevron {
            margin-left: auto;
            transition: transform 0.22s ease;
        }
        #mob-services-chevron.rotated { transform: rotate(180deg); }

        /* Hamburger button — display controlled by Tailwind (flex sm:hidden) */
        #mob-menu-btn {
            align-items: center; justify-content: center;
            width: 2.25rem; height: 2.25rem;
            border-radius: 0.5rem; color: #6b7280;
            transition: background 0.15s, color 0.15s;
            border: none; background: transparent; cursor: pointer;
            flex-shrink: 0;
        }
        #mob-menu-btn:hover { background: #f3f4f6; color: #111827; }

        /* Body scroll lock */
        body.drawer-open { overflow: hidden; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 flex flex-col min-h-screen text-gray-900 dark:text-gray-100 transition-colors duration-200">

    <!-- ── NAVBAR ── -->
    <?php require BASE_PATH . '/resources/views/layouts/navbar.php'; ?>

    <!-- ── MOBILE DRAWER ── -->
    <?php require BASE_PATH . '/resources/views/layouts/mobile-drawer.php'; ?>

    <!-- ── MAIN CONTENT ── -->
    <main class="flex-grow">
        <?php
            if (isset($contentView)) {
                require BASE_PATH . "/resources/views/{$contentView}.php";
            }
        ?>
    </main>

    <!-- ── FOOTER ── -->
    <?php if (empty($hideFooter)): ?>
        <?php if (!empty($minimalFooter)): ?>
            <?php require BASE_PATH . '/resources/views/layouts/footer-minimal.php'; ?>
        <?php else: ?>
            <?php require BASE_PATH . '/resources/views/layouts/footer.php'; ?>
        <?php endif; ?>
    <?php endif; ?>

    <script>
    // ── Desktop: Services dropdown ──────────────────────────────────────────
    (function() {
        const wrapper = document.getElementById('services-dropdown-wrapper');
        const menu    = document.getElementById('services-dropdown-menu');
        const chevron = document.getElementById('services-chevron');
        if (!wrapper || !menu) return;

        let closeTimer = null;

        function openServices() {
            clearTimeout(closeTimer);
            menu.classList.add('services-open');
            if (chevron) chevron.style.transform = 'rotate(180deg)';
        }
        function closeServices() {
            closeTimer = setTimeout(function() {
                menu.classList.remove('services-open');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }, 200);
        }

        wrapper.addEventListener('mouseenter', openServices);
        wrapper.addEventListener('mouseleave', closeServices);
        document.getElementById('services-dropdown-btn').addEventListener('click', function() {
            menu.classList.contains('services-open') ? closeServices() : openServices();
        });
    })();

    // ── Desktop: Notifications dropdown ─────────────────────────────────────
    function toggleNotifDropdown() {
        const menu   = document.getElementById('notif-dropdown-menu');
        const btn    = document.getElementById('notif-dropdown-btn');
        const isOpen = !menu.classList.contains('hidden');

        // Close profile dropdown if open to avoid overlap
        const profileMenu = document.getElementById('profile-dropdown-menu');
        if (profileMenu && !profileMenu.classList.contains('hidden')) {
            toggleProfileDropdown();
        }

        if (isOpen) {
            menu.classList.add('hidden');
            btn.classList.remove('bg-indigo-50', 'text-indigo-600', 'dark:bg-indigo-900/30', 'dark:text-indigo-400');
            btn.classList.add('hover:bg-indigo-50', 'hover:text-indigo-600', 'dark:hover:bg-gray-700', 'dark:hover:text-indigo-400');
        } else {
            menu.classList.remove('hidden');
            btn.classList.add('bg-indigo-50', 'text-indigo-600', 'dark:bg-indigo-900/30', 'dark:text-indigo-400');
            btn.classList.remove('hover:bg-indigo-50', 'hover:text-indigo-600', 'dark:hover:bg-gray-700', 'dark:hover:text-indigo-400');
            menu.style.animation = 'none';
            menu.offsetHeight;
            menu.style.animation = 'dropdownIn 0.15s ease-out';
        }
    }

    // ── Desktop: Profile dropdown ───────────────────────────────────────────
    function toggleProfileDropdown() {
        const menu    = document.getElementById('profile-dropdown-menu');
        const btn     = document.getElementById('profile-dropdown-btn');
        const chevron = document.getElementById('profile-dropdown-chevron');
        const isOpen  = !menu.classList.contains('hidden');

        if (isOpen) {
            menu.classList.add('hidden');
            btn.setAttribute('aria-expanded', 'false');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        } else {
            menu.classList.remove('hidden');
            btn.setAttribute('aria-expanded', 'true');
            if (chevron) chevron.style.transform = 'rotate(180deg)';
            menu.style.animation = 'none';
            menu.offsetHeight;
            menu.style.animation = 'dropdownIn 0.15s ease-out';
        }
    }

    // Close dropdowns on outside click
    document.addEventListener('click', function(e) {
        // Profile Dropdown
        const profWrapper = document.getElementById('profile-dropdown-wrapper');
        if (profWrapper && !profWrapper.contains(e.target)) {
            const menu    = document.getElementById('profile-dropdown-menu');
            const btn     = document.getElementById('profile-dropdown-btn');
            const chevron = document.getElementById('profile-dropdown-chevron');
            if (menu && !menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
                if (btn) btn.setAttribute('aria-expanded', 'false');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        }

        // Notification Dropdown
        const notifWrapper = document.getElementById('notif-dropdown-wrapper');
        if (notifWrapper && !notifWrapper.contains(e.target)) {
            const notifMenu = document.getElementById('notif-dropdown-menu');
            const notifBtn  = document.getElementById('notif-dropdown-btn');
            if (notifMenu && !notifMenu.classList.contains('hidden')) {
                notifMenu.classList.add('hidden');
                notifBtn.classList.remove('bg-indigo-50', 'text-indigo-600', 'dark:bg-indigo-900/30', 'dark:text-indigo-400');
                notifBtn.classList.add('hover:bg-indigo-50', 'hover:text-indigo-600', 'dark:hover:bg-gray-700', 'dark:hover:text-indigo-400');
            }
        }
    });

    // Close profile dropdown when clicking hash links inside it
    document.querySelectorAll('#profile-dropdown-menu a[href*="#"]').forEach(function(link) {
        link.addEventListener('click', function() {
            const menu    = document.getElementById('profile-dropdown-menu');
            const btn     = document.getElementById('profile-dropdown-btn');
            const chevron = document.getElementById('profile-dropdown-chevron');
            if (menu)    menu.classList.add('hidden');
            if (btn)     btn.setAttribute('aria-expanded', 'false');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        });
    });

    // ── Mobile drawer ───────────────────────────────────────────────────────
    function openDrawer() {
        document.getElementById('mob-drawer').classList.add('open');
        document.getElementById('mob-overlay').classList.add('open');
        document.body.classList.add('drawer-open');
    }

    function closeDrawer() {
        document.getElementById('mob-drawer').classList.remove('open');
        document.getElementById('mob-overlay').classList.remove('open');
        document.body.classList.remove('drawer-open');
    }

    // Fast close for navigation links — no animation so the drawer
    // doesn't stay visible mid-transition while the new page loads.
    function closeDrawerFast() {
        var drawer  = document.getElementById('mob-drawer');
        var overlay = document.getElementById('mob-overlay');
        // Kill the transition temporarily
        drawer.style.transition  = 'none';
        overlay.style.transition = 'none';
        // Hide instantly
        drawer.classList.remove('open');
        overlay.classList.remove('open');
        document.body.classList.remove('drawer-open');
        // Restore transition after a tick (for next open)
        requestAnimationFrame(function() {
            drawer.style.transition  = '';
            overlay.style.transition = '';
        });
    }

    // Services sub-list toggle inside drawer
    function toggleMobServices() {
        const list    = document.getElementById('mob-services-list');
        const chevron = document.getElementById('mob-services-chevron');
        const isOpen  = list.classList.contains('open');
        list.classList.toggle('open', !isOpen);
        chevron.classList.toggle('rotated', !isOpen);
    }

    // Close drawer on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDrawer();
    });

    // Swipe left to close drawer (native feel on touch devices)
    (function() {
        var drawer    = document.getElementById('mob-drawer');
        var startX    = 0;
        var startY    = 0;
        var dragging  = false;

        drawer.addEventListener('touchstart', function(e) {
            startX   = e.touches[0].clientX;
            startY   = e.touches[0].clientY;
            dragging = true;
        }, { passive: true });

        drawer.addEventListener('touchend', function(e) {
            if (!dragging) return;
            dragging = false;
            var dx = e.changedTouches[0].clientX - startX;
            var dy = Math.abs(e.changedTouches[0].clientY - startY);
            // Swipe left at least 60px, and more horizontal than vertical
            if (dx < -60 && dy < 80) {
                closeDrawer();
            }
        }, { passive: true });
    })();

    // ── Real-Time Polling Engine ────────────────────────────────────────────
    <?php if (isset($_SESSION['user_id'])): ?>
    (function() {
        const fetchPollData = async () => {
            try {
                const response = await fetch('<?= APP_URL ?>/api/poll');
                if (!response.ok) return;
                const data = await response.json();
                if (data.error) return;

                // 1. Update Global Nav Badges
                const msgBadge = document.getElementById('nav-unread-badge');
                if (msgBadge) {
                    if (data.unread_messages > 0) {
                        msgBadge.textContent = data.unread_messages > 99 ? '99+' : data.unread_messages;
                        msgBadge.classList.remove('hidden');
                    } else {
                        msgBadge.classList.add('hidden');
                    }
                }

                const notifBadge = document.getElementById('nav-notif-badge');
                if (notifBadge) {
                    if (data.unread_notifications > 0) {
                        notifBadge.textContent = data.unread_notifications > 99 ? '99+' : data.unread_notifications;
                        notifBadge.classList.remove('hidden');
                    } else {
                        notifBadge.classList.add('hidden');
                    }
                }

                // 2. Update Dashboard Tab Badges (if on dashboard)
                if (data.dashboard) {
                    // Homeowner mapping
                    const hwMappings = {
                        'tab-badge-pending-quotes': data.dashboard.pending_quotes,
                        'tab-badge-open-jobs': data.dashboard.open_jobs,
                        'tab-badge-active-bookings': data.dashboard.active_bookings,
                        'tab-badge-saved': data.dashboard.saved
                    };
                    
                    // Craftsman mapping
                    const crMappings = {
                        'tab-badge-pending-bids': data.dashboard.pending_bids,
                        'tab-badge-active-jobs': data.dashboard.active_jobs,
                        'tab-badge-pending-bookings': data.dashboard.pending_bookings,
                        'tab-badge-sent-bookings': data.dashboard.sent_bookings,
                        'tab-badge-saved': data.dashboard.saved
                    };

                    const mappings = data.role === 'homeowner' ? hwMappings : crMappings;
                    
                    for (const [id, value] of Object.entries(mappings)) {
                        const el = document.getElementById(id);
                        if (el) {
                            el.textContent = value;
                            if (value > 0) {
                                el.style.display = 'inline-flex';
                            } else {
                                el.style.display = 'none';
                            }
                        }
                    }
                }

                // 3. Dropdown Population
                const dropdownList = document.getElementById('notif-dropdown-list');
                if (dropdownList && data.recent_notifications) {
                    dropdownList.innerHTML = '';
                    if (data.recent_notifications.length === 0) {
                        dropdownList.innerHTML = '<div class="p-6 text-center text-sm text-gray-500 dark:text-gray-400">No new notifications</div>';
                    } else {
                        data.recent_notifications.forEach(notif => {
                            let iconSvg = '';
                            let bgColor = 'bg-gray-100 dark:bg-gray-700';
                            let iconColor = 'text-gray-500 dark:text-gray-400';
                            
                            switch(notif.type) {
                                case 'quote_new': 
                                    iconSvg = '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'; 
                                    bgColor = 'bg-yellow-100 dark:bg-yellow-900/40'; iconColor = 'text-yellow-600 dark:text-yellow-400'; break;
                                case 'quote_accepted':
                                case 'booking_accepted':
                                case 'counter_accepted':
                                case 'booking_completed':
                                    iconSvg = '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'; 
                                    bgColor = 'bg-green-100 dark:bg-green-900/40'; iconColor = 'text-green-600 dark:text-green-400'; break;
                                case 'booking_new': 
                                    iconSvg = '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>'; 
                                    bgColor = 'bg-indigo-100 dark:bg-indigo-900/40'; iconColor = 'text-indigo-600 dark:text-indigo-400'; break;
                                case 'booking_counter':
                                case 'booking_pending':
                                    iconSvg = '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'; 
                                    bgColor = 'bg-orange-100 dark:bg-orange-900/40'; iconColor = 'text-orange-600 dark:text-orange-400'; break;
                                case 'quote_rejected':
                                case 'booking_declined':
                                case 'counter_rejected':
                                    iconSvg = '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'; 
                                    bgColor = 'bg-red-100 dark:bg-red-900/40'; iconColor = 'text-red-600 dark:text-red-400'; break;
                                case 'review_new':
                                    iconSvg = '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>';
                                    bgColor = 'bg-yellow-100 dark:bg-yellow-900/40'; iconColor = 'text-yellow-600 dark:text-yellow-400'; break;
                                default:
                                    iconSvg = '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
                                    bgColor = 'bg-gray-100 dark:bg-gray-700'; iconColor = 'text-gray-500 dark:text-gray-400'; break;
                            }

                            const isUnread = notif.is_read == 0;
                            const unreadDot = isUnread ? `<span class="h-2 w-2 bg-indigo-500 dark:bg-indigo-400 rounded-full flex-shrink-0 mt-1.5"></span>` : ``;

                            const safeLink = notif.link ? notif.link : '/notifications';
                            dropdownList.innerHTML += `
                                <a href="<?= APP_URL ?>/notifications/read?id=${notif.id}&redirect=${encodeURIComponent(safeLink)}" class="flex items-start gap-3 p-3 hover:bg-gray-50/80 dark:hover:bg-gray-700/60 transition-colors border-b border-gray-100/50 dark:border-gray-700/50 last:border-0 ${isUnread ? 'bg-indigo-50/30 dark:bg-indigo-900/20' : ''}">
                                    <div class="h-8 w-8 rounded-full ${bgColor} flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <div class="${iconColor}">${iconSvg}</div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-800 dark:text-gray-100 ${isUnread ? 'font-semibold' : 'font-medium'} leading-snug break-words">${notif.message.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</p>
                                        <span class="text-[10px] text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider block mt-1">${notif.time_ago}</span>
                                    </div>
                                    ${unreadDot}
                                </a>
                            `;
                        });
                    }
                }

            } catch(e) { console.error('Polling failed:', e); }
        };

        // Poll immediately, then every 15 seconds
        fetchPollData();
        setInterval(fetchPollData, 15000);
    })();
    <?php endif; ?>
    </script>
</body>
</html>