<?php
/**
 * NAVBAR PARTIAL
 * Extracted from layouts/app.php for maintainability.
 * All variables from app.php are available here via PHP's require scope.
 */

    $appUrlPath = rtrim(parse_url(APP_URL, PHP_URL_PATH) ?? '', '/');
    $currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';

    $route = $currentUri;
    if ($appUrlPath !== '' && strpos($currentUri, $appUrlPath) === 0) {
        $route = substr($currentUri, strlen($appUrlPath));
    }
    if ($route === '') $route = '/';

    $isHome      = ($route === '/');
    $isSearch    = strpos($route, '/search') === 0;
    $isJobs      = strpos($route, '/jobs') === 0;
    $isDashboard = strpos($route, '/homeowner/dashboard') === 0
                || strpos($route, '/craftsman/dashboard') === 0
                || strpos($route, '/admin/dashboard') === 0;
    $isMessages  = strpos($route, '/messages') === 0;
    $isNotifs    = strpos($route, '/notifications') === 0;

    $baseIconClass     = "relative p-2 transition-colors duration-200 rounded-lg";
    $activeIconClass   = "text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30";
    $inactiveIconClass = "text-gray-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-gray-700";
?>
    <nav class="bg-white dark:bg-gray-800 shadow dark:shadow-gray-900/50 sticky top-0 z-40 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative flex items-center h-16">

                <!-- ── MOBILE: Hamburger — left of logo, mobile only ── -->
                <button id="mob-menu-btn" class="flex sm:hidden mr-3 text-gray-600 dark:text-gray-300" onclick="openDrawer()" aria-label="Open menu">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- ── LEFT: Logo ───────────────────────────────────── -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="<?= APP_URL ?>/" class="flex items-center space-x-2.5 group">
                        <div class="h-8 w-8 bg-indigo-600 rounded-lg flex items-center justify-center shadow-sm group-hover:bg-indigo-700 transition-colors duration-200">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                            </svg>
                        </div>
                        <span class="text-xl font-extrabold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200">Crafts</span>
                    </a>
                </div>

                <!-- ── CENTER: Desktop nav links ───────────────────── -->
                <div class="hidden sm:flex items-center space-x-1 ml-10">

                    <a href="<?= APP_URL ?>/" class="nav-pill <?= $isHome ? 'nav-pill-active' : 'nav-pill-inactive' ?>">Home</a>

                    <!-- Find Craftsmen — plain link to search -->
                    <a href="<?= APP_URL ?>/search"
                       class="nav-pill <?= $isSearch ? 'nav-pill-active' : 'nav-pill-inactive' ?>">
                        Find Craftsmen
                    </a>

                    <!-- Services dropdown -->
                    <div class="relative" id="services-dropdown-wrapper">
                        <button id="services-dropdown-btn"
                                class="nav-pill nav-pill-inactive flex items-center gap-1">
                            Services
                            <svg id="services-chevron" class="h-3.5 w-3.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="services-dropdown-menu"
                             class="absolute left-0 mt-2 w-[480px] bg-white dark:bg-gray-800 rounded-2xl shadow-xl dark:shadow-gray-900/60 border border-gray-100 dark:border-gray-700 p-4 z-50">
                            <div class="grid grid-cols-5 gap-1">
                                <a href="<?= APP_URL ?>/search?category=Plumbing" class="services-item group">
                                    <div class="services-icon bg-blue-100 text-blue-600 group-hover:bg-blue-200">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="services-label text-blue-700">Plumbing</span>
                                </a>
                                <a href="<?= APP_URL ?>/search?category=Electrical" class="services-item group">
                                    <div class="services-icon bg-yellow-100 text-yellow-600 group-hover:bg-yellow-200">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    </div>
                                    <span class="services-label text-yellow-700">Electrical</span>
                                </a>
                                <a href="<?= APP_URL ?>/search?category=Carpentry" class="services-item group">
                                    <div class="services-icon bg-orange-100 text-orange-600 group-hover:bg-orange-200">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                                    </div>
                                    <span class="services-label text-orange-700">Carpentry</span>
                                </a>
                                <a href="<?= APP_URL ?>/search?category=Painting" class="services-item group">
                                    <div class="services-icon bg-pink-100 text-pink-600 group-hover:bg-pink-200">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                                    </div>
                                    <span class="services-label text-pink-700">Painting</span>
                                </a>
                                <a href="<?= APP_URL ?>/search?category=Roofing" class="services-item group">
                                    <div class="services-icon bg-stone-100 text-stone-600 group-hover:bg-stone-200">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    </div>
                                    <span class="services-label text-stone-700">Roofing</span>
                                </a>
                                <a href="<?= APP_URL ?>/search?category=HVAC" class="services-item group">
                                    <div class="services-icon bg-cyan-100 text-cyan-600 group-hover:bg-cyan-200">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <span class="services-label text-cyan-700">HVAC</span>
                                </a>
                                <a href="<?= APP_URL ?>/search?category=Landscaping" class="services-item group">
                                    <div class="services-icon bg-green-100 text-green-600 group-hover:bg-green-200">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                    </div>
                                    <span class="services-label text-green-700">Landscaping</span>
                                </a>
                                <a href="<?= APP_URL ?>/search?category=Tiling" class="services-item group">
                                    <div class="services-icon bg-purple-100 text-purple-600 group-hover:bg-purple-200">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                                    </div>
                                    <span class="services-label text-purple-700">Tiling</span>
                                </a>
                                <a href="<?= APP_URL ?>/search?category=General+Handyman" class="services-item group">
                                    <div class="services-icon bg-indigo-100 text-indigo-600 group-hover:bg-indigo-200">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <span class="services-label text-indigo-700">Handyman</span>
                                </a>
                            </div>
                            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-center">
                                <a href="<?= APP_URL ?>/search" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors">
                                    View all craftsmen →
                                </a>
                            </div>
                        </div>
                    </div>

                    <a href="<?= APP_URL ?>/jobs" class="nav-pill <?= $isJobs ? 'nav-pill-active' : 'nav-pill-inactive' ?>">Job Board</a>
                </div>

                <!-- ── RIGHT: Desktop icons + profile / guest buttons ─ -->
                <div class="ml-auto flex items-center gap-1">

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php
                            if ($_SESSION['role'] === 'admin') {
                                $dashboardUrl = APP_URL . '/admin/dashboard';
                            } elseif ($_SESSION['role'] === 'craftsman') {
                                $dashboardUrl = APP_URL . '/craftsman/dashboard';
                            } else {
                                $dashboardUrl = APP_URL . '/homeowner/dashboard';
                            }
                            $headerUser    = clone (new \App\Models\User());
                            $headerDbUser  = $headerUser->findById($_SESSION['user_id']);
                            $headerPicUrl  = $headerDbUser ? get_profile_picture_url($headerDbUser['profile_picture'] ?? 'default.png', $headerDbUser['first_name'], $headerDbUser['last_name']) : '';

                            $headerMsgModel    = new \App\Models\Message();
                            $headerUnreadCount = $headerMsgModel->getUnreadConversationCount($_SESSION['user_id']);
                            $headerRequestCount = $headerMsgModel->getPendingRequestCount($_SESSION['user_id']);
                            $headerTotalBadge  = $headerUnreadCount;

                            $headerNotifModel  = new \App\Models\Notification();
                            $headerNotifCount  = $headerNotifModel->getUnreadCount($_SESSION['user_id']);
                        ?>

                        <!-- Dashboard link — desktop only -->
                        <?php if ($_SESSION['role'] !== 'admin'): ?>
                        <a href="<?= $dashboardUrl ?>" class="hidden sm:inline-flex nav-pill <?= $isDashboard ? 'nav-pill-active' : 'nav-pill-inactive' ?>">
                            Dashboard
                        </a>
                        <?php else: ?>
                        <a href="<?= APP_URL ?>/admin/dashboard" class="hidden sm:inline-flex nav-pill flex items-center gap-1 <?= $isDashboard ? 'nav-pill-active' : 'nav-pill-inactive' ?>">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Admin
                        </a>
                        <?php endif; ?>

                        <span class="hidden sm:block text-gray-200 dark:text-gray-600 text-lg font-light px-1">|</span>

                        <!-- Dark Mode Toggle -->
                        <button onclick="toggleDarkMode()" class="hidden sm:block relative p-2 transition-colors duration-200 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-gray-700 dark:hover:text-indigo-400" title="Toggle Dark Mode" id="dark-mode-toggle">
                            <!-- Sun icon (visible in LIGHT mode — you are in light theme) -->
                            <svg class="h-5 w-5 block dark:hidden text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <!-- Moon icon (visible in DARK mode — you are in dark theme) -->
                            <svg class="h-5 w-5 hidden dark:block text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>

                        <!-- Messages icon -->
                        <a href="<?= APP_URL ?>/messages" class="<?= $baseIconClass . ' ' . ($isMessages ? $activeIconClass : $inactiveIconClass) ?>" title="Messages">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <span id="nav-unread-badge" class="<?= $headerTotalBadge > 0 ? '' : 'hidden ' ?>absolute -top-0.5 -right-0.5 inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-red-500 text-white min-w-[18px] leading-none ring-2 ring-white dark:ring-gray-800">
                                <?= $headerTotalBadge > 99 ? '99+' : $headerTotalBadge ?>
                            </span>
                        </a>

                        <!-- Notifications Dropdown -->
                        <div class="relative" id="notif-dropdown-wrapper">
                            <button id="notif-dropdown-btn" onclick="toggleNotifDropdown()" class="<?= $baseIconClass . ' ' . ($isNotifs ? $activeIconClass : $inactiveIconClass) ?>" title="Notifications">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span id="nav-notif-badge" class="<?= $headerNotifCount > 0 ? '' : 'hidden ' ?>absolute -top-0.5 -right-0.5 inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-red-500 text-white min-w-[18px] leading-none ring-2 ring-white dark:ring-gray-800">
                                    <?= $headerNotifCount > 99 ? '99+' : $headerNotifCount ?>
                                </span>
                            </button>
                            
                            <!-- Dropdown panel -->
                            <div id="notif-dropdown-menu" class="hidden fixed left-2 right-2 top-[68px] sm:absolute sm:left-auto sm:top-auto sm:right-0 sm:mt-2 sm:w-96 bg-white/95 dark:bg-gray-800/95 backdrop-blur-xl rounded-2xl shadow-2xl dark:shadow-gray-900/60 border border-gray-100/50 dark:border-gray-700/50 py-1 z-[100] origin-top sm:origin-top-right overflow-hidden" style="animation: dropdownIn 0.15s ease-out;">
                                <div class="px-4 py-3 border-b border-gray-100/50 dark:border-gray-700/50 flex justify-between items-center bg-gray-50/30 dark:bg-gray-700/30">
                                    <h3 class="font-bold text-gray-900 dark:text-gray-100">Notifications</h3>
                                    <?php if ($headerNotifCount > 0): ?>
                                    <form method="POST" action="<?= APP_URL ?>/notifications/mark-all-read" class="m-0 p-0 inline">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <button type="submit" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors whitespace-nowrap">Mark all read</button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                                
                                <div id="notif-dropdown-list" class="max-h-[350px] overflow-y-auto w-full">
                                    <!-- Populated by JS -->
                                    <div class="p-8 flex flex-col items-center justify-center text-gray-400">
                                        <svg class="h-8 w-8 mb-2 animate-spin text-indigo-200" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        <span class="text-sm">Fetching...</span>
                                    </div>
                                </div>
                                
                                <div class="border-t border-gray-100/50 dark:border-gray-700/50 p-1.5 bg-gray-50/50 dark:bg-gray-700/50 backdrop-blur-md">
                                    <a href="<?= APP_URL ?>/notifications" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors uppercase tracking-wider block w-full py-2 text-center rounded-xl hover:bg-white dark:hover:bg-gray-700">View All Activity</a>
                                </div>
                            </div>
                        </div>

                        <!-- Profile dropdown -->
                        <div class="relative" id="profile-dropdown-wrapper">
                            <button id="profile-dropdown-btn" onclick="toggleProfileDropdown()"
                                class="flex items-center gap-2 hover:bg-gray-50 dark:hover:bg-gray-700 p-1.5 pr-2 rounded-full transition-colors duration-200 border border-transparent hover:border-gray-200 dark:hover:border-gray-600 focus:outline-none"
                                aria-haspopup="true" aria-expanded="false">
                                <?php if ($headerPicUrl): ?>
                                <img src="<?= $headerPicUrl ?>" alt="Profile" class="w-8 h-8 rounded-full object-cover border border-gray-200 dark:border-gray-600 flex-shrink-0">
                                <?php endif; ?>
                                <!-- Name + role badge: hidden on small screens to save space -->
                                <span class="hidden sm:flex items-center gap-1 text-sm font-semibold text-gray-700 dark:text-gray-200">
                                    <?= htmlspecialchars($_SESSION['name']) ?>
                                    <?php if (!empty($_SESSION['is_verified'])): ?>
                                    <svg class="h-4 w-4 text-blue-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <?php endif; ?>
                                </span>
                                <span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium <?= $_SESSION['role'] === 'craftsman' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300' : ($_SESSION['role'] === 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300' : 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300') ?> capitalize">
                                    <?= htmlspecialchars($_SESSION['role']) ?>
                                </span>
                                <svg id="profile-dropdown-chevron" class="h-4 w-4 text-gray-400 dark:text-gray-500 transition-transform duration-200 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div id="profile-dropdown-menu"
                                 class="hidden absolute right-0 mt-2 w-60 bg-white dark:bg-gray-800 rounded-xl shadow-lg dark:shadow-gray-900/60 border border-gray-100 dark:border-gray-700 py-1.5 z-50 origin-top-right"
                                 style="animation: dropdownIn 0.15s ease-out; max-width: calc(100vw - 1rem);">
                                <!-- Header -->
                                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center space-x-3">
                                        <?php if ($headerPicUrl): ?>
                                        <img src="<?= $headerPicUrl ?>" alt="Profile" class="w-10 h-10 rounded-full object-cover border border-gray-200 dark:border-gray-600 flex-shrink-0">
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
                                            <p class="text-xs text-gray-400 dark:text-gray-500 truncate capitalize"><?= htmlspecialchars($_SESSION['role']) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Section 1: Common -->
                                <div class="py-1.5 border-b border-gray-100 dark:border-gray-700">
                                    <a href="<?= APP_URL ?>/profile/<?= htmlspecialchars($_SESSION['username'] ?? '') ?>" class="dropdown-item">
                                        <svg class="dropdown-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        View Profile
                                    </a>
                                    <a href="<?= APP_URL ?>/messages" class="dropdown-item">
                                        <svg class="dropdown-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        Messages
                                        <?php if ($headerTotalBadge > 0): ?>
                                        <span class="dropdown-badge bg-indigo-100 text-indigo-700 dark:bg-indigo-500 dark:text-white"><?= $headerTotalBadge > 99 ? '99+' : $headerTotalBadge ?></span>
                                        <?php endif; ?>
                                    </a>
                                    <a href="<?= APP_URL ?>/notifications" class="dropdown-item">
                                        <svg class="dropdown-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                        Notifications
                                        <?php if ($headerNotifCount > 0): ?>
                                        <span class="dropdown-badge bg-red-100 text-red-600 dark:bg-red-500 dark:text-white"><?= $headerNotifCount > 99 ? '99+' : $headerNotifCount ?></span>
                                        <?php endif; ?>
                                    </a>
                                </div>
                                <!-- Section 2: Role links -->
                                <div class="py-1.5 border-b border-gray-100 dark:border-gray-700">
                                    <?php if ($_SESSION['role'] === 'homeowner'): ?>
                                        <a href="<?= APP_URL ?>/homeowner/dashboard#bookings" class="dropdown-item">
                                            <svg class="dropdown-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            My Bookings
                                        </a>
                                        <a href="<?= APP_URL ?>/homeowner/dashboard#jobs" class="dropdown-item">
                                            <svg class="dropdown-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            My Jobs
                                        </a>
                                        <a href="<?= APP_URL ?>/homeowner/dashboard#favorites" class="dropdown-item">
                                            <svg class="dropdown-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                            Saved Craftsmen
                                        </a>
                                        <a href="<?= APP_URL ?>/profile/edit" class="dropdown-item">
                                            <svg class="dropdown-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit Profile
                                        </a>
                                    <?php elseif ($_SESSION['role'] === 'craftsman'): ?>
                                        <a href="<?= APP_URL ?>/craftsman/dashboard#bookings" class="dropdown-item">
                                            <svg class="dropdown-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            My Bookings
                                        </a>
                                        <a href="<?= APP_URL ?>/craftsman/dashboard#quotes" class="dropdown-item">
                                            <svg class="dropdown-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                            My Quotes
                                        </a>
                                        <a href="<?= APP_URL ?>/profile/edit" class="dropdown-item">
                                            <svg class="dropdown-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit Profile
                                        </a>
                                    <?php elseif ($_SESSION['role'] === 'admin'): ?>
                                        <a href="<?= APP_URL ?>/admin/dashboard" class="dropdown-item">
                                            <svg class="dropdown-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                                            Dashboard
                                        </a>
                                        <a href="<?= APP_URL ?>/admin/users" class="dropdown-item">
                                            <svg class="dropdown-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                            User Management
                                        </a>
                                        <a href="<?= APP_URL ?>/admin/verifications" class="dropdown-item">
                                            <svg class="dropdown-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                            Verifications
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <!-- Section 3: Logout -->
                                <div class="py-1.5">
                                    <form method="POST" action="<?= APP_URL ?>/logout">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <button type="submit" class="dropdown-item w-full text-left text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-700 dark:hover:text-red-300">
                                            <svg class="dropdown-icon text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Log Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>



                    <?php else: ?>
                        <!-- Dark Mode Toggle (Guest) -->
                        <button onclick="toggleDarkMode()" class="hidden sm:block relative p-2 transition-colors duration-200 rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-gray-700 dark:hover:text-indigo-400" title="Toggle Dark Mode" id="guest-dark-mode-toggle">
                            <!-- Sun icon -->
                            <svg class="h-5 w-5 block dark:hidden text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <!-- Moon icon -->
                            <svg class="h-5 w-5 hidden dark:block text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>
                        <span class="hidden sm:block text-gray-200 dark:text-gray-600 text-lg font-light px-1">|</span>

                        <!-- Guest desktop: Log in pill + Sign up button -->
                        <a href="<?= APP_URL ?>/login" class="hidden sm:inline-flex nav-pill nav-pill-inactive">Log in</a>
                        <a href="<?= APP_URL ?>/register" class="hidden sm:inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200">Sign up</a>

                        <!-- Guest mobile: login button matching drawer style -->
                        <a href="<?= APP_URL ?>/login"
                           class="flex sm:hidden items-center justify-center px-4 py-2 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200">
                            Log in
                        </a>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </nav>
