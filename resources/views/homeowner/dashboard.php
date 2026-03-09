<!-- Homeowner Dashboard -->
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">
                Welcome back, <?= htmlspecialchars($_SESSION['first_name'] ?? 'Homeowner') ?>!
            </h1>
            <p class="mt-1 text-sm text-gray-500">Here's an overview of your activity on CraftConnect.</p>
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

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Active Jobs</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $activeJobsCount ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Pending Quotes</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $pendingQuotesCount ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Completed</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $completedJobsCount ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Saved Craftsmen</p>
                        <p class="text-2xl font-bold text-gray-900"><?= count($favorites) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Tabs + Sidebar -->
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8">

            <!-- Tabs Section -->
            <div>
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-6" id="dashboard-tabs">
                        <button onclick="switchTab('jobs')" data-tab="jobs"
                            class="tab-btn border-indigo-500 text-indigo-600 whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            My Jobs
                        </button>
                        <button onclick="switchTab('quotes')" data-tab="quotes"
                            class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            Incoming Quotes
                            <?php if ($pendingQuotesCount > 0): ?>
                            <span class="ml-1.5 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700"><?= $pendingQuotesCount ?></span>
                            <?php endif; ?>
                        </button>
                        <button onclick="switchTab('bookings')" data-tab="bookings"
                            class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            Bookings
                        </button>
                        <button onclick="switchTab('favorites')" data-tab="favorites"
                            class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            Saved Craftsmen
                            <?php if (!empty($favorites)): ?>
                            <span class="ml-1.5 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold bg-pink-100 text-pink-700"><?= count($favorites) ?></span>
                            <?php endif; ?>
                        </button>
                    </nav>
                </div>

                <!-- Tab: My Jobs -->
                <div id="tab-jobs" class="tab-content">
                    <?php if (!empty($jobs)): ?>
                    <div class="space-y-3">
                        <?php foreach ($jobs as $job): ?>
                        <a href="<?= APP_URL ?>/jobs/show?id=<?= $job['id'] ?>" class="block bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md hover:border-indigo-200 transition-all duration-200 p-5">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base font-bold text-gray-900 truncate"><?= htmlspecialchars($job['title']) ?></h3>
                                    <div class="mt-1 flex items-center flex-wrap gap-2 text-xs text-gray-500">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 text-gray-700 font-medium"><?= htmlspecialchars($job['service_category']) ?></span>
                                        <?php if (!empty($job['address'])): ?>
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                            <?= htmlspecialchars(preg_replace('/^\d{2}\s-\s/', '', $job['address'])) ?>
                                        </span>
                                        <?php endif; ?>
                                        <span><?= date('M d, Y', strtotime($job['created_at'])) ?></span>
                                    </div>
                                </div>
                                <span class="ml-3 px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full 
                                    <?= $job['status'] === 'open' ? 'bg-green-100 text-green-800' : ($job['status'] === 'assigned' ? 'bg-yellow-100 text-yellow-800' : ($job['status'] === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) ?>">
                                    <?= ucfirst($job['status']) ?>
                                </span>
                            </div>
                            <?php if (!empty($job['budget_range'])): ?>
                            <p class="mt-2 text-sm font-medium text-gray-700">Budget: <?= htmlspecialchars($job['budget_range']) ?></p>
                            <?php endif; ?>
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
                <div id="tab-quotes" class="tab-content hidden">
                    <?php if (!empty($allQuotes)): ?>
                    <div class="space-y-3">
                        <?php foreach ($allQuotes as $quote): ?>
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <a href="<?= APP_URL ?>/jobs/show?id=<?= $quote['job_posting_id'] ?>" class="text-base font-bold text-indigo-600 hover:text-indigo-800 truncate block"><?= htmlspecialchars($quote['job_title']) ?></a>
                                    <p class="mt-1 text-sm text-gray-600">
                                        <span class="font-medium text-gray-900"><?= htmlspecialchars($quote['craftsman_first_name'] . ' ' . $quote['craftsman_last_name']) ?></span>
                                        quoted <span class="font-bold text-green-700">$<?= number_format($quote['quoted_price'], 2) ?></span>
                                    </p>
                                    <?php if (!empty($quote['cover_message'])): ?>
                                    <p class="mt-2 text-sm text-gray-500 italic line-clamp-2">"<?= htmlspecialchars($quote['cover_message']) ?>"</p>
                                    <?php endif; ?>
                                    <p class="mt-1 text-xs text-gray-400"><?= date('M d, Y \a\t g:i A', strtotime($quote['quote_created_at'])) ?></p>
                                </div>
                                <span class="ml-3 px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full 
                                    <?= $quote['quote_status'] === 'accepted' ? 'bg-green-100 text-green-800' : ($quote['quote_status'] === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                                    <?= ucfirst($quote['quote_status']) ?>
                                </span>
                            </div>
                            <?php if ($quote['quote_status'] === 'pending'): ?>
                            <div class="mt-3 pt-3 border-t border-gray-100 flex items-center space-x-2">
                                <form id="accept-quote-<?= $quote['quote_id'] ?>" action="<?= APP_URL ?>/jobs/accept-quote" method="POST" class="inline">
                                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                                    <input type="hidden" name="quote_id" value="<?= $quote['quote_id'] ?>">
                                    <button type="button" onclick="showConfirmModal('accept-quote-<?= $quote['quote_id'] ?>', 'Accept this quote?', 'This will accept <?= htmlspecialchars($quote['craftsman_first_name']) ?>\'s quote of $<?= number_format($quote['quoted_price'], 2) ?> and reject all other quotes for this job.', 'accept')" class="px-3 py-1.5 text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition duration-150">Accept Quote</button>
                                </form>
                                <form id="decline-quote-<?= $quote['quote_id'] ?>" action="<?= APP_URL ?>/jobs/reject-quote" method="POST" class="inline">
                                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                                    <input type="hidden" name="quote_id" value="<?= $quote['quote_id'] ?>">
                                    <button type="button" onclick="showConfirmModal('decline-quote-<?= $quote['quote_id'] ?>', 'Decline this quote?', 'Are you sure you want to decline <?= htmlspecialchars($quote['craftsman_first_name']) ?>\'s quote? This action cannot be undone.', 'decline')" class="px-3 py-1.5 text-xs font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 transition duration-150">Decline</button>
                                </form>
                                <a href="<?= APP_URL ?>/profile?id=<?= $quote['craftsman_id'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition duration-150">View Profile</a>
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
                <div id="tab-bookings" class="tab-content hidden">
                    <?php if (!empty($bookings)): ?>
                    <div class="space-y-3">
                        <?php foreach ($bookings as $booking): ?>
                        <div class="bg-white rounded-lg shadow-sm border <?= $booking['status'] === 'counter_offered' ? 'border-orange-200' : ($booking['status'] === 'pending_completion' ? 'border-purple-200' : ($booking['status'] === 'in_progress' ? 'border-blue-200' : 'border-gray-100')) ?> p-5">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-base font-bold text-gray-900">
                                        Booking with <?= htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']) ?>
                                    </p>
                                    <p class="mt-1 text-sm text-gray-600 line-clamp-2"><?= htmlspecialchars($booking['description']) ?></p>
                                    <div class="mt-2 flex items-center flex-wrap gap-2 text-xs text-gray-500">
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                            </svg>
                                            <?= htmlspecialchars(preg_replace('/^\d{2}\s-\s/', '', $booking['address'])) ?>
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                            <?= date('M d, Y \a\t g:i A', strtotime($booking['scheduled_date'])) ?>
                                        </span>
                                        <?php if (!empty($booking['quoted_price'])): ?>
                                        <span class="flex items-center font-semibold text-green-600">
                                            $<?= number_format($booking['quoted_price'], 2) ?>
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
                                        case 'cancelled': echo 'bg-red-100 text-red-800'; break;
                                        default: echo 'bg-gray-100 text-gray-800';
                                    }
                                    ?>">
                                    <?php
                                    switch($booking['status']) {
                                        case 'requested': echo 'Pending'; break;
                                        case 'counter_offered': echo 'Counter-Offer'; break;
                                        case 'in_progress': echo 'In Progress'; break;
                                        case 'pending_completion': echo 'Confirm Needed'; break;
                                        case 'completed': echo 'Completed'; break;
                                        case 'cancelled': echo 'Cancelled'; break;
                                        default: echo ucfirst($booking['status']);
                                    }
                                    ?>
                                </span>
                            </div>

                            <?php if ($booking['status'] === 'counter_offered'): ?>
                            <!-- Counter-Offer Review Section -->
                            <div class="mt-3 pt-3 border-t border-orange-100">
                                <div class="bg-orange-50 rounded-lg p-4 mb-3">
                                    <p class="text-xs font-bold text-orange-800 uppercase tracking-wider mb-2">📩 Craftsman's Counter-Offer</p>
                                    <?php if (!empty($booking['counter_description'])): ?>
                                    <p class="text-sm text-gray-700 mb-2"><span class="font-semibold">Description:</span> <?= htmlspecialchars($booking['counter_description']) ?></p>
                                    <?php endif; ?>
                                    <div class="flex flex-wrap gap-4 text-sm">
                                        <?php if (!empty($booking['counter_price'])): ?>
                                        <span class="font-semibold text-green-700">💰 Price: $<?= number_format($booking['counter_price'], 2) ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($booking['counter_date'])): ?>
                                        <span class="text-gray-600">📅 Date: <?= date('M d, Y \a\t g:i A', strtotime($booking['counter_date'])) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($booking['counter_note'])): ?>
                                    <p class="mt-2 text-sm text-gray-500 italic">"<?= htmlspecialchars($booking['counter_note']) ?>"</p>
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <form id="accept-counter-<?= $booking['id'] ?>" action="<?= APP_URL ?>/bookings/accept-counter" method="POST" class="inline">
                                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                        <button type="button" onclick="showConfirmModal('accept-counter-<?= $booking['id'] ?>', 'Accept Counter-Offer?', 'This will accept the craftsman\'s changes and start the job.', 'accept')" class="px-3 py-1.5 text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition duration-150">Accept Changes</button>
                                    </form>
                                    <form id="cancel-counter-<?= $booking['id'] ?>" action="<?= APP_URL ?>/bookings/cancel-counter" method="POST" class="inline">
                                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                        <button type="button" onclick="showConfirmModal('cancel-counter-<?= $booking['id'] ?>', 'Cancel this booking?', 'This will cancel the booking entirely. This cannot be undone.', 'decline')" class="px-3 py-1.5 text-xs font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 transition duration-150">Cancel Booking</button>
                                    </form>
                                    <a href="<?= APP_URL ?>/profile?id=<?= $booking['craftsman_id'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition duration-150">View Craftsman</a>
                                </div>
                            </div>

                            <?php elseif ($booking['status'] === 'pending_completion'): ?>
                            <!-- Confirm Completion Section -->
                            <div class="mt-3 pt-3 border-t border-purple-100">
                                <div class="bg-purple-50 rounded-lg p-3 mb-3">
                                    <p class="text-sm font-semibold text-purple-800">⏳ The craftsman has marked this job as complete. Please confirm.</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <form id="confirm-complete-<?= $booking['id'] ?>" action="<?= APP_URL ?>/bookings/confirm-completion" method="POST" class="inline">
                                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                        <button type="button" onclick="showConfirmModal('confirm-complete-<?= $booking['id'] ?>', 'Confirm Job Complete?', 'This confirms the work is done. You will be able to leave a review.', 'accept')" class="px-3 py-1.5 text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition duration-150">✅ Confirm Completion</button>
                                    </form>
                                    <a href="<?= APP_URL ?>/profile?id=<?= $booking['craftsman_id'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition duration-150">View Craftsman</a>
                                </div>
                            </div>

                            <?php elseif ($booking['status'] === 'completed' && empty($booking['has_reviewed'])): ?>
                            <div class="mt-3 pt-3 border-t border-gray-100 flex items-center space-x-2">
                                <a href="<?= APP_URL ?>/reviews/create?booking_id=<?= $booking['id'] ?>" class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition duration-150">
                                    <svg class="h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    Write a Review
                                </a>
                                <a href="<?= APP_URL ?>/profile?id=<?= $booking['craftsman_id'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition duration-150">View Craftsman</a>
                            </div>
                            <?php elseif ($booking['status'] === 'completed' && !empty($booking['has_reviewed'])): ?>
                            <div class="mt-3 pt-3 border-t border-gray-100 flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-1 bg-gray-50 text-xs font-medium text-gray-500 rounded border border-gray-200">
                                    <svg class="h-3 w-3 mr-1 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Reviewed
                                </span>
                                <a href="<?= APP_URL ?>/profile?id=<?= $booking['craftsman_id'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition duration-150">View Craftsman</a>
                            </div>

                            <?php elseif ($booking['status'] === 'in_progress'): ?>
                            <div class="mt-3 pt-3 border-t border-blue-100 bg-blue-50 -mx-5 -mb-5 px-5 py-3 rounded-b-lg">
                                <p class="text-xs font-semibold text-blue-700">🔧 Job is in progress — the craftsman will mark it complete when done.</p>
                            </div>

                            <?php else: ?>
                            <div class="mt-3 pt-3 border-t border-gray-100 flex items-center space-x-2">
                                <a href="<?= APP_URL ?>/profile?id=<?= $booking['craftsman_id'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition duration-150">View Craftsman</a>
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
                <div id="tab-favorites" class="tab-content hidden">
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
                                    <h3 class="text-base font-bold text-gray-900"><?= htmlspecialchars($favorite['first_name'] . ' ' . $favorite['last_name']) ?></h3>
                                    <p class="text-sm font-medium text-indigo-600"><?= htmlspecialchars($favorite['service_category'] ?? 'Professional') ?></p>
                                    
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
                                    <a href="<?= APP_URL ?>/profile?id=<?= $favorite['id'] ?>" class="px-3 py-1.5 text-xs font-medium rounded text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition duration-150">
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

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="<?= APP_URL ?>/jobs/create" class="flex items-center w-full px-4 py-3 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition duration-150">
                            <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Post a New Job
                        </a>
                        <a href="<?= APP_URL ?>/search" class="flex items-center w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-150 border border-gray-200">
                            <svg class="h-5 w-5 mr-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                            Find Craftsmen
                        </a>
                        <a href="<?= APP_URL ?>/jobs" class="flex items-center w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-150 border border-gray-200">
                            <svg class="h-5 w-5 mr-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                            </svg>
                            Browse Job Board
                        </a>
                        <a href="<?= APP_URL ?>/profile/edit" class="flex items-center w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-150 border border-gray-200">
                            <svg class="h-5 w-5 mr-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit Profile
                        </a>
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-xl shadow-lg p-6 text-white">
                    <h3 class="font-bold text-lg">💡 Pro Tip</h3>
                    <p class="mt-2 text-indigo-100 text-sm leading-relaxed">
                        Add detailed descriptions to your jobs and include a budget range. Jobs with budgets receive 
                        <span class="font-bold text-white">3x more quotes</span> from craftsmen!
                    </p>
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
        el.classList.add('hidden');
    });
    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.classList.remove('border-indigo-500', 'text-indigo-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    document.getElementById('tab-' + tabName).classList.remove('hidden');
    var activeBtn = document.querySelector('[data-tab="' + tabName + '"]');
    activeBtn.classList.remove('border-transparent', 'text-gray-500');
    activeBtn.classList.add('border-indigo-500', 'text-indigo-600');
}

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
