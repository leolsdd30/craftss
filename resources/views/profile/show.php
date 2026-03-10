<!-- Generic Full Profile View -->
<div class="bg-gray-50 min-h-screen pb-12">
    <!-- Cover Banner -->
    <div class="h-48 <?= $user['role'] === 'craftsman' ? 'bg-indigo-600 bg-gradient-to-r from-indigo-700 to-indigo-500' : 'bg-gray-600 bg-gradient-to-r from-gray-700 to-gray-500' ?> w-full relative object-cover shadow-inner">
        <!-- Abstract CSS Background pattern -->
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
    </div>

    <!-- Main Content Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-10">
        <?php if (isset($_GET['error']) && $_GET['error'] === 'incomplete'): ?>
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center shadow-sm">
            <svg class="h-5 w-5 mr-2 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <p class="text-sm font-bold">
                Cannot publish profile: Missing required information. Please edit your profile to add your service category, location, and phone number.
            </p>
        </div>
        <?php endif; ?>

        <div class="bg-white shadow-xl rounded-xl mt-8">
            <div class="grid grid-cols-1 md:grid-cols-3 items-start">
                
                <!-- Left Column (Sticky Sidebar) -->
                <div class="md:col-span-1 border-r border-gray-100 bg-white p-6 md:p-8 flex flex-col pt-0 sm:rounded-l-xl">
                    <div class="relative w-full flex justify-center md:justify-start">
                        <div class="relative w-32 h-32 mb-6 rounded-full ring-4 ring-white shadow-lg bg-white -mt-16 overflow-hidden">
                            <img src="<?= get_profile_picture_url($user['profile_picture'] ?? 'default.png', $user['first_name'], $user['last_name']) ?>" 
                                 alt="<?= htmlspecialchars($user['first_name']) ?>'s profile picture" class="object-cover w-full h-full">
                        </div>
                        
                        <!-- Favorite Heart -->
                        <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'homeowner' && $user['role'] === 'craftsman'): ?>
                        <div class="absolute right-0 top-0 pt-2 pr-2">
                            <button type="button" onclick="toggleFavorite(<?= $user['id'] ?>, this)" class="p-2.5 rounded-full z-10 bg-white shadow-sm border <?= $isFavorite ? 'border-pink-200 text-pink-500 hover:bg-pink-50' : 'border-gray-200 text-gray-300 hover:text-pink-400 hover:border-pink-200' ?> transition-colors duration-200 outline-none focus:outline-none" title="<?= $isFavorite ? 'Remove from favorites' : 'Save to favorites' ?>">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" <?= $isFavorite ? 'viewBox="0 0 20 20" fill="currentColor"' : 'fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"' ?>>
                                    <path <?= $isFavorite ? 'fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"' : 'stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"' ?> />
                                </svg>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="text-center md:text-left mb-6">
                        <h1 class="text-2xl font-extrabold text-gray-900 flex items-center justify-center md:justify-start">
                            <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                            <?php if ($user['role'] === 'craftsman' && !empty($craftsmanDetails['is_verified'])): ?>
                            <svg class="ml-2 h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" title="Verified Professional">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812 3.066 3.066 0 00.723 1.745 3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?>
                        </h1>
                        <p class="text-sm font-semibold uppercase tracking-widest text-<?= $user['role'] === 'craftsman' ? 'indigo' : 'gray' ?>-600 mt-1">
                            <?= $user['role'] === 'craftsman' ? htmlspecialchars($craftsmanDetails['service_category'] ?? 'Professional') : 'Homeowner' ?>
                        </p>
                        <?php if (!empty($user['username'])): ?>
                        <p class="text-sm text-gray-400 mt-0.5 font-medium">@<?= htmlspecialchars($user['username']) ?></p>
                        <?php endif; ?>
                        <p class="text-sm text-gray-500 mt-1">Member since <?= date('Y', strtotime($user['created_at'])) ?></p>
                        <?php if (!empty($user['wilaya'])): ?>
                        <div class="mt-3 flex items-center justify-center md:justify-start text-sm text-gray-600">
                            <svg class="h-4 w-4 mr-1.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <?= htmlspecialchars($user['wilaya']) ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($user['role'] === 'craftsman'): ?>
                        <div class="mt-3 flex items-center justify-center md:justify-start">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <svg class="h-4 w-4 <?= $i <= round($rating['avg_rating']) ? 'text-yellow-400' : 'text-gray-300' ?>" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <?php endfor; ?>
                            <span class="ml-1.5 text-xs font-bold text-gray-700"><?= $rating['avg_rating'] ?></span>
                            <span class="ml-1 text-xs text-gray-500">(<?= $rating['total_reviews'] ?> <?= $rating['total_reviews'] === 1 ? 'review' : 'reviews' ?>)</span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($user['role'] === 'craftsman'): ?>
                    <div class="mb-6 flex-grow text-center md:text-left">
                        <div class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-50 border border-indigo-100 text-sm font-medium text-indigo-800">
                            Hourly Rate: <span class="ml-2 font-bold text-lg text-indigo-600">$<?= number_format($craftsmanDetails['hourly_rate'] ?? 0, 2) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="mt-auto space-y-3 pt-6 border-t border-gray-100">
                        <?php if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $user['id']): ?>
                            <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="<?= APP_URL ?>/messages/conversation?with=<?= $user['id'] ?>" class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                                <svg class="mr-2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                Send Message
                            </a>
                            <?php endif; ?>
                            <?php if ($user['role'] === 'craftsman'): ?>
                            <a href="<?= APP_URL ?>/bookings/create?craftsman_id=<?= $user['id'] ?>" class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                                Request Booking
                            </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="<?= APP_URL ?>/profile/edit" class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-indigo-200 shadow-sm text-sm font-medium rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                                <svg class="mr-2 h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Edit Profile
                            </a>

                            <?php if ($user['role'] === 'craftsman'): ?>
                            <button onclick="openLaunchModal()" class="w-full mt-4 flex items-center justify-center px-4 py-3 text-sm font-medium text-white <?= !empty($craftsmanDetails['is_published']) ? 'bg-green-600 hover:bg-green-700' : 'bg-indigo-600 hover:bg-indigo-700' ?> rounded-lg transition duration-150 shadow-sm">
                                <?php if (!empty($craftsmanDetails['is_published'])): ?>
                                    <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Card is Live
                                <?php else: ?>
                                    <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                                    </svg>
                                    Launch Card
                                <?php endif; ?>
                            </button>
                            <?php endif; ?>

                            <form action="<?= APP_URL ?>/logout" method="POST" class="w-full mt-4">
                                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-bold rounded-lg text-red-600 hover:text-red-700 hover:bg-red-50 focus:outline-none transition duration-150">
                                    Logout
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                </div>

                <!-- Right Column (Main Content) -->
                <div class="md:col-span-2 p-6 md:p-8 bg-gray-50/50 sm:rounded-r-xl">
                    
                    <?php if ($user['role'] === 'craftsman'): ?>
                    <!-- Bio Section -->
                    <div class="mb-10">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="mr-2 h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            About Me
                        </h2>
                        <div class="prose prose-sm sm:prose-base text-gray-600 leading-relaxed max-w-none">
                            <?php if (!empty($craftsmanDetails['bio'])): ?>
                                <?= nl2br(htmlspecialchars($craftsmanDetails['bio'])) ?>
                            <?php else: ?>
                                <p class="italic text-gray-400">This craftsman hasn't added a bio yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Portfolio Section -->
                    <div class="mb-10">
                        <?php 
                            $images = !empty($craftsmanDetails['portfolio_images']) ? json_decode($craftsmanDetails['portfolio_images'], true) : [];
                        ?>
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="mr-2 h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Portfolio
                            <?php if (!empty($images)): ?>
                            <span class="ml-2 text-sm font-medium text-gray-400">(<?= count($images) ?> photos)</span>
                            <?php endif; ?>
                        </h2>
                        <?php if (empty($images)): ?>
                            <div class="bg-gray-100 rounded-lg p-8 text-center border-2 border-dashed border-gray-200">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-sm text-gray-500 font-medium">No portfolio images uploaded yet.</p>
                            </div>
                        <?php else: ?>
                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                                <?php foreach ($images as $idx => $img): ?>
                                    <div class="relative aspect-w-1 aspect-h-1 rounded-xl overflow-hidden bg-gray-100 group cursor-pointer shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200" 
                                         onclick="openLightbox(<?= $idx ?>)">
                                        <img src="<?= APP_URL ?>/uploads/portfolio/<?= htmlspecialchars($img) ?>" 
                                             alt="Portfolio piece" 
                                             class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-200 flex items-center justify-center pointer-events-none">
                                            <svg class="h-8 w-8 text-white opacity-0 group-hover:opacity-80 transition-opacity duration-200 drop-shadow-lg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                            </svg>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <div class="mb-10 py-10">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            Homeowner Overview
                        </h2>
                        <div class="bg-white rounded-lg p-5 border border-gray-100 shadow-sm">
                            <p class="text-gray-500 italic">This user is an active homeowner using CraftConnect.</p>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['id'] && $user['role'] === 'craftsman'): ?>
