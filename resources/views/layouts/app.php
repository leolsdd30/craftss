<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'CraftConnect' ?></title>
    
    <!-- SEO and Open Graph Meta Tags -->
    <?php
        $defaultDesc = 'CraftConnect - The premier platform connecting skilled craftsmen and homeowners in Algeria.';
        $defaultImg = APP_URL . '/assets/img/og-preview.jpg'; // We can add a generic preview later
        
        $desc = $metaDescription ?? $ogDescription ?? $defaultDesc;
        $title = $ogTitle ?? $pageTitle ?? 'CraftConnect';
        $img = $ogImage ?? $defaultImg;
    ?>
    <meta name="description" content="<?= htmlspecialchars($desc) ?>">
    
    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= APP_URL . $_SERVER['REQUEST_URI'] ?>">
    <meta property="og:title" content="<?= htmlspecialchars($title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($desc) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($img) ?>">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= APP_URL . $_SERVER['REQUEST_URI'] ?>">
    <meta property="twitter:title" content="<?= htmlspecialchars($title) ?>">
    <meta property="twitter:description" content="<?= htmlspecialchars($desc) ?>">
    <meta property="twitter:image" content="<?= htmlspecialchars($img) ?>">
    <!-- Use Tailwind via CDN for prototyping -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Scrollbar Styling - Sleek & Thin */
        ::-webkit-scrollbar {
            width: 4px; /* Thin width */
            height: 4px; /* Thin height for horizontal scrollbars */
        }
        ::-webkit-scrollbar-track {
            background: transparent; /* No unsightly track background */
        }
        ::-webkit-scrollbar-thumb {
            background-color: #cbd5e1; /* Very subtle slate-300 by default */
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background-color: #818cf8; /* indigo-400 on hover for interaction */
        }
        
        /* Firefox Support */
        * {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen text-gray-900">
    <!-- Navbar -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="<?= APP_URL ?>/" class="text-2xl font-bold text-indigo-600">CraftConnect</a>
                    </div>
                    <!-- Main Navigation Links -->
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="<?= APP_URL ?>/" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Home
                        </a>
                        <a href="<?= APP_URL ?>/search" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Find Craftsmen
                        </a>
                        <a href="<?= APP_URL ?>/jobs" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Job Board
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php 
                            if ($_SESSION['role'] === 'admin') {
                                $dashboardUrl = APP_URL . '/admin/dashboard';
                            } elseif ($_SESSION['role'] === 'craftsman') {
                                $dashboardUrl = APP_URL . '/craftsman/dashboard';
                            } else {
                                $dashboardUrl = APP_URL . '/homeowner/dashboard';
                            }
                                
                            // Quick DB lookup to ensure we have the freshest profile picture
                            $headerUser = clone (new \App\Models\User());
                            $headerDbUser = $headerUser->findById($_SESSION['user_id']);
                            $headerPicUrl = $headerDbUser ? get_profile_picture_url($headerDbUser['profile_picture'] ?? 'default.png', $headerDbUser['first_name'], $headerDbUser['last_name']) : '';

                            // Get unread message count (conversations only, not total messages)
                            $headerMsgModel = new \App\Models\Message();
                            $headerUnreadCount = $headerMsgModel->getUnreadConversationCount($_SESSION['user_id']);
                            $headerRequestCount = $headerMsgModel->getPendingRequestCount($_SESSION['user_id']);
                            $headerTotalBadge = $headerUnreadCount + $headerRequestCount;

                            // Get unread notification count
                            $headerNotifModel = new \App\Models\Notification();
                            $headerNotifCount = $headerNotifModel->getUnreadCount($_SESSION['user_id']);
                        ?>
                        <?php if ($_SESSION['role'] !== 'admin'): ?>
                        <a href="<?= $dashboardUrl ?>" class="text-sm font-medium text-gray-500 hover:text-gray-900 border-b-2 border-transparent hover:border-indigo-600 transition-colors duration-200">
                            Dashboard
                        </a>
                        <?php endif; ?>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                        <a href="<?= APP_URL ?>/admin/dashboard" class="text-sm font-medium text-red-600 hover:text-red-800 border-b-2 border-transparent hover:border-red-500 transition-colors duration-200 flex items-center">
                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Admin
                        </a>
                        <?php endif; ?>
                        <span class="text-sm text-gray-300">|</span>
                        <!-- Messages Icon -->
                        <a href="<?= APP_URL ?>/messages" class="relative p-2 text-gray-400 hover:text-indigo-600 transition-colors duration-200 rounded-lg hover:bg-indigo-50" title="Messages">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <?php if ($headerTotalBadge > 0): ?>
                            <span id="nav-unread-badge" class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-bold <?= $headerRequestCount > 0 ? 'bg-yellow-500' : 'bg-red-500' ?> text-white min-w-[18px] leading-none ring-2 ring-white">
                                <?= $headerTotalBadge > 99 ? '99+' : $headerTotalBadge ?>
                            </span>
                            <?php endif; ?>
                        </a>
                        <!-- Notifications Bell -->
                        <a href="<?= APP_URL ?>/notifications" class="relative p-2 text-gray-400 hover:text-indigo-600 transition-colors duration-200 rounded-lg hover:bg-indigo-50" title="Notifications">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <?php if ($headerNotifCount > 0): ?>
                            <span id="nav-notif-badge" class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-red-500 text-white min-w-[18px] leading-none ring-2 ring-white">
                                <?= $headerNotifCount > 99 ? '99+' : $headerNotifCount ?>
                            </span>
                            <?php endif; ?>
                        </a>
                        <a href="<?= APP_URL ?>/profile" class="flex items-center space-x-2 hover:bg-gray-50 p-1.5 pr-3 rounded-full transition-colors duration-200 border border-transparent hover:border-gray-200" title="View Profile">
                            <?php if ($headerPicUrl): ?>
                                <img src="<?= $headerPicUrl ?>" alt="Profile" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                            <?php endif; ?>
                            <span class="text-sm font-semibold text-gray-700"><?= htmlspecialchars($_SESSION['name']) ?></span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $_SESSION['role'] === 'craftsman' ? 'bg-indigo-100 text-indigo-800' : 'bg-green-100 text-green-800' ?> capitalize">
                                <?= htmlspecialchars($_SESSION['role']) ?>
                            </span>
                        </a>
                    <?php else: ?>
                        <a href="<?= APP_URL ?>/login" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors duration-200">Log in</a>
                        <a href="<?= APP_URL ?>/register" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200">Sign up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow">
        <?php
            // The controller will set $contentView to the nested view file path
            if (isset($contentView)) {
                require BASE_PATH . "/resources/views/{$contentView}.php";
            }
        ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">&copy; <?= date('Y') ?> CraftConnect. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
