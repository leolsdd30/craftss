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
?>
<div class="bg-gray-50 min-h-screen pb-16">

    <!-- Cover Banner -->
    <div class="h-44 w-full relative overflow-hidden
        <?= $isCraftsman ? 'bg-gradient-to-r from-indigo-700 to-indigo-500' : 'bg-gradient-to-r from-gray-700 to-gray-500' ?>">
        <div class="absolute inset-0 opacity-20"
             style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
        <?php if ($isCraftsman && !empty($craftsmanDetails['is_published'])): ?>
        <div class="absolute top-4 right-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white bg-opacity-20 text-white border border-white border-opacity-30">
                <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></span>
                Listed on Marketplace
            </span>
        </div>
        <?php endif; ?>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Error banner -->
        <?php if (isset($_GET['error']) && $_GET['error'] === 'incomplete'): ?>
        <div class="mt-4 flex items-start space-x-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            <svg class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Profile incomplete — please add your service category, location, and phone number before publishing.</span>
        </div>
        <?php endif; ?>

        <!-- Main Layout -->
        <div class="flex flex-col lg:flex-row gap-6 -mt-12 relative z-10">

            <!-- ============================================================ -->
            <!-- LEFT SIDEBAR -->
            <!-- ============================================================ -->
            <div class="w-full lg:w-72 flex-shrink-0">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 relative">

                    <!-- Avatar + header -->
                    <div class="relative pt-10 px-6 pb-5 text-center border-b border-gray-100">

                        <!-- Avatar -->
                        <div class="absolute -top-10 left-1/2 -translate-x-1/2">
                            <div class="relative w-20 h-20 rounded-full ring-4 ring-white shadow-md overflow-hidden bg-white">
                                <img src="<?= get_profile_picture_url($user['profile_picture'] ?? 'default.png', $user['first_name'], $user['last_name']) ?>"
                                     alt="<?= htmlspecialchars($user['first_name']) ?>"
                                     class="object-cover w-full h-full">
                            </div>
                            <?php if ($isCraftsman && !empty($craftsmanDetails['is_verified'])): ?>
                            <div class="absolute -bottom-0.5 -right-0.5 bg-white rounded-full p-0.5 shadow-sm">
                                <svg class="h-4 w-4 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812 3.066 3.066 0 00.723 1.745 3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Favorite button (homeowners only, viewing a craftsman) -->
                        <?php if (isset($_SESSION['user_id']) && $isCraftsman && !$isOwnProfile && ($_SESSION['role'] ?? '') !== 'admin'): ?>
                        <div class="absolute top-3 right-3">
                            <button type="button" onclick="toggleFavorite(<?= $user['id'] ?>, this)"
                                class="p-1.5 rounded-full bg-white shadow-sm border transition-all duration-150
                                       <?= $isFavorite ? 'border-pink-200 text-pink-500' : 'border-gray-200 text-gray-300 hover:text-pink-400 hover:border-pink-200' ?>"
                                title="<?= $isFavorite ? 'Remove from saved' : 'Save craftsman' ?>">
                                <?php if ($isFavorite): ?>
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                </svg>
                                <?php else: ?>
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <?php endif; ?>
                            </button>
                        </div>
                        <?php endif; ?>

                        <!-- Name + role -->
                        <h1 class="text-lg font-bold text-gray-900 mt-1">
                            <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                        </h1>

                        <?php if ($isCraftsman): ?>
                        <span class="inline-flex items-center mt-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold <?= $catStyles['badge'] ?>">
                            <?= htmlspecialchars($craftsmanDetails['service_category'] ?? 'Professional') ?>
                        </span>
                        <?php else: ?>
                        <span class="inline-flex items-center mt-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                            Homeowner
                        </span>
                        <?php endif; ?>

                        <?php if (!empty($user['username'])): ?>
                        <p class="text-xs text-gray-400 mt-1">@<?= htmlspecialchars($user['username']) ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Info rows -->
                    <div class="px-5 py-4 space-y-2.5 border-b border-gray-100">
                        <?php if (!empty($user['wilaya'])): ?>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="h-4 w-4 mr-2 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <?= htmlspecialchars($user['wilaya']) ?>
                        </div>
                        <?php endif; ?>

                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="h-4 w-4 mr-2 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            Member since <?= date('Y', strtotime($user['created_at'])) ?>
                        </div>

                        <?php if ($isCraftsman): ?>
                        <!-- Stars -->
                        <div class="flex items-center gap-1">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <svg class="h-4 w-4 <?= $i <= round($rating['avg_rating']) ? 'text-yellow-400' : 'text-gray-200' ?>" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <?php endfor; ?>
                            <span class="text-xs font-semibold text-gray-700 ml-0.5"><?= number_format((float)$rating['avg_rating'], 1) ?></span>
                            <span class="text-xs text-gray-400">(<?= $rating['total_reviews'] ?> <?= $rating['total_reviews'] === 1 ? 'review' : 'reviews' ?>)</span>
                        </div>

                        <!-- Hourly rate -->
                        <div class="flex items-center justify-between bg-gray-50 rounded-lg px-3 py-2 border border-gray-100">
                            <span class="text-xs text-gray-500 font-medium">Hourly Rate</span>
                            <span class="text-sm font-bold text-indigo-600"><?= number_format($craftsmanDetails['hourly_rate'] ?? 0, 2) ?> <span class="text-xs font-normal text-gray-400">DZD</span></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Action Buttons -->
                    <div class="px-5 py-4 space-y-2">

                        <?php if ($isOwnProfile): ?>
                            <!-- Own profile actions -->
                            <a href="<?= APP_URL ?>/profile/edit"
                               class="flex items-center justify-center w-full px-4 py-2.5 border border-indigo-200 rounded-lg text-sm font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Profile
                            </a>

                            <?php if ($isCraftsman): ?>
                            <button onclick="openLaunchModal()"
                                class="flex items-center justify-center w-full px-4 py-2.5 border border-transparent rounded-lg text-sm font-semibold text-white transition
                                       <?= !empty($craftsmanDetails['is_published']) ? 'bg-green-600 hover:bg-green-700' : 'bg-indigo-600 hover:bg-indigo-700' ?>">
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

                            <button type="button" onclick="openLogoutModal()"
                                class="flex items-center justify-center w-full px-4 py-2 rounded-lg text-sm font-medium text-red-500 hover:text-red-700 hover:bg-red-50 transition border border-transparent">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>

                        <?php elseif (isset($_SESSION['user_id'])): ?>
                            <!-- Logged in, viewing someone else -->
                            <a href="<?= APP_URL ?>/messages/<?= htmlspecialchars($user['username'] ?? '') ?>"
                               class="flex items-center justify-center w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition">
                                <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                Send Message
                            </a>
                            <?php if ($isCraftsman && isset($_SESSION['user_id']) && !$isOwnProfile && ($_SESSION['role'] ?? '') !== 'admin'): ?>
                            <a href="<?= APP_URL ?>/bookings/create/<?= htmlspecialchars($user['username']) ?>"
                               class="flex items-center justify-center w-full px-4 py-2.5 border border-transparent rounded-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Request Booking
                            </a>
                            <?php endif; ?>

                        <?php elseif ($isCraftsman): ?>
                            <!-- Guest viewing craftsman -->
                            <a href="<?= APP_URL ?>/login"
                               class="flex items-center justify-center w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 transition">
                                Send Message
                            </a>
                            <a href="<?= APP_URL ?>/register"
                               class="flex items-center justify-center w-full px-4 py-2.5 border border-transparent rounded-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition">
                                Sign up to Book
                            </a>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- RIGHT MAIN CONTENT -->
            <!-- ============================================================ -->
            <div class="flex-1 min-w-0 space-y-5">

                <?php if ($isCraftsman): ?>

                <!-- About Me -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-base font-bold text-gray-900 mb-3 flex items-center">
                        <svg class="mr-2 h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="mr-2 h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Portfolio
                        <?php if (!empty($images)): ?>
                        <span class="ml-2 text-xs text-gray-400 font-normal"><?= count($images) ?> photo<?= count($images) !== 1 ? 's' : '' ?></span>
                        <?php endif; ?>
                    </h2>

                    <?php if (!empty($images)): ?>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <?php foreach ($images as $index => $img): ?>
                        <div class="aspect-square rounded-lg overflow-hidden bg-gray-100 cursor-pointer group hover:shadow-md transition-shadow duration-200"
                             onclick="openLightbox(<?= $index ?>)">
                            <img src="<?= APP_URL ?>/uploads/portfolio/<?= htmlspecialchars($img) ?>"
                                 alt="Portfolio <?= $index + 1 ?>"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
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
        </div>

        <!-- ============================================================ -->
        <!-- REVIEWS SECTION (Craftsman only, below main layout) -->
        <!-- ============================================================ -->
        <?php if ($isCraftsman): ?>
        <div class="mt-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">
                    Reviews
                    <span class="ml-1 text-sm font-normal text-gray-400">(<?= $rating['total_reviews'] ?>)</span>
                </h2>
                <?php if ($rating['avg_rating'] > 0): ?>
                <div class="flex items-center gap-1.5">
                    <span class="text-2xl font-extrabold text-gray-900"><?= number_format((float)$rating['avg_rating'], 1) ?></span>
                    <div class="flex">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <svg class="h-5 w-5 <?= $i <= round($rating['avg_rating']) ? 'text-yellow-400' : 'text-gray-200' ?>" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <?php endfor; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($reviews)): ?>
            <div class="space-y-3">
                <?php foreach ($reviews as $review): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-start space-x-3">
                        <img class="h-9 w-9 rounded-full object-cover flex-shrink-0"
                             src="<?= get_profile_picture_url($review['profile_picture'] ?? 'default.png', $review['first_name'], $review['last_name']) ?>"
                             alt="<?= htmlspecialchars($review['first_name']) ?>">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($review['first_name'] . ' ' . $review['last_name']) ?></p>
                                <div class="flex items-center gap-1">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <svg class="h-3.5 w-3.5 <?= $i <= $review['star_rating'] ? 'text-yellow-400' : 'text-gray-200' ?>" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5"><?= date('M d, Y', strtotime($review['created_at'])) ?></p>
                            <?php if (!empty($review['comment'])): ?>
                            <p class="mt-2 text-sm text-gray-600 leading-relaxed break-words"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php else: ?>
            <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-10 text-center">
                <svg class="mx-auto h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                <h3 class="text-sm font-medium text-gray-900 mt-1">No reviews yet</h3>
                <p class="text-xs text-gray-400 mt-1">Be the first to review this craftsman!</p>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</div>

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
                <?php if (empty($craftsmanDetails['id']) || empty($user['wilaya']) || empty($user['phone_number'])): ?>
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 rounded-md mb-6 text-sm shadow-sm">
                    <p class="font-bold flex items-center">
                        <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Profile Incomplete
                    </p>
                    <p class="mt-1 text-yellow-700">To publish your marketing card, please <a href="<?= APP_URL ?>/profile/edit" class="font-bold underline hover:text-yellow-900">edit your profile</a> and provide your service category, hourly rate, phone number, and location first.</p>
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
                    <?php if (!empty($craftsmanDetails['id']) && !empty($user['wilaya']) && !empty($user['phone_number'])): ?>
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
    img.src = '<?= APP_URL ?>/uploads/portfolio/' + portfolioImages[lightboxIndex];
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
    var isCurrentlyFavorite = btnElement.classList.contains('text-pink-500');

    // Optimistic UI update
    if (isCurrentlyFavorite) {
        btnElement.classList.remove('border-pink-200', 'text-pink-500');
        btnElement.classList.add('border-gray-200', 'text-gray-300', 'hover:text-pink-400', 'hover:border-pink-200');
        btnElement.title = 'Save to favorites';
        icon.setAttribute('fill', 'none');
        icon.setAttribute('viewBox', '0 0 24 24');
        icon.setAttribute('stroke', 'currentColor');
        icon.setAttribute('stroke-width', '2');
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />';
    } else {
        btnElement.classList.remove('border-gray-200', 'text-gray-300', 'hover:text-pink-400', 'hover:border-pink-200');
        btnElement.classList.add('border-pink-200', 'text-pink-500');
        btnElement.title = 'Remove from favorites';
        icon.removeAttribute('stroke');
        icon.removeAttribute('stroke-width');
        icon.setAttribute('fill', 'currentColor');
        icon.setAttribute('viewBox', '0 0 20 20');
        icon.innerHTML = '<path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />';
    }

    try {
        var response = await fetch('<?= APP_URL ?>/favorites/toggle', {
            method: 'POST',
            credentials: 'same-origin',
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
        if (!data.success) {
            alert(data.message || 'Failed to update favorites.');
            window.location.reload();
        }
    } catch (e) {
        console.error('Error toggling favorite:', e);
        window.location.reload();
    }
}
</script>