<!-- Launch Card Modal -->
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
                <div class="bg-white overflow-hidden shadow-md rounded-xl flex flex-col border border-gray-100 mx-auto w-full">
                    <div class="p-6 flex-grow relative overflow-hidden">
                        <!-- Tiny accent banner -->
                        <div class="h-1 bg-indigo-500 absolute top-0 inset-x-0"></div>
                        
                        <div class="flex items-center space-x-4 mb-4 mt-2">
                            <div class="relative">
                                <img class="h-16 w-16 rounded-full object-cover border-2 <?= !empty($craftsmanDetails['is_verified']) ? 'border-green-300' : 'border-gray-200' ?> shadow-sm" 
                                     src="<?= get_profile_picture_url($user['profile_picture'] ?? 'default.png', $user['first_name'], $user['last_name']) ?>" 
                                     alt="<?= htmlspecialchars($user['first_name']) ?>">
                                <?php if (!empty($craftsmanDetails['is_verified'])): ?>
                                <div class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5 shadow-sm">
                                    <svg class="h-4 w-4 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812 3.066 3.066 0 00.723 1.745 3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 tracking-tight">
                                    <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>
                                </h2>
                                <p class="text-xs font-bold uppercase tracking-wider text-indigo-600 mt-0.5">
                                    <?= htmlspecialchars($craftsmanDetails['service_category'] ?? 'Professional') ?>
                                </p>
                            </div>
                        </div>

                        <p class="text-sm text-gray-500 line-clamp-3 mb-4 leading-relaxed">
                            <?= !empty($craftsmanDetails['bio']) ? htmlspecialchars($craftsmanDetails['bio']) : 'Your bio will appear here. Edit your profile to add one!' ?>
                        </p>

                        <div class="flex items-center justify-between text-sm py-3 border-t border-gray-100 bg-gray-50 -mx-6 px-6 -mb-6">
                            <div>
                                <span class="text-gray-500 text-xs font-medium uppercase tracking-wider">Hourly Rate</span>
                                <p class="font-bold text-gray-900 text-base">$<?= number_format($craftsmanDetails['hourly_rate'] ?? 0, 2) ?></p>
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
                    <a href="<?= APP_URL ?>/profile/edit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-bold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 relative overflow-hidden group">
                        Complete Profile First
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Portfolio Lightbox Modal -->
<?php if ($user['role'] === 'craftsman' && !empty($images)): ?>
<div id="portfolio-lightbox" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-90 transition-opacity" onclick="closeLightbox()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <!-- Close Button -->
        <button onclick="closeLightbox()" class="absolute top-4 right-4 z-10 text-white hover:text-gray-300 transition-colors p-2">
            <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <!-- Counter -->
        <div class="absolute top-4 left-4 z-10 text-white text-sm font-medium bg-black bg-opacity-50 px-3 py-1.5 rounded-full">
            <span id="lightbox-counter"></span>
        </div>
        <!-- Prev Button -->
        <button onclick="lightboxPrev()" class="absolute left-4 z-10 text-white hover:text-gray-300 transition-colors p-3 rounded-full bg-black bg-opacity-30 hover:bg-opacity-50">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <!-- Image -->
        <img id="lightbox-image" src="" alt="Portfolio" class="max-h-[85vh] max-w-[90vw] object-contain rounded-lg shadow-2xl">
        <!-- Next Button -->
        <button onclick="lightboxNext()" class="absolute right-4 z-10 text-white hover:text-gray-300 transition-colors p-3 rounded-full bg-black bg-opacity-30 hover:bg-opacity-50">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>
