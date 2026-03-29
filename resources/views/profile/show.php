<!-- Profile Show Page -->
<?php
$isOwnProfile = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['id'];
$isHomeowner  = isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'homeowner';
$isCraftsman  = $user['role'] === 'craftsman';

// Use get_category_classes helper for badge styling
if ($isCraftsman) {
    $catStyles = get_category_classes($craftsmanDetails['service_category'] ?? 'General Handyman');
}

$images = [];
if ($isCraftsman && !empty($craftsmanDetails['portfolio_images'])) {
    $decoded = json_decode($craftsmanDetails['portfolio_images'], true);
    $images = is_array($decoded) ? $decoded : [];
}

$canPublish = false;
if ($isCraftsman) {
    $canPublish = !(
        empty($user['first_name']) || 
        empty($user['last_name']) || 
        empty($user['phone_number']) || 
        empty($user['wilaya']) ||
        empty($craftsmanDetails['service_category']) ||
        empty($craftsmanDetails['bio']) ||
        !isset($craftsmanDetails['hourly_rate']) || $craftsmanDetails['hourly_rate'] <= 0
    );
}
?>
<div class="bg-gray-50 min-h-screen pb-16 pt-6 sm:pt-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- ============================================================ -->
        <!-- NOTIFICATIONS / ALERTS -->
        <!-- ============================================================ -->
        <?php if (isset($_GET['success']) && $_GET['success'] === 'email_verified'): ?>
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl shadow-sm flex items-start gap-4 animate-fade-in-down">
            <div class="flex-shrink-0 bg-emerald-100 p-2 rounded-full mt-0.5">
                <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-emerald-900 mb-1">Email Successfully Verified! 🎉</h3>
                <p class="text-emerald-700 font-medium">Your email address has been securely confirmed. Your account is now fully active, and you have complete access to post jobs, browse profiles, and manage bookings!</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- ============================================================ -->
        <!-- PREMIUM HORIZONTAL HEADER -->
        <!-- ============================================================ -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden mb-8 relative">
            <!-- Cover Banner (Uses Portfolio Image or Gradient) -->
            <?php 
            $coverStyle = '';
            if ($isCraftsman && !empty($images) && isset($images[0])) {
                $coverUrl = strpos($images[0], '/') !== false 
                    ? APP_URL . '/uploads/' . ltrim(htmlspecialchars($images[0]), '/')
                    : APP_URL . '/uploads/portfolio/' . htmlspecialchars($images[0]);
                $coverStyle = 'background-image: url(' . $coverUrl . '); background-size: cover; background-position: center;';
            } 
            ?>
            <div class="h-32 sm:h-56 w-full relative overflow-hidden <?= empty($coverStyle) ? ($isCraftsman ? 'bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600' : 'bg-gradient-to-r from-gray-600 via-gray-500 to-gray-400') : '' ?>" style="<?= $coverStyle ?>">
                <?php if (!empty($coverStyle)): ?>
                <!-- Dark gradient overlay for image cover to ensure text/avatar pops -->
                <div class="absolute inset-0 bg-gradient-to-b from-black/10 via-black/20 to-black/60 backdrop-blur-[2px]"></div>
                <?php else: ?>
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1.5px, transparent 1.5px); background-size: 24px 24px;"></div>
                <?php endif; ?>
            </div>

            <!-- Header Content -->
            <div class="px-4 sm:px-10 pb-6 sm:pb-8 pt-2">
                <!-- ═══ MOBILE LAYOUT: Avatar left + mini actions right ═══ -->
                <div class="sm:hidden relative z-10">
                    <!-- Top row: Avatar left, Share+Save right -->
                    <div class="flex items-start justify-between -mt-12">
                        <div class="relative w-24 h-24 rounded-2xl ring-4 ring-white shadow-lg overflow-hidden bg-white shrink-0">
                            <img src="<?= get_profile_picture_url($user['profile_picture'] ?? 'default.png', $user['first_name'], $user['last_name']) ?>"
                                 alt="<?= htmlspecialchars($user['first_name']) ?>"
                                 class="object-cover w-full h-full">
                        </div>
                        <div class="flex items-center gap-2 mt-3">
                            <!-- Share icon btn -->
                            <div class="relative" id="shareWrapperMobile">
                                <button type="button" onclick="toggleShareMenu()"
                                    class="flex items-center justify-center w-10 h-10 rounded-xl border shadow-sm bg-white border-gray-200 text-gray-500 hover:bg-gray-50 transition-all"
                                    title="Share">
                                    <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                    </svg>
                                </button>
                            </div>
                            <?php if (isset($_SESSION['user_id']) && $isCraftsman && !$isOwnProfile && ($_SESSION['role'] ?? '') !== 'admin'): ?>
                            <!-- Save icon btn -->
                            <button type="button" onclick="toggleFavorite(<?= $user['id'] ?>, this)"
                                class="flex items-center justify-center w-10 h-10 rounded-xl border shadow-sm transition-all
                                       <?= $isFavorite ? 'bg-pink-50 border-pink-200 text-pink-600' : 'bg-white border-gray-200 text-gray-500' ?>"
                                title="<?= $isFavorite ? 'Remove from saved' : 'Save' ?>">
                                <?php if ($isFavorite): ?>
                                <svg class="h-[18px] w-[18px] text-pink-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                </svg>
                                <?php else: ?>
                                <svg class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <?php endif; ?>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Name + Meta (left-aligned, compact) -->
                    <div class="mt-3">
                        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                            <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                            <?php if ($isCraftsman && !empty($craftsmanDetails['is_verified'])): ?>
                            <svg class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <?php endif; ?>
                        </h1>
                        <div class="mt-1.5 flex flex-wrap items-center gap-2.5">
                            <?php if (!empty($user['username'])): ?>
                            <span class="text-xs font-medium text-gray-400">@<?= htmlspecialchars($user['username']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($user['wilaya'])): ?>
                            <span class="text-xs font-semibold text-gray-500 flex items-center">
                                <svg class="h-3.5 w-3.5 mr-0.5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                <?= htmlspecialchars($user['wilaya']) ?>
                            </span>
                            <?php endif; ?>
                            <?php if ($isCraftsman && !empty($craftsmanDetails['is_published'])): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-green-50 text-green-700 text-[10px] font-bold uppercase tracking-wider border border-green-200">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1 animate-pulse"></span>
                                Available
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Mobile Action Buttons Row -->
                    <div class="flex gap-2.5 mt-4">
                        <?php if ($isOwnProfile): ?>
                            <a href="<?= APP_URL ?>/profile/edit"
                               class="flex-1 flex items-center justify-center py-2.5 rounded-xl text-sm font-bold bg-white text-gray-700 border border-gray-200 shadow-sm transition-all">
                                <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </a>
                            <?php if ($isCraftsman): ?>
                            <button onclick="openLaunchModal()"
                                class="flex-1 flex items-center justify-center py-2.5 rounded-xl text-sm font-bold text-white shadow-sm transition-all
                                       <?= !empty($craftsmanDetails['is_published']) ? 'bg-emerald-500' : 'bg-indigo-600' ?>">
                                <?php if (!empty($craftsmanDetails['is_published'])): ?>
                                <svg class="h-4 w-4 mr-1.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Live
                                <?php else: ?>
                                <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Launch
                                <?php endif; ?>
                            </button>
                            <?php endif; ?>
                        <?php elseif (isset($_SESSION['user_id'])): ?>
                            <a href="<?= APP_URL ?>/messages/<?= htmlspecialchars($user['username'] ?? '') ?>"
                               class="flex-1 flex items-center justify-center py-2.5 rounded-xl text-sm font-bold bg-white text-gray-700 border border-gray-200 shadow-sm transition-all">
                                <svg class="h-4 w-4 mr-1.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                Message
                            </a>
                            <?php if ($isCraftsman && !$isOwnProfile && ($_SESSION['role'] ?? '') !== 'admin'): ?>
                            <a href="<?= APP_URL ?>/bookings/create/<?= htmlspecialchars($user['username']) ?>"
                               class="flex-1 flex items-center justify-center py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 shadow-sm transition-all">
                                <svg class="h-4 w-4 mr-1.5 text-indigo-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Book
                            </a>
                            <?php endif; ?>
                        <?php elseif ($isCraftsman): ?>
                            <a href="<?= APP_URL ?>/login"
                               class="flex-1 flex items-center justify-center py-2.5 rounded-xl text-sm font-bold bg-white text-gray-700 border border-gray-200 shadow-sm transition-all">
                                Message
                            </a>
                            <a href="<?= APP_URL ?>/register"
                               class="flex-1 flex items-center justify-center py-2.5 rounded-xl text-sm font-bold text-white bg-indigo-600 shadow-sm transition-all">
                                Sign up
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ═══ DESKTOP LAYOUT: Original horizontal layout ═══ -->
                <div class="hidden sm:flex sm:flex-row sm:items-end justify-between relative z-10 gap-6">
                    <!-- Avatar & Name Profile Box -->
                    <div class="flex flex-row items-end gap-5">
                        <div class="relative w-40 h-40 rounded-3xl ring-[6px] ring-white shadow-lg overflow-hidden bg-white shrink-0 -mt-24">
                            <img src="<?= get_profile_picture_url($user['profile_picture'] ?? 'default.png', $user['first_name'], $user['last_name']) ?>"
                                 alt="<?= htmlspecialchars($user['first_name']) ?>"
                                 class="object-cover w-full h-full">
                        </div>
                        
                        <div class="text-left pb-3">
                            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2.5">
                                <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                <?php if ($isCraftsman && !empty($craftsmanDetails['is_verified'])): ?>
                                <svg class="h-7 w-7 text-blue-500" viewBox="0 0 20 20" fill="currentColor" title="Verified Information">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <?php endif; ?>
                            </h1>
                            <div class="mt-3 flex flex-wrap items-center gap-3">
                                <?php if (!empty($user['username'])): ?>
                                <span class="text-sm font-medium text-gray-400">@<?= htmlspecialchars($user['username']) ?></span>
                                <?php endif; ?>

                                <?php if (!empty($user['wilaya'])): ?>
                                <span class="text-sm font-semibold text-gray-500 flex items-center">
                                    <svg class="h-4 w-4 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                    <?= htmlspecialchars($user['wilaya']) ?>
                                </span>
                                <?php endif; ?>

                                <?php if ($isCraftsman && !empty($craftsmanDetails['is_published'])): ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded bg-green-50 text-green-700 text-xs font-bold uppercase tracking-wider border border-green-200">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                                    Available
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Action Buttons -->
                    <div class="flex flex-row items-center gap-3 pb-3">
                        
                        <!-- Share Button -->
                        <div class="relative" id="shareWrapper">
                            <button type="button" onclick="toggleShareMenu()"
                                class="flex items-center justify-center px-4 py-3 rounded-xl text-sm font-bold border shadow-sm transition-all duration-200 bg-white border-gray-200 text-gray-600 hover:bg-gray-50"
                                title="Share this profile">
                                <svg class="h-5 w-5 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                </svg>
                                Share
                            </button>
                            <!-- Share Dropdown -->
                            <div id="shareDropdown" class="hidden absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden">
                                <div class="px-3.5 py-2.5 border-b border-gray-100">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Share Profile</p>
                                </div>
                                <button onclick="copyProfileLink()" class="w-full flex items-center gap-3 px-3.5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                    <span id="copyLinkText">Copy Link</span>
                                </button>
                                <a href="https://wa.me/?text=<?= urlencode('Check out this craftsman on Crafts: ' . APP_URL . '/profile/' . ($user['username'] ?? '')) ?>" target="_blank" rel="noopener" class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors">
                                    <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    WhatsApp
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(APP_URL . '/profile/' . ($user['username'] ?? '')) ?>" target="_blank" rel="noopener" class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <svg class="h-4 w-4 text-gray-400" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    Facebook
                                </a>
                            </div>
                        </div>

                        <?php if (isset($_SESSION['user_id']) && $isCraftsman && !$isOwnProfile && ($_SESSION['role'] ?? '') !== 'admin'): ?>
                        <!-- Favorite button -->
                        <button type="button" onclick="toggleFavorite(<?= $user['id'] ?>, this)"
                            class="flex items-center justify-center px-4 py-3 rounded-xl text-sm font-bold border shadow-sm transition-all duration-200
                                   <?= $isFavorite ? 'bg-pink-50 border-pink-200 text-pink-600 hover:bg-pink-100' : 'bg-white border-gray-200 text-gray-600 hover:bg-gray-50' ?>"
                            title="<?= $isFavorite ? 'Remove from saved' : 'Save craftsman' ?>">
                            <?php if ($isFavorite): ?>
                            <svg class="h-5 w-5 mr-2 text-pink-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                            </svg>
                            Saved
                            <?php else: ?>
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            Save
                            <?php endif; ?>
                        </button>
                        <?php endif; ?>

                        <?php if ($isOwnProfile): ?>
                            <a href="<?= APP_URL ?>/profile/edit"
                               class="flex items-center justify-center px-5 py-3 rounded-xl text-sm font-bold bg-white text-gray-700 border border-gray-200 shadow-sm hover:shadow hover:bg-gray-50 hover:border-gray-300 transition-all">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Profile
                            </a>
                            <?php if ($isCraftsman): ?>
                            <button onclick="openLaunchModal()"
                                class="flex items-center justify-center px-5 py-3 rounded-xl text-sm font-bold text-white shadow-sm hover:shadow transition-all
                                       <?= !empty($craftsmanDetails['is_published']) ? 'bg-emerald-500 hover:bg-emerald-600' : 'bg-indigo-600 hover:bg-indigo-700' ?>">
                                <?php if (!empty($craftsmanDetails['is_published'])): ?>
                                <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Card is Live
                                <?php else: ?>
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Launch Card
                                <?php endif; ?>
                            </button>
                            <?php endif; ?>
                        <?php elseif (isset($_SESSION['user_id'])): ?>
                            <a href="<?= APP_URL ?>/messages/<?= htmlspecialchars($user['username'] ?? '') ?>"
                               class="flex items-center justify-center px-5 py-3 rounded-xl text-sm font-bold bg-white text-gray-700 border border-gray-200 shadow-sm hover:shadow hover:bg-gray-50 hover:border-gray-300 transition-all">
                                <svg class="h-4 w-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                Message
                            </a>
                            <?php if ($isCraftsman && !$isOwnProfile && ($_SESSION['role'] ?? '') !== 'admin'): ?>
                            <a href="<?= APP_URL ?>/bookings/create/<?= htmlspecialchars($user['username']) ?>"
                               class="flex items-center justify-center px-5 py-3 rounded-xl text-sm font-bold text-white bg-indigo-600 shadow-sm hover:shadow hover:bg-indigo-700 transition-all">
                                <svg class="h-4 w-4 mr-2 text-indigo-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Request Booking
                            </a>
                            <?php endif; ?>
                        <?php elseif ($isCraftsman): ?>
                            <a href="<?= APP_URL ?>/login"
                               class="flex items-center justify-center px-5 py-3 rounded-xl text-sm font-bold bg-white text-gray-700 border border-gray-200 shadow-sm hover:shadow hover:bg-gray-50 transition-all">
                                Send Message
                            </a>
                            <a href="<?= APP_URL ?>/register"
                               class="flex items-center justify-center px-5 py-3 rounded-xl text-sm font-bold text-white bg-indigo-600 shadow-sm hover:shadow hover:bg-indigo-700 transition-all">
                                Sign up to Book
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Stats Grid -->
                <?php if ($isCraftsman): ?>
                <div class="grid grid-cols-2 md:grid-cols-<?= !empty($showTotalJobs) ? '4' : '3' ?> gap-3 sm:gap-4 mt-8 pt-8 border-t border-gray-100">
                    
                    <!-- Global Rating -->
                    <div class="bg-yellow-50/40 border border-yellow-100/50 rounded-xl p-4 shadow-sm transition-all flex flex-col justify-center">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-yellow-500" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <p class="text-[11px] font-bold text-yellow-700/80 uppercase tracking-wider">Global Rating</p>
                        </div>
                        <div class="flex items-end gap-1.5 mt-0.5">
                            <p class="text-xl font-bold text-gray-900 tracking-tight leading-none"><?= number_format((float)$rating['avg_rating'], 1) ?></p>
                            <span class="text-[11px] font-medium text-gray-500 mb-[1px]">/ 5.0 (<?= $rating['total_reviews'] ?>)</span>
                        </div>
                    </div>

                    <!-- Hourly Rate -->
                    <div class="bg-indigo-50/40 border border-indigo-100/50 rounded-xl p-4 shadow-sm transition-all flex flex-col justify-center">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-[11px] font-bold text-indigo-700/80 uppercase tracking-wider">Hourly Rate</p>
                        </div>
                        <p class="text-xl font-bold text-gray-900 tracking-tight leading-none mt-0.5"><?= number_format($craftsmanDetails['hourly_rate'] ?? 0, 2) ?> <span class="text-sm font-medium text-gray-500 ml-0.5">DZD</span></p>
                    </div>

                    <!-- Platform Member -->
                    <div class="bg-teal-50/40 border border-teal-100/50 rounded-xl p-4 shadow-sm transition-all flex flex-col justify-center">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-[11px] font-bold text-teal-700/80 uppercase tracking-wider">Member Since</p>
                        </div>
                        <p class="text-xl font-bold text-gray-900 tracking-tight leading-none mt-0.5"><?= date('Y', strtotime($user['created_at'])) ?></p>
                    </div>

                    <!-- Total Jobs -->
                    <?php if (!empty($showTotalJobs)): ?>
                    <div class="bg-purple-50/40 border border-purple-100/50 rounded-xl p-4 shadow-sm transition-all flex flex-col justify-center">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            <p class="text-[11px] font-bold text-purple-700/80 uppercase tracking-wider">Total Jobs</p>
                        </div>
                        <p class="text-xl font-bold text-gray-900 tracking-tight leading-none mt-0.5"><?= (int)($totalCompletedJobs ?? 0) ?></p>
                    </div>
                    <?php endif; ?>

                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- MAIN CONTENT TWO-COLUMN GRID -->
        <!-- ============================================================ -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 relative">

            <!-- Left Main Content (Alerts, About, Portfolio) - spans 2 cols on desktop -->
            <div class="lg:col-span-2 space-y-6 flex flex-col min-w-0">

                <!-- Alert Banners -->
                <?php if (isset($_GET['error']) && $_GET['error'] === 'incomplete'): ?>
                <div class="flex items-start space-x-3 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl text-sm shadow-md">
                    <svg class="h-5 w-5 flex-shrink-0 mt-0.5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-bold text-base mb-1">Profile Incomplete</p>
                        <p class="text-red-600">To publish your card, please edit your profile and provide your: Phone Number, Location, Service Category, Hourly Rate, and Bio.</p>
                    </div>
                </div>
                <?php elseif (isset($_GET['info']) && $_GET['info'] === 'unpublished'): ?>
                <div class="flex items-start space-x-3 bg-blue-50 border border-blue-200 text-blue-800 px-5 py-4 rounded-xl text-sm shadow-md">
                    <svg class="h-5 w-5 flex-shrink-0 mt-0.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-bold text-base mb-1">Card Automatically Unpublished</p>
                        <p class="text-blue-700">Because you removed required information from your profile, your marketing card was taken off the Job Board. Fill in the required fields to publish it again.</p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($isCraftsman): ?>

                <!-- About Me -->
                <div class="bg-white rounded-[1.5rem] shadow-sm border border-gray-100 p-7">
                    <h2 class="text-lg font-extrabold text-gray-900 mb-4 flex items-center">
                        <svg class="mr-2.5 h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        About Me
                    </h2>
                    <?php if (!empty($craftsmanDetails['bio'])): ?>
                    <div class="prose prose-sm text-gray-600 leading-relaxed max-w-none">
                        <?= nl2br(htmlspecialchars($craftsmanDetails['bio'])) ?>
                    </div>
                    <?php else: ?>
                    <p class="text-sm text-gray-400 italic">This craftsman hasn't added a bio yet.</p>
                    <?php endif; ?>
                </div>

                <!-- Portfolio -->
                <div class="bg-white rounded-[1.5rem] shadow-sm border border-gray-100 p-7">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-extrabold text-gray-900 flex items-center">
                            <svg class="mr-2.5 h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Portfolio
                        </h2>
                        <?php if (!empty($images)): ?>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100"><?= count($images) ?> photo<?= count($images) !== 1 ? 's' : '' ?></span>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($images)): ?>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4">
                        <?php foreach ($images as $index => $img): 
                              $imgUrl = strpos($img, '/') !== false 
                                  ? APP_URL . '/uploads/' . ltrim(htmlspecialchars($img), '/')
                                  : APP_URL . '/uploads/portfolio/' . htmlspecialchars($img);
                        ?>
                        <div class="relative rounded-xl overflow-hidden bg-gray-100 cursor-pointer group shadow-sm hover:shadow-lg transition-all duration-300 <?= $index === 0 ? 'col-span-2 row-span-2 aspect-[4/3] sm:aspect-auto' : 'aspect-square' ?>"
                             onclick="openLightbox(<?= $index ?>)">
                            <img src="<?= $imgUrl ?>"
                                 alt="Portfolio <?= $index + 1 ?>"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <!-- Premium Hover Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <div class="bg-white/20 backdrop-blur-md p-3 rounded-full transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 ring-1 ring-white/30">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="bg-gray-50 rounded-lg border-2 border-dashed border-gray-200 p-10 text-center">
                        <svg class="mx-auto h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm text-gray-400">No portfolio images uploaded yet.</p>
                        <?php if ($isOwnProfile): ?>
                        <a href="<?= APP_URL ?>/profile/edit" class="mt-2 inline-block text-xs text-indigo-600 hover:text-indigo-700 font-medium">Add photos →</a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <?php else: ?>
                <!-- Homeowner profile content -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-base font-bold text-gray-900 mb-3">About</h2>
                    <p class="text-sm text-gray-500">This user is an active homeowner on Crafts.</p>
                </div>
                <?php endif; ?>

            </div>

            <!-- ============================================================ -->
            <!-- RIGHT SIDEBAR (Reviews) -->
            <!-- ============================================================ -->
            <?php if ($isCraftsman): ?>
            <div class="lg:col-span-1">
                <div class="sticky top-24 pt-2">
                    <div class="flex items-center justify-between mb-5 px-1 pb-3 border-b border-gray-100">
                        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight flex items-center">
                            <svg class="mr-2.5 h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            Reviews
                            <span class="ml-2.5 text-xs font-bold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-full"><?= $rating['total_reviews'] ?></span>
                        </h2>
                    </div>

                    <?php if (!empty($reviews)): ?>
                    <div class="space-y-4 max-h-[800px] overflow-y-auto pr-2 custom-scrollbar pb-6 relative">
                        <?php foreach ($reviews as $review): ?>
                        <div class="bg-white rounded-[1.25rem] shadow-sm border border-gray-100/60 p-5 relative overflow-hidden transition-all hover:shadow-md hover:border-indigo-100">
                            <!-- Decorative background quote -->
                            <div class="absolute -right-2 -top-4 text-gray-50/80 pointer-events-none select-none" style="font-size: 8rem; font-family: serif; line-height: 1;">&rdquo;</div>
                            
                            <div class="flex items-start space-x-3.5 relative z-10">
                                <img class="h-9 w-9 rounded-full object-cover flex-shrink-0"
                                     src="<?= get_profile_picture_url($review['profile_picture'] ?? 'default.png', $review['first_name'], $review['last_name']) ?>"
                                     alt="<?= htmlspecialchars($review['first_name']) ?>">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between flex-wrap gap-2 mb-0.5">
                                        <p class="text-[15px] font-bold text-gray-900"><?= htmlspecialchars($review['first_name'] . ' ' . $review['last_name']) ?></p>
                                        <div class="flex items-center gap-0.5">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <svg class="h-3.5 w-3.5 <?= $i <= $review['star_rating'] ? 'text-yellow-400' : 'text-gray-200' ?>" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <p class="text-[11px] font-semibold text-indigo-400/80 uppercase tracking-wider mb-2.5"><?= date('F j, Y', strtotime($review['created_at'])) ?></p>
                                    
                                    <?php if (!empty($review['comment'])): ?>
                                    <p class="text-sm text-gray-600 leading-relaxed break-words relative z-10 italic">"<?= nl2br(htmlspecialchars($review['comment'])) ?>"</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    </div>
                    <?php else: ?>
                    <div class="bg-white rounded-2xl border-2 border-dashed border-gray-200 p-8 text-center shadow-sm">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <h3 class="text-sm font-bold text-gray-900 mt-1">No reviews yet</h3>
                        <p class="text-[13px] text-gray-500 mt-1">Be the first to review this craftsman!</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<style>
