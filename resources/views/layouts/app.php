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

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }

        /* ── Scrollbar ─────────────────────────────────────────── */
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background-color: #818cf8; }
        * { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }

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

        /* ── Services dropdown ─────────────────────────────────── */
        .services-item {
            display: flex; flex-direction: column; align-items: center;
            padding: 0.625rem 0.5rem; border-radius: 0.75rem;
            text-decoration: none; transition: background-color 0.15s; gap: 0.375rem;
        }
        .services-item:hover { background-color: #f9fafb; }
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

        /* ── Profile dropdown ──────────────────────────────────── */
        .dropdown-item {
            display: flex; align-items: center; padding: 0.5rem 1rem;
            font-size: 0.875rem; color: #374151; transition: background-color 0.15s;
            text-decoration: none; gap: 0.625rem; cursor: pointer;
            background: none; border: none; width: 100%;
        }
        .dropdown-item:hover { background-color: #f9fafb; color: #111827; }
        .dropdown-icon { width: 1rem; height: 1rem; color: #9ca3af; flex-shrink: 0; }
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

        /* Drawer panel */
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
        #mob-drawer.open { transform: translateX(0); }

        /* Drawer header */
        .mob-header {
            display: flex; align-items: center; justify-content: flex-start;
            gap: 0.5rem;
            padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9;
            flex-shrink: 0;
        }

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
        .mob-service-dot {
            width: 0.375rem; height: 0.375rem;
            border-radius: 9999px; flex-shrink: 0;
        }

        /* Divider */
        .mob-divider { height: 1px; background: #f1f5f9; margin: 0.5rem 0; }

        /* Drawer footer (logout area) */
        .mob-footer {
            margin-top: auto; border-top: 1px solid #f1f5f9;
            padding: 0.75rem 0;
        }

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
<body class="bg-gray-50 flex flex-col min-h-screen text-gray-900">

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

    // Close profile dropdown on outside click
    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('profile-dropdown-wrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            const menu    = document.getElementById('profile-dropdown-menu');
            const btn     = document.getElementById('profile-dropdown-btn');
            const chevron = document.getElementById('profile-dropdown-chevron');
            if (menu)    menu.classList.add('hidden');
            if (btn)     btn.setAttribute('aria-expanded', 'false');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
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
    </script>
</body>
</html>