</div>
<?php endif; ?>

    <!-- Reviews Section (Craftsman only) -->
    <?php if ($user['role'] === 'craftsman'): ?>
    <div class="max-w-4xl mx-auto mt-10 px-4">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Reviews</h2>
                <p class="text-sm text-gray-500"><?= $rating['total_reviews'] ?> review<?= $rating['total_reviews'] !== 1 ? 's' : '' ?> · <?= $rating['avg_rating'] ?> average</p>
            </div>
        </div>

        <?php if (!empty($reviews)): ?>
        <div class="space-y-4">
            <?php foreach ($reviews as $review): ?>
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
                <div class="flex items-start space-x-4">
                    <img class="h-10 w-10 rounded-full object-cover" 
                         src="<?= get_profile_picture_url($review['profile_picture'] ?? 'default.png', $review['first_name'], $review['last_name']) ?>" 
                         alt="<?= htmlspecialchars($review['first_name']) ?>">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($review['first_name'] . ' ' . $review['last_name']) ?></p>
                            <p class="text-xs text-gray-400"><?= date('M d, Y', strtotime($review['created_at'])) ?></p>
                        </div>
                        <div class="flex items-center mt-1">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <svg class="h-4 w-4 <?= $i <= $review['star_rating'] ? 'text-yellow-400' : 'text-gray-300' ?>" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <?php endfor; ?>
                        </div>
                        <?php if (!empty($review['comment'])): ?>
                        <p class="mt-2 text-sm text-gray-600"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-lg border-2 border-dashed border-gray-200 p-10 text-center">
            <svg class="mx-auto h-10 w-10 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
            </svg>
            <h3 class="mt-3 text-sm font-medium text-gray-900">No reviews yet</h3>
            <p class="mt-1 text-sm text-gray-500">Be the first to review this craftsman!</p>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div>