/* Custom scrollbar for reviews */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #e5e7eb;
    border-radius: 10px;
}
</style>

<!-- ============================================================ -->
<!-- PORTFOLIO LIGHTBOX -->
<!-- ============================================================ -->
<?php if ($isCraftsman && !empty($images)): ?>
<div id="portfolio-lightbox" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-90" onclick="closeLightbox()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <button onclick="closeLightbox()" class="absolute top-4 right-4 z-10 text-white hover:text-gray-300 transition p-2">
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div class="absolute top-4 left-4 z-10 text-white text-sm font-medium bg-black bg-opacity-50 px-3 py-1 rounded-full">
            <span id="lightbox-counter"></span>
        </div>
        <button onclick="lightboxPrev()" class="absolute left-4 z-10 text-white p-3 rounded-full bg-black bg-opacity-30 hover:bg-opacity-50 transition">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <img id="lightbox-image" src="" alt="Portfolio" class="max-h-[85vh] max-w-[90vw] object-contain rounded-lg shadow-2xl">
        <button onclick="lightboxNext()" class="absolute right-4 z-10 text-white p-3 rounded-full bg-black bg-opacity-30 hover:bg-opacity-50 transition">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
</div>
<?php endif; ?>

<!-- ============================================================ -->
<!-- LAUNCH CARD MODAL with Card Preview (own craftsman profile only) -->
<!-- ============================================================ -->
<?php if ($isOwnProfile && $isCraftsman): ?>
<div id="launchModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" aria-hidden="true" onclick="closeLaunchModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
            <div class="px-6 pt-6 pb-2">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 rounded-full p-2 mr-3">
                            <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Launch Card Preview</h3>
                    </div>
                    <button onclick="closeLaunchModal()" class="text-gray-400 hover:text-gray-500 transition">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="bg-white px-6 pt-2 pb-6">
                <?php if (!$canPublish): ?>
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 rounded-md mb-6 text-sm shadow-sm">
                    <p class="font-bold flex items-center">
                        <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Profile Incomplete
                    </p>
                    <p class="mt-1 text-yellow-700">To publish your marketing card, please <a href="<?= APP_URL ?>/profile/edit" class="font-bold underline hover:text-yellow-900">edit your profile</a> and provide your Phone Number, Location, Category, Bio, and Hourly Rate.</p>
                </div>
                <?php else: ?>
                <p class="text-gray-500 mb-4 text-center text-sm leading-relaxed">
                    This is how your business card appears to homeowners.
                </p>
                <?php endif; ?>

                <!-- Demo Card Box -->
                <?php $previewCatStyles = get_category_classes($craftsmanDetails['service_category'] ?? 'General Handyman'); ?>
                <div class="bg-white overflow-hidden shadow-md rounded-xl flex flex-col border border-gray-100 mx-auto w-full">
                    <div class="p-6 flex-grow relative overflow-hidden">
                        <!-- Tiny accent banner -->
                        <div class="h-1 bg-indigo-500 absolute top-0 inset-x-0"></div>
                        
                        <div class="flex items-center space-x-4 mb-4 mt-2">
                            <div class="relative">
                                <img class="h-16 w-16 rounded-full object-cover border-2 border-gray-200 shadow-sm" 
                                     src="<?= get_profile_picture_url($user['profile_picture'] ?? 'default.png', $user['first_name'], $user['last_name']) ?>" 
                                     alt="<?= htmlspecialchars($user['first_name']) ?>">
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 tracking-tight flex items-center gap-1">
                                    <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                    <?php if (!empty($craftsmanDetails['is_verified'])): ?>
                                    <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" title="Verified Professional">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <?php endif; ?>
                                </h2>
                                <div class="mt-0.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wider <?= $previewCatStyles['badge'] ?>">
                                        <?= htmlspecialchars($craftsmanDetails['service_category'] ?? 'Professional') ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <p class="text-sm text-gray-500 line-clamp-3 mb-4 leading-relaxed break-words">
                            <?= !empty($craftsmanDetails['bio']) ? htmlspecialchars($craftsmanDetails['bio']) : 'Your bio will appear here. Edit your profile to add one!' ?>
                        </p>

                        <div class="flex items-center justify-between text-sm py-3 border-t border-gray-100 bg-gray-50 -mx-6 px-6 -mb-6">
                            <div>
                                <span class="text-gray-500 text-xs font-medium uppercase tracking-wider">Hourly Rate</span>
                                <p class="font-bold text-gray-900 text-base"><?= number_format($craftsmanDetails['hourly_rate'] ?? 0, 2) ?> DZD</p>
                            </div>
                            <div class="text-right">
                                <span class="text-gray-500 text-xs font-medium uppercase tracking-wider">Success Rating</span>
                                <p class="font-bold text-indigo-600 flex items-center justify-end gap-1">
                                    100% 
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-3 bg-gray-100 border-t border-gray-200 mt-auto text-center text-xs text-gray-400 font-medium">
                        Preview Only
                    </div>
                </div>

            </div>
            <div class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 border-t border-gray-200">
                <div class="w-full sm:w-auto mt-3 sm:mt-0">
                    <button type="button" onclick="closeLaunchModal()" class="w-full inline-flex justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150">
                        Cancel
                    </button>
                </div>

                <div class="w-full sm:w-auto">
                    <?php if ($canPublish): ?>
                    <form id="publishForm" action="<?= APP_URL ?>/profile/publish" method="POST" class="w-full">
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                        <input type="hidden" name="status" value="<?= !empty($craftsmanDetails['is_published']) ? '0' : '1' ?>">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-bold rounded-md text-white <?= !empty($craftsmanDetails['is_published']) ? 'bg-red-600 hover:bg-red-700' : 'bg-indigo-600 hover:bg-indigo-700' ?> focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            <?php if (!empty($craftsmanDetails['is_published'])): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                                </svg>
                                Unpublish Card
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Go Live Now
                            <?php endif; ?>
                        </button>
                    </form>
                    <?php else: ?>
                    <a href="<?= APP_URL ?>/profile/edit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-bold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                        Complete Profile First
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ============================================================ -->
<!-- LOGOUT CONFIRMATION MODAL -->
<!-- ============================================================ -->
<?php if ($isOwnProfile): ?>
<div id="logoutModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" onclick="closeLogoutModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full transform transition-all">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 bg-red-100 rounded-full p-2 mr-3">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Log Out?</h3>
                </div>
                <p class="text-sm text-gray-600 mb-6">Are you sure you want to log out of your account?</p>
                <div class="flex justify-end space-x-3">
                    <button onclick="closeLogoutModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-150">Cancel</button>
                    <form action="<?= APP_URL ?>/logout" method="POST" class="inline">
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition duration-150">Yes, Log Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
// ── Lightbox ──────────────────────────────────────────────────────────────────
var portfolioImages = <?= json_encode(!empty($images) ? $images : []) ?>;
var lightboxIndex = 0;

