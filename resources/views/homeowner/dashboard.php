<!-- Homeowner Dashboard -->
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">
                Welcome back, <?= htmlspecialchars($_SESSION['first_name'] ?? 'Homeowner') ?>!
            </h1>
            <p class="mt-1 text-sm text-gray-500">Here's an overview of your activity on Crafts.</p>
        </div>

        <!-- Success Messages -->
        <?php if (isset($_GET['success'])): ?>
        <div class="rounded-md bg-green-50 p-4 mb-6 border border-green-200">
            <div class="flex">
                <svg class="h-5 w-5 text-green-400 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="text-sm font-medium text-green-800">
                    <?php 
                    switch($_GET['success']) {
                        case 'job_posted': echo 'Your job has been posted successfully! Craftsmen can now submit quotes.'; break;
                        case 'counter_accepted': echo 'Counter-offer accepted! The job is now in progress.'; break;
                        case 'counter_cancelled': echo 'Booking has been cancelled.'; break;
                        case 'job_completed': echo 'Job confirmed as complete! You can now leave a review.'; break;
                        case 'booking_requested': echo 'Booking request sent successfully!'; break;
                        default: echo 'Action completed successfully.';
                    }
                    ?>
                </p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Main Content Layout -->
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- Sidebar Navigation -->
            <aside class="w-full lg:w-64 flex-shrink-0">
                <nav class="space-y-1 bg-white rounded-xl shadow-sm border border-gray-100 p-3" id="dashboard-tabs">
                    <button onclick="switchTab('overview')" data-tab="overview"
                        class="group tab-btn flex items-center w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        Overview
                    </button>
                    <button onclick="switchTab('jobs')" data-tab="jobs"
                        class="group tab-btn flex items-center w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        My Jobs
                    </button>
                    <button onclick="switchTab('quotes')" data-tab="quotes"
                        class="group tab-btn flex items-center w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                        Incoming Quotes
                        <?php if ($pendingQuotesCount > 0): ?>
                        <span class="ml-auto bg-red-100 text-red-600 py-0.5 px-2 rounded-full text-xs font-bold"><?= $pendingQuotesCount ?></span>
                        <?php endif; ?>
                    </button>
                    <button onclick="switchTab('bookings')" data-tab="bookings"
                        class="group tab-btn flex items-center w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        Bookings
                    </button>
                    <button onclick="switchTab('favorites')" data-tab="favorites"
                        class="group tab-btn flex items-center w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200">
                        <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        Saved Craftsmen
                        <?php if (!empty($favorites)): ?>
                        <span class="ml-auto bg-pink-100 text-pink-600 py-0.5 px-2 rounded-full text-xs font-bold"><?= count($favorites) ?></span>
                        <?php endif; ?>
                    </button>
                </nav>

                <!-- Sidebar Action Buttons -->
                <div class="mt-4 space-y-2">
                    <a href="<?= APP_URL ?>/jobs/create" class="flex items-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Post a New Job
                    </a>
                    <a href="<?= APP_URL ?>/search" class="flex items-center w-full px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-50 border border-indigo-100 hover:bg-indigo-100 rounded-lg shadow-sm transition">
                        <svg class="mr-2 h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg> Find Craftsmen
                    </a>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 min-w-0">

                <!-- Tab: Overview -->
                <div id="tab-overview" class="tab-content" style="display:none">
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 mb-8">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col justify-between">
                            <svg class="h-6 w-6 text-indigo-500 mb-3 bg-indigo-50 rounded p-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Active Jobs</p>
                            <p class="text-2xl font-bold text-gray-900"><?= $activeJobsCount ?></p>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col justify-between">
                            <svg class="h-6 w-6 text-yellow-500 mb-3 bg-yellow-50 rounded p-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Quotes</p>
                            <p class="text-2xl font-bold text-gray-900"><?= $pendingQuotesCount ?></p>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col justify-between">
                            <svg class="h-6 w-6 text-green-500 mb-3 bg-green-50 rounded p-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Completed</p>
                            <p class="text-2xl font-bold text-gray-900"><?= $completedJobsCount ?></p>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col justify-between">
                            <svg class="h-6 w-6 text-pink-500 mb-3 bg-pink-50 rounded p-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Saved Pros</p>
                            <p class="text-2xl font-bold text-gray-900"><?= count($favorites) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Tab: My Jobs -->
                <div id="tab-jobs" class="tab-content" style="display:none">
                    <?php if (!empty($jobs)): ?>
                    <div class="space-y-3">
                        <?php foreach ($jobs as $job): ?>
                        <a href="<?= APP_URL ?>/jobs/<?= $job['id'] ?>" class="group block bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:border-indigo-300 transition-all duration-200 p-5">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0 pr-4">
                                    <h3 class="text-base font-bold text-gray-900 group-hover:text-indigo-600 transition-colors truncate"><?= htmlspecialchars($job['title']) ?></h3>
                                    <div class="mt-2 flex items-center flex-wrap gap-3 text-xs text-gray-500">
                                        <?php $jobCatStyles = get_category_classes($job['service_category']); ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md <?= $jobCatStyles['badge'] ?> font-medium">
                                            <?= htmlspecialchars($job['service_category']) ?>
                                        </span>
                                        <?php if (!empty($job['address'])): ?>
                                        <span class="flex items-center">
                                            <svg class="h-3.5 w-3.5 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                            <?= htmlspecialchars(preg_replace('/^\d{2}\s-\s/', '', $job['address'])) ?>
                                        </span>
                                        <?php endif; ?>
                                        <span class="flex items-center">
                                            <svg class="h-3.5 w-3.5 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <?= date('M d, Y', strtotime($job['created_at'])) ?>
                                        </span>
                                        <?php if (!empty($job['budget_range'])): ?>
                                        <span class="flex items-center font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">
                                            <svg class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <?= htmlspecialchars($job['budget_range']) ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex items-center flex-shrink-0 ml-4">
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full 
                                        <?= $job['status'] === 'open' ? 'bg-green-100 text-green-800' : ($job['status'] === 'assigned' ? 'bg-yellow-100 text-yellow-800' : ($job['status'] === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) ?>">
                                        <?= ucfirst($job['status']) ?>
                                    </span>
                                    <svg class="ml-3 h-5 w-5 text-gray-400 group-hover:text-indigo-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="bg-white rounded-lg border-2 border-dashed border-gray-200 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-3 text-sm font-medium text-gray-900">No jobs posted yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by posting your first job request.</p>
                        <a href="<?= APP_URL ?>/jobs/create" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                            Post your first job
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Tab: Incoming Quotes -->
                <div id="tab-quotes" class="tab-content" style="display:none">
                    <?php if (!empty($allQuotes)): ?>
                    <div class="space-y-3">
                        <?php foreach ($allQuotes as $quote): ?>
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:border-indigo-100 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0 pr-4">
                                    <div class="flex items-center gap-2 mb-1">
                                        <a href="<?= APP_URL ?>/jobs/<?= $quote['job_posting_id'] ?>" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 truncate"><?= htmlspecialchars($quote['job_title']) ?></a>
                                        <span class="text-gray-300">•</span>
                                        <p class="text-xs text-gray-400"><?= date('M d, Y', strtotime($quote['quote_created_at'])) ?></p>
                                    </div>
                                    <div class="flex items-baseline gap-2 mt-2">
                                        <span class="text-lg font-bold text-gray-900 flex items-center gap-1">
                                            <?= htmlspecialchars($quote['craftsman_first_name'] . ' ' . $quote['craftsman_last_name']) ?>
                                            <?php if (!empty($quote['craftsman_is_verified'])): ?>
                                            <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" title="Verified Craftsman">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            <?php endif; ?>
                                        </span>
                                        <span class="text-sm text-gray-500">quoted</span>
                                        <span class="text-lg font-extrabold text-emerald-700 bg-emerald-50 px-2 rounded-md"><?= number_format($quote['quoted_price'], 2) ?> DZD</span>
                                    </div>
                                    <?php if (!empty($quote['cover_message'])): ?>
                                    <div class="mt-3 bg-gray-50 rounded-lg p-3 border border-gray-100">
                                        <p class="text-sm text-gray-600 italic line-clamp-2">"<?= htmlspecialchars($quote['cover_message']) ?>"</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-shrink-0 ml-4 border-l border-gray-100 pl-4">
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full 
                                        <?= $quote['quote_status'] === 'accepted' ? 'bg-green-100 text-green-800' : ($quote['quote_status'] === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                                        <?= ucfirst($quote['quote_status']) ?>
                                    </span>
                                </div>
                            </div>
                            <?php if ($quote['quote_status'] === 'pending'): ?>
                            <div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap items-center gap-3">
                                <form id="accept-quote-<?= $quote['quote_id'] ?>" action="<?= APP_URL ?>/jobs/accept-quote" method="POST" class="m-0">
                                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                                    <input type="hidden" name="quote_id" value="<?= $quote['quote_id'] ?>">
                                    <button type="button" onclick="showConfirmModal('accept-quote-<?= $quote['quote_id'] ?>', 'Accept this quote?', 'This will accept <?= htmlspecialchars($quote['craftsman_first_name']) ?>\'s quote of <?= number_format($quote['quoted_price'], 2) ?> DZD and reject all other quotes for this job.', 'accept')" class="inline-flex items-center px-4 py-2 text-sm font-bold rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition duration-150">
                                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Accept Quote
                                    </button>
                                </form>
                                <form id="decline-quote-<?= $quote['quote_id'] ?>" action="<?= APP_URL ?>/jobs/reject-quote" method="POST" class="m-0">
                                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                                    <input type="hidden" name="quote_id" value="<?= $quote['quote_id'] ?>">
                                    <button type="button" onclick="showConfirmModal('decline-quote-<?= $quote['quote_id'] ?>', 'Decline this quote?', 'Are you sure you want to decline <?= htmlspecialchars($quote['craftsman_first_name']) ?>\'s quote? This action cannot be undone.', 'decline')" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 hover:text-red-600 transition duration-150">
                                        Decline
                                    </button>
                                </form>
                                <a href="<?= APP_URL ?>/profile/<?= $quote['craftsman_username'] ?>" class="ml-auto inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition duration-150">
                                    View Profile
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="bg-white rounded-lg border-2 border-dashed border-gray-200 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <h3 class="mt-3 text-sm font-medium text-gray-900">No quotes yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Once craftsmen see your jobs, they'll submit their quotes here.</p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Tab: Bookings -->
                <div id="tab-bookings" class="tab-content" style="display:none">
                    <?php if (!empty($bookings)): ?>
                    <div class="space-y-3">
                        <?php foreach ($bookings as $booking): ?>
                        <div class="bg-white rounded-lg shadow-sm border <?= $booking['status'] === 'counter_offered' ? 'border-orange-200' : ($booking['status'] === 'pending_completion' ? 'border-purple-200' : ($booking['status'] === 'in_progress' ? 'border-blue-200' : 'border-gray-100')) ?> p-5">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-base font-bold text-gray-900 flex items-center gap-1">
                                        Booking with <?= htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']) ?>
                                        <?php if (!empty($booking['is_verified'])): ?>
                                        <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" title="Verified Craftsman">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <?php endif; ?>
                                    </p>
                                    <p class="mt-1 text-sm text-gray-600 line-clamp-2"><?= htmlspecialchars($booking['description']) ?></p>
                                    <div class="mt-2 flex items-center flex-wrap gap-2 text-xs text-gray-500">
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                            <?= htmlspecialchars(preg_replace('/^\d{2}\s-\s/', '', $booking['address'] ?? '')) ?>
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                            <?= date('M d, Y', strtotime($booking['scheduled_date'])) ?>
                                        </span>
                                        <?php if (!empty($booking['quoted_price'])): ?>
                                        <span class="flex items-center font-semibold text-green-600">
                                            <?= number_format($booking['quoted_price'], 2) ?> DZD
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <span class="ml-3 px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full 
                                    <?php
                                    switch($booking['status']) {
                                        case 'requested': echo 'bg-yellow-100 text-yellow-800'; break;
                                        case 'counter_offered': echo 'bg-orange-100 text-orange-800'; break;
                                        case 'in_progress': echo 'bg-blue-100 text-blue-800'; break;
                                        case 'pending_completion': echo 'bg-purple-100 text-purple-800'; break;
                                        case 'completed': echo 'bg-green-100 text-green-800'; break;
                                        case 'cancelled': echo 'bg-gray-100 text-gray-800'; break;
                                        default: echo 'bg-gray-100 text-gray-800';
                                    }
                                    ?>">
                                    <?= ucfirst(str_replace('_', ' ', $booking['status'])) ?>
                                </span>
                            </div>

                            <?php if ($booking['status'] === 'counter_offered'): ?>
                            <div class="mt-3 pt-3 border-t border-orange-100 bg-orange-50 -mx-5 -mb-5 px-5 py-3 rounded-b-lg">
                                <p class="text-xs font-semibold text-orange-700 mb-1">📤 Counter-offer received</p>
                                <div class="flex items-center space-x-2 mt-2">
                                    <form id="accept-counter-<?= $booking['id'] ?>" action="<?= APP_URL ?>/bookings/accept-counter" method="POST" class="inline">
                                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                        <button type="button" onclick="showConfirmModal('accept-counter-<?= $booking['id'] ?>', 'Accept Counter-Offer?', 'This will confirm the new price and date.', 'accept')" class="px-2 py-1 text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition duration-150">Accept</button>
                                    </form>
                                    <form id="cancel-counter-<?= $booking['id'] ?>" action="<?= APP_URL ?>/bookings/cancel-counter" method="POST" class="inline">
                                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                        <button type="button" onclick="showConfirmModal('cancel-counter-<?= $booking['id'] ?>', 'Decline Counter?', 'This will cancel the booking request.', 'decline')" class="px-2 py-1 text-xs font-medium rounded-md text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition duration-150">Decline</button>
                                    </form>
                                </div>
                            </div>

                            <?php elseif ($booking['status'] === 'pending_completion'): ?>
                            <div class="mt-3 pt-3 border-t border-purple-100 bg-purple-50 -mx-5 -mb-5 px-5 py-3 rounded-b-lg">
                                <p class="text-sm font-semibold text-purple-800">⏳ The craftsman has marked this job as complete. Please confirm.</p>
                                <div class="flex items-center space-x-2 mt-2">
                                    <form id="confirm-complete-<?= $booking['id'] ?>" action="<?= APP_URL ?>/bookings/confirm-completion" method="POST" class="inline">
                                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                        <button type="button" onclick="showConfirmModal('confirm-complete-<?= $booking['id'] ?>', 'Confirm Job Complete?', 'This confirms the work is done. You will be able to leave a review.', 'accept')" class="px-3 py-1.5 text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition duration-150">✅ Confirm Completion</button>
                                    </form>
                                    <a href="<?= APP_URL ?>/profile/<?= $booking['username'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition duration-150">View Craftsman</a>
                                </div>
                            </div>
                            <?php elseif ($booking['status'] === 'completed' && empty($booking['has_reviewed'])): ?>
                            <div class="mt-3 pt-3 border-t border-gray-100 flex items-center space-x-2">
                                <a href="<?= APP_URL ?>/reviews/create?booking_id=<?= $booking['id'] ?>" class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 transition duration-150">
                                    Write a Review
                                </a>
                                <a href="<?= APP_URL ?>/profile/<?= $booking['username'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition duration-150">View Craftsman</a>
                            </div>
                            <?php elseif ($booking['status'] === 'completed' && !empty($booking['has_reviewed'])): ?>
                            <div class="mt-3 pt-3 border-t border-gray-100 flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-1 bg-gray-50 text-xs font-medium text-gray-500 rounded border border-gray-200">
                                    <svg class="h-3 w-3 mr-1 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Reviewed
                                </span>
                                <a href="<?= APP_URL ?>/profile/<?= $booking['username'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition duration-150">View Craftsman</a>
                            </div>
                            <?php elseif ($booking['status'] === 'in_progress'): ?>
                            <div class="mt-3 pt-3 border-t border-blue-100 bg-blue-50 -mx-5 -mb-5 px-5 py-3 rounded-b-lg">
                                <p class="text-xs font-semibold text-blue-700">🔧 Job is in progress</p>
                            </div>
                            <?php else: ?>
                            <div class="mt-3 pt-3 border-t border-gray-100 flex items-center space-x-2">
                                <a href="<?= APP_URL ?>/profile/<?= $booking['username'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition duration-150">View Craftsman</a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="bg-white rounded-lg border-2 border-dashed border-gray-200 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-3 text-sm font-medium text-gray-900">No bookings yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Find a craftsman and send a direct booking request.</p>
                        <a href="<?= APP_URL ?>/search" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                            Find Craftsmen
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Tab: Favorites -->
                <div id="tab-favorites" class="tab-content" style="display:none">
                    <?php if (!empty($favorites)): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($favorites as $favorite): ?>
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md hover:border-pink-200 transition-all duration-200 p-5 flex flex-col justify-between relative overflow-hidden group">
                            <!-- Absolute heart icon fading in background -->
                            <svg class="absolute -right-4 -bottom-4 h-24 w-24 text-pink-50 opacity-10 group-hover:scale-110 transition-transform duration-300 pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                            </svg>

                            <div class="flex items-start space-x-4 relative z-10">
                                    <img src="<?= get_profile_picture_url($favorite['profile_picture'] ?? 'default.png', $favorite['first_name'], $favorite['last_name']) ?>" 
                                         alt="<?= htmlspecialchars($favorite['first_name']) ?>" 
                                         class="h-12 w-12 rounded-full object-cover shadow-sm border border-gray-100">
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 flex items-center gap-1">
                                        <?= htmlspecialchars($favorite['first_name'] . ' ' . $favorite['last_name']) ?>
                                        <?php if (!empty($favorite['is_verified'])): ?>
                                        <svg class="h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" title="Verified Craftsman">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <?php endif; ?>
                                    </h3>
                                    <?php $favCatStyles = get_category_classes($favorite['service_category'] ?? 'General Handyman'); ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold <?= $favCatStyles['badge'] ?>">
                                        <?= htmlspecialchars($favorite['service_category'] ?? 'Professional') ?>
                                    </span>
                                    
                                    <div class="mt-1 flex items-center text-xs text-gray-500 space-x-3">
                                        <?php if (!empty($favorite['wilaya'])): ?>
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                            <?= htmlspecialchars($favorite['wilaya']) ?>
                                        </span>
                                        <?php endif; ?>
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 mr-1 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            <?= $favorite['rating_score'] > 0 ? htmlspecialchars($favorite['rating_score']) . ' (' . intval($favorite['reviews_count']) . ')' : 'No ratings' ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between relative z-10">
                                <span class="text-sm font-bold text-gray-900">
                                    DZD <?= number_format($favorite['hourly_rate'] ?? 0, 2) ?><span class="text-xs text-gray-500 font-normal">/hr</span>
                                </span>
                                <div class="flex space-x-2">
                                    <button type="button" onclick="confirmRemoveFavorite(<?= $favorite['id'] ?>)" class="p-1.5 text-pink-500 hover:text-pink-700 bg-pink-50 hover:bg-pink-100 rounded transition duration-150" title="Remove from saved">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <a href="<?= APP_URL ?>/profile/<?= $favorite['username'] ?>" class="px-3 py-1.5 text-xs font-medium rounded text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition duration-150">
                                        View Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="bg-white rounded-lg border-2 border-dashed border-gray-200 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-pink-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <h3 class="mt-3 text-sm font-medium text-gray-900">No saved craftsmen</h3>
                        <p class="mt-1 text-sm text-gray-500">Save your favorite craftsmen here for quick access later.</p>
                        <a href="<?= APP_URL ?>/search" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-pink-700 bg-pink-100 hover:bg-pink-200 transition duration-150">
                            Find Craftsmen to Save
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

            </div>

            </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirm-modal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" onclick="hideConfirmModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div id="modal-icon-accept" class="hidden flex-shrink-0 bg-green-100 rounded-full p-2 mr-3">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div id="modal-icon-decline" class="hidden flex-shrink-0 bg-red-100 rounded-full p-2 mr-3">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h3 id="modal-title" class="text-lg font-bold text-gray-900"></h3>
                </div>
                <p id="modal-message" class="text-sm text-gray-600 mb-6"></p>
                <div class="flex justify-end space-x-3">
                    <button onclick="hideConfirmModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-150">Cancel</button>
                    <button id="modal-confirm-btn" onclick="confirmAction()" class="px-4 py-2 text-sm font-medium text-white rounded-lg transition duration-150">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tab Switching + Confirmation Modal Script -->
<script>
var pendingFormId = null;

function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(function(el) {
        el.style.display = 'none';
        el.classList.add('hidden');
    });
    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.classList.remove('border-indigo-500', 'text-indigo-600');
        btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
    });
    var target = document.getElementById('tab-' + tabName);
    if (target) {
        target.classList.remove('hidden');
        target.style.display = '';
    }
    var activeBtn = document.querySelector('[data-tab="' + tabName + '"]');
    if (activeBtn) {
        activeBtn.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        activeBtn.classList.add('border-indigo-500', 'text-indigo-600');
    }
    
    // Update URL hash without jumping the page
    if (history.replaceState) {
        history.replaceState(null, null, '#' + tabName);
    } else {
        window.location.hash = tabName;
    }
}