<script>
function openLaunchModal() {
    document.getElementById('launchModal').classList.remove('hidden');
    // Prevent scrolling behind modal
    document.body.style.overflow = 'hidden';
}

function closeLaunchModal() {
    document.getElementById('launchModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function confirmLaunch() {
    document.getElementById('publishForm').submit();
}

async function toggleFavorite(craftsmanId, btnElement) {
    const icon = btnElement.querySelector('svg');
    const isCurrentlyFavorite = btnElement.classList.contains('text-pink-500');
    
    // Optimistic UI update
    if (isCurrentlyFavorite) {
        btnElement.classList.remove('border-pink-200', 'text-pink-500', 'hover:bg-pink-50');
        btnElement.classList.add('border-gray-200', 'text-gray-300', 'hover:text-pink-400', 'hover:border-pink-200');
        btnElement.title = 'Save to favorites';
        icon.removeAttribute('fill');
        icon.setAttribute('fill', 'none');
        icon.setAttribute('stroke', 'currentColor');
        icon.setAttribute('stroke-width', '2');
        icon.setAttribute('viewBox', '0 0 24 24');
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />';
    } else {
        btnElement.classList.remove('border-gray-200', 'text-gray-300', 'hover:text-pink-400', 'hover:border-pink-200');
        btnElement.classList.add('border-pink-200', 'text-pink-500', 'hover:bg-pink-50');
        btnElement.title = 'Remove from favorites';
        icon.removeAttribute('stroke');
        icon.removeAttribute('stroke-width');
        icon.setAttribute('fill', 'currentColor');
        icon.setAttribute('viewBox', '0 0 20 20');
        icon.innerHTML = '<path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />';
    }

    try {
        const response = await fetch('<?= APP_URL ?>/favorites/toggle', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ craftsman_id: craftsmanId })
        });
        
        const data = await response.json();
        if (!data.success) {
            // Revert on failure
            alert(data.message || 'Failed to update favorites.');
            window.location.reload();
        }
    } catch (e) {
        console.error('Error toggling favorite:', e);
        // Revert on failure
        window.location.reload();
    }
}

// Portfolio Lightbox
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
</script>