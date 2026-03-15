<!-- Craftsman Full Profile View -->
<div class="bg-gray-50 min-h-screen pb-12">
    <!-- Cover Banner -->
    <div class="h-48 bg-indigo-600 w-full relative object-cover bg-gradient-to-r from-indigo-700 to-indigo-500 shadow-inner">
        <!-- Abstract CSS Background pattern -->
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
    </div>

    <!-- Main Content Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-10">
        <div class="mb-4">
            <a href="<?= APP_URL ?>/search" class="text-sm font-medium text-white hover:text-indigo-100 transition-colors duration-200 drop-shadow-md">&larr; Back to Search</a>
        </div>

        <div class="bg-white shadow-xl rounded-xl overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-3">
                
                <!-- Left Column (Sticky Sidebar) -->
                <div class="md:col-span-1 border-r border-gray-100 bg-white p-6 md:p-8 flex flex-col pt-0 mt-[-3rem]">
                    <div class="relative w-32 h-32 mx-auto md:mx-0 mb-6 rounded-full ring-4 ring-white shadow-lg overflow-hidden bg-white">
                        <img src="<?= get_profile_picture_url($profile['profile_picture'] ?? 'default.png', $profile['first_name'], $profile['last_name']) ?>" 
                             alt="<?= htmlspecialchars($profile['first_name']) ?>'s profile picture" class="object-cover w-full h-full">
                    </div>

                    <div class="text-center md:text-left mb-6">
                        <h1 class="text-2xl font-extrabold text-gray-900 flex items-center justify-center md:justify-start">
                            <?= htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name']) ?>
                            <?php if ($profile['is_verified']): ?>
                            <svg class="ml-2 h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" title="Verified Professional">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812 3.066 3.066 0 00.723 1.745 3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <?php endif; ?>
                        </h1>
                        <?php $searchProfCatStyles = get_category_classes($profile['service_category']); ?>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wide <?= $searchProfCatStyles['badge'] ?>">
                                <?= htmlspecialchars($profile['service_category']) ?>
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Member since <?= date('Y', strtotime($profile['created_at'])) ?></p>
                    </div>

                    <div class="space-y-4 mb-8 flex-grow">
                        <div class="bg-indigo-50 rounded-lg p-4 text-center border border-indigo-100 border-dashed">
                            <span class="block text-sm font-medium text-indigo-800">Hourly Rate</span>
                            <span class="block text-3xl font-bold text-indigo-600"><?= number_format($profile['hourly_rate'], 2) ?> DZD</span>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Local Professional
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            100% Success Rate
                        </div>
                    </div>

                    <div class="mt-auto space-y-3">
                        <a href="<?= APP_URL ?>/messages/conversation?with=<?= $profile['user_id'] ?>" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            <svg class="mr-2 -ml-1 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Send Message
                        </a>
                        <button onclick="alert('Direct booking system coming in Step 7!')" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            <svg class="mr-2 -ml-1 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Request Booking
                        </button>
                    </div>
                </div>

                <!-- Right Column (Main Content) -->
                <div class="md:col-span-2 p-6 md:p-8 bg-gray-50/50">
                    <!-- Bio Section -->
                    <div class="mb-10">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="mr-2 h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            About Me
                        </h2>
                        <div class="prose prose-sm sm:prose-base text-gray-600 leading-relaxed max-w-none">
                            <?php if (!empty($profile['bio'])): ?>
                                <?= nl2br(htmlspecialchars($profile['bio'])) ?>
                            <?php else: ?>
                                <p class="italic text-gray-400">This craftsman hasn't added a bio yet, but their skills speak for themselves!</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Portfolio Section -->
                    <div class="mb-10">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="mr-2 h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Portfolio
                        </h2>
                        <?php 
                            $images = !empty($profile['portfolio_images']) ? json_decode($profile['portfolio_images'], true) : [];
                        ?>
                        <?php if (empty($images)): ?>
                            <div class="bg-gray-100 rounded-lg p-8 text-center border-2 border-dashed border-gray-200">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-sm text-gray-500 font-medium">No portfolio images uploaded yet.</p>
                            </div>
                        <?php else: ?>
                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                                <?php foreach ($images as $img): ?>
                                    <div class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden bg-gray-100 group">
                                        <img src="<?= APP_URL ?>/uploads/portfolio/<?= htmlspecialchars($img) ?>" alt="Portfolio piece" class="object-cover group-hover:opacity-75 transition-opacity duration-200">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Reviews Placeholder -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="mr-2 h-6 w-6 text-yellow-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            Recent Reviews
                        </h2>
                        
                        <!-- Static placeholder for now -->
                        <div class="bg-white rounded-lg p-5 border border-gray-100 shadow-sm">
                            <div class="flex items-center mb-2">
                                <div class="flex text-yellow-400">
                                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                                <span class="ml-2 text-sm font-medium text-gray-900">Great work, very professional</span>
                            </div>
                            <p class="text-xs text-gray-500 mb-2">By Sarah J. • 2 weeks ago</p>
                            <p class="text-sm text-gray-600">"Did an amazing job fixing our plumbing. Arrived on time and explained everything clearly. Would highly recommend!"</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