// Read hash IMMEDIATELY (before paint) to prevent tab flicker
(function() {
    var hash = window.location.hash.substring(1);
    hash = hash.split('?')[0];
    if (hash && document.getElementById('tab-' + hash)) {
        switchTab(hash);
    } else {
        // Default: show first tab
        switchTab('overview');
    }
})();

// Listen for hash changes when navigating via back/forward or typing in the address bar
window.addEventListener('hashchange', function() {
    var hash = window.location.hash.substring(1).split('?')[0];
    if (hash && document.getElementById('tab-' + hash)) {
        switchTab(hash);
    }
});

function showConfirmModal(formId, title, message, type) {
    pendingFormId = formId;
    document.getElementById('modal-title').textContent = title;
    document.getElementById('modal-message').textContent = message;

    var btn = document.getElementById('modal-confirm-btn');
    var iconAccept = document.getElementById('modal-icon-accept');
    var iconDecline = document.getElementById('modal-icon-decline');

    if (type === 'accept') {
        btn.className = 'px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition duration-150';
        btn.textContent = 'Yes, Accept';
        iconAccept.classList.remove('hidden');
        iconDecline.classList.add('hidden');
    } else {
        btn.className = 'px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition duration-150';
        btn.textContent = 'Yes, Decline';
        iconAccept.classList.add('hidden');
        iconDecline.classList.remove('hidden');
    }

    document.getElementById('confirm-modal').classList.remove('hidden');
}

