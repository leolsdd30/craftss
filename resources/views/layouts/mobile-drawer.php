<?php
/**
 * MOBILE DRAWER PARTIAL
 * Extracted from layouts/app.php for maintainability.
 * All variables from app.php (and navbar.php) are available here via PHP's require scope.
 * Requires: $isHome, $isSearch, $isJobs, $isDashboard, $headerPicUrl,
 *           $headerTotalBadge, $headerNotifCount (set in navbar.php)
 */
?>
    <div id="mob-overlay" onclick="closeDrawer()"></div>

    <div id="mob-drawer" role="dialog" aria-modal="true" aria-label="Navigation menu">

        <!-- Drawer header — X on the left (same muscle memory as hamburger),
             Crafts logo+name to the right of it -->
        <div class="mob-header">
            <button onclick="closeDrawer()" class="p-2 -ml-1 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-200 transition flex-shrink-0" aria-label="Close menu">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <a href="<?= APP_URL ?>/" class="flex items-center gap-2" onclick="closeDrawerFast()">
                <div class="h-7 w-7 bg-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                    </svg>
                </div>
                <span class="text-base font-extrabold text-gray-900 dark:text-white">Crafts</span>
            </a>
        </div>

        <!-- ── Logged-in user: avatar strip ──────────────────── -->
        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="flex items-center gap-3 px-5 py-4 bg-gray-50 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
            <?php if (!empty($headerPicUrl)): ?>
            <img src="<?= $headerPicUrl ?>" alt="Profile" class="w-11 h-11 rounded-full object-cover border-2 border-white dark:border-gray-600 shadow-sm flex-shrink-0">
            <?php endif; ?>
            <div class="min-w-0">
                <p class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate flex items-center gap-1">
                    <?= htmlspecialchars($_SESSION['name']) ?>
                    <?php if (!empty($_SESSION['is_verified'])): ?>
                    <svg class="h-3.5 w-3.5 text-blue-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <?php endif; ?>
                </p>
                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium <?= $_SESSION['role'] === 'craftsman' ? 'bg-indigo-100 text-indigo-700' : ($_SESSION['role'] === 'admin' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700') ?> capitalize">
                    <?= htmlspecialchars($_SESSION['role']) ?>
                </span>
            </div>
        </div>
        <?php endif; ?>

        <!-- ── Explore section ───────────────────────────────── -->
        <div class="mob-section-label">Explore</div>

        <a href="<?= APP_URL ?>/" class="mob-link <?= $isHome ? 'active' : '' ?>" onclick="closeDrawerFast()">
            <svg class="mob-link-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Home
        </a>

        <a href="<?= APP_URL ?>/search" class="mob-link <?= $isSearch ? 'active' : '' ?>" onclick="closeDrawerFast()">
            <svg class="mob-link-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Find Craftsmen
        </a>

        <a href="<?= APP_URL ?>/jobs" class="mob-link <?= $isJobs ? 'active' : '' ?>" onclick="closeDrawerFast()">
            <svg class="mob-link-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Job Board
        </a>

        <!-- Services sub-menu toggle -->
        <button onclick="toggleMobServices()" class="mob-link w-full text-left">
            <svg class="mob-link-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            Services
            <svg id="mob-services-chevron" class="mob-link-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <!-- Services list -->
        <div id="mob-services-list">
            <?php
            $mobServices = [
                ['Plumbing',          'bg-blue-400'],
                ['Electrical',        'bg-yellow-400'],
                ['Carpentry',         'bg-orange-400'],
                ['Painting',          'bg-pink-400'],
                ['Roofing',           'bg-stone-400'],
                ['HVAC',              'bg-cyan-400'],
                ['Landscaping',       'bg-green-400'],
                ['Tiling',            'bg-purple-400'],
                ['General Handyman',  'bg-indigo-400'],
            ];
            foreach ($mobServices as [$name, $dot]):
            ?>
            <a href="<?= APP_URL ?>/search?category=<?= urlencode($name) ?>" class="mob-service-link" onclick="closeDrawerFast()">
                <span class="mob-service-dot <?= $dot ?>"></span>
                <?= $name ?>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- ── Dark mode toggle ──────────────────────────────── -->
        <div class="mob-divider"></div>
        <button type="button" onclick="event.preventDefault(); toggleDarkMode();" class="mob-link w-full text-left">
            <!-- Sun icon (light mode) -->
            <svg class="mob-link-icon block dark:hidden text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <!-- Moon icon (dark mode) -->
            <svg class="mob-link-icon hidden dark:block text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
            <span>Dark Mode</span>
            
            <!-- Sliding Toggle UI (pushes right) -->
            <div class="ml-auto relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none bg-gray-200 dark:bg-indigo-600 shadow-inner">
                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out translate-x-0 dark:translate-x-5"></span>
            </div>
        </button>

        <!-- ── Dashboard link (logged-in only) — below services, above footer -->
        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="mob-divider"></div>
        <?php
            if ($_SESSION['role'] === 'admin') {
                $mobDashUrl   = APP_URL . '/admin/dashboard';
                $mobDashLabel = 'Admin Dashboard';
            } elseif ($_SESSION['role'] === 'craftsman') {
                $mobDashUrl   = APP_URL . '/craftsman/dashboard';
                $mobDashLabel = 'My Dashboard';
            } else {
                $mobDashUrl   = APP_URL . '/homeowner/dashboard';
                $mobDashLabel = 'My Dashboard';
            }
        ?>
        <a href="<?= $mobDashUrl ?>" class="mob-link <?= $isDashboard ? 'active' : '' ?>" onclick="closeDrawerFast()">
            <svg class="mob-link-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <?= $mobDashLabel ?>
        </a>
        <?php endif; ?>

        <!-- Footer: logout (logged in) OR login/signup (guest) -->
        <div class="mob-footer">
            <?php if (isset($_SESSION['user_id'])): ?>
            <form method="POST" action="<?= APP_URL ?>/logout">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <button type="submit" class="mob-link w-full text-left" style="color:#dc2626;">
                    <svg class="mob-link-icon" style="color:#f87171;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Log Out
                </button>
            </form>
            <?php else: ?>
            <div class="mob-auth-area">
                <a href="<?= APP_URL ?>/login"
                   class="flex items-center justify-center px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                   onclick="closeDrawerFast()">
                    Log in
                </a>
                <a href="<?= APP_URL ?>/register"
                   class="flex items-center justify-center px-4 py-3 bg-indigo-600 hover:bg-indigo-700 rounded-xl text-sm font-semibold text-white transition"
                   onclick="closeDrawerFast()">
                    Sign up — it's free
                </a>
            </div>
            <?php endif; ?>
        </div>

    </div><!-- /mob-drawer -->