function openLightbox(index) {
    lightboxIndex = index;
    updateLightbox();
    document.getElementById('portfolio-lightbox').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('portfolio-lightbox').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
function lightboxPrev() {
    lightboxIndex = (lightboxIndex - 1 + portfolioImages.length) % portfolioImages.length;
    updateLightbox();
}
function lightboxNext() {
    lightboxIndex = (lightboxIndex + 1) % portfolioImages.length;
    updateLightbox();
}
function updateLightbox() {
    var img = document.getElementById('lightbox-image');
    var path = portfolioImages[lightboxIndex];
    img.src = path.indexOf('/') !== -1 ? '<?= APP_URL ?>/uploads/' + path : '<?= APP_URL ?>/uploads/portfolio/' + path;
    document.getElementById('lightbox-counter').textContent = (lightboxIndex + 1) + ' / ' + portfolioImages.length;
}
document.addEventListener('keydown', function(e) {
    var lb = document.getElementById('portfolio-lightbox');
    if (!lb || lb.classList.contains('hidden')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') lightboxPrev();
    if (e.key === 'ArrowRight') lightboxNext();
});

// ── Launch Modal ──────────────────────────────────────────────────────────────
function openLaunchModal() {
    document.getElementById('launchModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeLaunchModal() {
    document.getElementById('launchModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
function confirmLaunch() {
    document.getElementById('publishForm').submit();
}

// ── Logout Modal ──────────────────────────────────────────────────────────────
function openLogoutModal() {
    document.getElementById('logoutModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeLogoutModal() {
    document.getElementById('logoutModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// ── Favorite Toggle ───────────────────────────────────────────────────────────
async function toggleFavorite(craftsmanId, btnElement) {
    var icon = btnElement.querySelector('svg');
    var textSpan = btnElement.querySelector('span');
    
    // Determine current state based on presence of the background class
    var isCurrentlyFavorite = btnElement.classList.contains('bg-pink-50');

    // 1) Instantly play a tiny pop animation on button
    btnElement.style.transform = 'scale(0.92)';
    setTimeout(() => btnElement.style.transform = 'scale(1)', 150);

    // 2) Optimistic UI Update (Real-time transition)
    if (isCurrentlyFavorite) {
        // Change to "Save" (Not favorite)
        btnElement.classList.remove('bg-pink-50', 'border-pink-200', 'text-pink-600', 'hover:bg-pink-100');
        btnElement.classList.add('bg-white', 'border-gray-200', 'text-gray-600', 'hover:bg-gray-50');
        btnElement.title = 'Save craftsman';
        
        icon.classList.remove('text-pink-500');
        icon.classList.add('text-gray-400');
        icon.setAttribute('fill', 'none');
        icon.setAttribute('viewBox', '0 0 24 24');
        icon.setAttribute('stroke', 'currentColor');
        icon.setAttribute('stroke-width', '2');
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />';
        
        if (textSpan) textSpan.innerText = 'Save';
    } else {
        // Change to "Saved" (Is favorite)
        btnElement.classList.remove('bg-white', 'border-gray-200', 'text-gray-600', 'hover:bg-gray-50');
        btnElement.classList.add('bg-pink-50', 'border-pink-200', 'text-pink-600', 'hover:bg-pink-100');
        btnElement.title = 'Remove from saved';
        
        icon.classList.remove('text-gray-400');
        icon.classList.add('text-pink-500');
        icon.removeAttribute('stroke');
        icon.removeAttribute('stroke-width');
        icon.setAttribute('fill', 'currentColor');
        icon.setAttribute('viewBox', '0 0 20 20');
        icon.innerHTML = '<path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />';
        
        if (textSpan) textSpan.innerText = 'Saved';
    }

    // 3) Send request to the backend with proper CSRF token
    try {
        var response = await fetch('<?= APP_URL ?>/favorites/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                craftsman_id: craftsmanId,
                csrf_token: '<?= e($_SESSION['csrf_token'] ?? '') ?>'
            })
        });
        
        var data = await response.json();
        
        // If the server rejected it, silently revert the UI back
        if (!response.ok || !data.success) {
            console.warn('Favorite toggle rejected by server:', data.message);
            // Revert changes by reloading
            window.location.reload();
        }
    } catch (e) {
        console.error('Network error during favorite toggle:', e);
        // Silent failure - or revert
        window.location.reload();
    }
}

// ── Share Profile ─────────────────────────────────────────────────────────────
function toggleShareMenu() {
    // On mobile, try native share first
    if (navigator.share) {
        navigator.share({
            title: '<?= e($user['first_name'] . ' ' . $user['last_name']) ?> on Crafts',
            text: 'Check out this craftsman on Crafts!',
            url: window.location.href
        }).catch(function() {});
        return;
    }
    // On desktop, toggle dropdown
    var dropdown = document.getElementById('shareDropdown');
    dropdown.classList.toggle('hidden');
}

function copyProfileLink() {
    var url = window.location.href;
    navigator.clipboard.writeText(url).then(function() {
        var textEl = document.getElementById('copyLinkText');
        textEl.innerText = 'Copied!';
        textEl.parentElement.classList.add('text-green-600');
        setTimeout(function() {
            textEl.innerText = 'Copy Link';
            textEl.parentElement.classList.remove('text-green-600');
        }, 2000);
    });
}

// Close share dropdown when clicking outside
document.addEventListener('click', function(e) {
    var wrapper = document.getElementById('shareWrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('shareDropdown').classList.add('hidden');
    }
});
</script>