function hideConfirmModal() {
    document.getElementById('confirm-modal').classList.add('hidden');
    pendingFormId = null;
}

function confirmAction() {
    if (pendingFormId) {
        document.getElementById(pendingFormId).submit();
    } else if (pendingFavoriteId) {
        removeFavorite(pendingFavoriteId);
        hideConfirmModal();
    }
}

    function acceptQuote() {
        if (currentQuoteFormId) {
            document.getElementById(currentQuoteFormId).submit();
        }
    }

    var pendingFavoriteId = null;

    function confirmRemoveFavorite(id) {
        pendingFavoriteId = id;
        pendingFormId = null;
        
        document.getElementById('modal-title').innerText = 'Remove from Favorites';
        document.getElementById('modal-message').innerText = 'Are you sure you want to remove this craftsman from your saved list?';
        
        document.getElementById('modal-icon-accept').classList.add('hidden');
        document.getElementById('modal-icon-decline').classList.remove('hidden');
        
        const confirmBtn = document.getElementById('modal-confirm-btn');
        confirmBtn.innerText = 'Remove';
        confirmBtn.className = 'px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition duration-150';
        
        document.getElementById('confirm-modal').classList.remove('hidden');
    }

    // New Javascript for removeFavorite
    async function removeFavorite(craftsmanId) {
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
            if (data.success) {
                // simple reload to update UI
                window.location.reload();
            } else {
                alert(data.message || 'Failed to remove favorite.');
            }
        } catch (e) {
            console.error('Error removing favorite:', e);
            alert('An error occurred. Please try again.');
        }
    }
</script>
