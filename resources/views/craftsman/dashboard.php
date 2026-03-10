<!-- Craftsman Dashboard -->
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900">
                Welcome back, <?= htmlspecialchars($_SESSION['first_name'] ?? 'Craftsman') ?>!
            </h1>
            <p class="mt-1 text-sm text-gray-500">Manage your business, track your bids, and grow your career.</p>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Earnings</p>
                        <p class="text-2xl font-bold text-gray-900">$<?= number_format($totalEarnings, 2) ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Active Jobs</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $activeBookings ?></p>
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
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted Bids</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $submittedBids ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</p>
                        <p class="text-2xl font-bold text-gray-900"><?= $rating['total_reviews'] > 0 ? '★ ' . $rating['avg_rating'] : '—' ?></p>
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
                        <button onclick="switchTab('quotes')" data-tab="quotes"
                            class="tab-btn border-indigo-500 text-indigo-600 whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            My Quotes
                            <?php if ($pendingBids > 0): ?>
                            <span class="ml-1.5 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700"><?= $pendingBids ?></span>
                            <?php endif; ?>
                        </button>
                        <button onclick="switchTab('active')" data-tab="active"
                            class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            Active Jobs
                            <?php if ($activeBookings > 0): ?>
                            <span class="ml-1.5 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700"><?= $activeBookings ?></span>
                            <?php endif; ?>
                        </button>
                        <button onclick="switchTab('bookings')" data-tab="bookings"
                            class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            Bookings
                            <?php if ($pendingBookings > 0): ?>
                            <span class="ml-1.5 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700"><?= $pendingBookings ?></span>
                            <?php endif; ?>
                        </button>
                        <button onclick="switchTab('reviews')" data-tab="reviews"
                            class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            Reviews
                        </button>
                    </nav>
                </div>

                <!-- Tab: My Quotes -->
                <div id="tab-quotes" class="tab-content" style="display:none">
                    <?php if (!empty($quotes)): ?>
                    <div class="space-y-3">
                        <?php foreach ($quotes as $quote): ?>
                        <a href="<?= APP_URL ?>/jobs/show?id=<?= $quote['job_posting_id'] ?>" class="block bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md hover:border-indigo-200 transition-all duration-200 p-5">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base font-bold text-gray-900 truncate"><?= htmlspecialchars($quote['title']) ?></h3>
                                    <div class="mt-1.5 flex items-center flex-wrap gap-2">
                                        <span class="text-sm font-semibold text-green-700">My Bid: $<?= number_format($quote['quoted_price'], 2) ?></span>
                                        <span class="text-xs text-gray-400">·</span>
                                        <span class="text-xs text-gray-500"><?= date('M d, Y', strtotime($quote['created_at'])) ?></span>
                                    </div>
                                    <?php if (!empty($quote['cover_message'])): ?>
                                    <p class="mt-2 text-sm text-gray-500 italic line-clamp-1">"<?= htmlspecialchars($quote['cover_message']) ?>"</p>
                                    <?php endif; ?>
                                </div>
                                <span class="ml-3 px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full 
                                    <?= $quote['status'] === 'accepted' ? 'bg-green-100 text-green-800' : ($quote['status'] === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                                    <?= ucfirst($quote['status']) ?>
                                </span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="bg-white rounded-lg border-2 border-dashed border-gray-200 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <h3 class="mt-3 text-sm font-medium text-gray-900">No quotes submitted yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Browse the job board and start submitting quotes to win work.</p>
                        <a href="<?= APP_URL ?>/jobs" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                            Browse Job Board
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Tab: Active Jobs (accepted quotes) -->
                <div id="tab-active" class="tab-content hidden">
                    <?php 
                        $acceptedQuotes = array_filter($quotes ?? [], function($q) { return $q['status'] === 'accepted'; });
                    ?>
                    <?php if (!empty($acceptedQuotes)): ?>
                    <div class="space-y-3">
                        <?php foreach ($acceptedQuotes as $quote): ?>
                        <a href="<?= APP_URL ?>/jobs/show?id=<?= $quote['job_posting_id'] ?>" class="block bg-white rounded-lg shadow-sm border border-green-200 hover:shadow-md transition-all duration-200 p-5">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base font-bold text-gray-900 truncate"><?= htmlspecialchars($quote['title']) ?></h3>
                                    <div class="mt-1.5 flex items-center gap-2">
                                        <span class="text-sm font-semibold text-green-700">Agreed: $<?= number_format($quote['quoted_price'], 2) ?></span>
                                    </div>
                                </div>
                                <span class="ml-3 px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                    In Progress
                                </span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="bg-white rounded-lg border-2 border-dashed border-gray-200 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-3 text-sm font-medium text-gray-900">No active jobs</h3>
                        <p class="mt-1 text-sm text-gray-500">Once a homeowner accepts your quote, the job will appear here.</p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Tab: Bookings -->
                <div id="tab-bookings" class="tab-content hidden">
                    <?php if (!empty($bookings)): ?>
                    <div class="space-y-3">
                        <?php foreach ($bookings as $booking): ?>
                        <div class="bg-white rounded-lg shadow-sm border <?= $booking['status'] === 'requested' ? 'border-yellow-200' : ($booking['status'] === 'counter_offered' ? 'border-orange-200' : ($booking['status'] === 'in_progress' ? 'border-blue-200' : ($booking['status'] === 'pending_completion' ? 'border-purple-200' : 'border-gray-100'))) ?> p-5">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-base font-bold text-gray-900">
                                        <?= htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']) ?>
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
                                        case 'cancelled': echo 'bg-gray-100 text-gray-800'; break;
                                        default: echo 'bg-gray-100 text-gray-800';
                                    }
                                    ?>">
                                    <?php
                                    switch($booking['status']) {
                                        case 'requested': echo 'Requested'; break;
                                        case 'counter_offered': echo 'Counter Sent'; break;
                                        case 'in_progress': echo 'In Progress'; break;
                                        case 'pending_completion': echo 'Awaiting Confirmation'; break;
                                        case 'completed': echo 'Completed'; break;
                                        case 'cancelled': echo 'Cancelled'; break;
                                        default: echo ucfirst($booking['status']);
                                    }
                                    ?>
                                </span>
                            </div>

                            <?php if ($booking['status'] === 'requested'): ?>
                            <div class="mt-3 pt-3 border-t border-gray-100 flex items-center space-x-2">
                                <form id="accept-booking-<?= $booking['id'] ?>" action="<?= APP_URL ?>/bookings/accept" method="POST" class="inline">
                                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                    <button type="button" onclick="showConfirmModal('accept-booking-<?= $booking['id'] ?>', 'Accept this booking?', 'The job will start immediately. The homeowner will be notified.', 'accept')" class="px-3 py-1.5 text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition duration-150">Accept</button>
                                </form>
                                <button type="button" onclick="openCounterModal(<?= $booking['id'] ?>, '<?= e($booking['description']) ?>', '<?= e($booking['scheduled_date']) ?>')" class="px-3 py-1.5 text-xs font-medium rounded-md text-white bg-orange-500 hover:bg-orange-600 transition duration-150">Counter-Offer</button>
                                <form id="decline-booking-<?= $booking['id'] ?>" action="<?= APP_URL ?>/bookings/decline" method="POST" class="inline">
                                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                    <button type="button" onclick="showConfirmModal('decline-booking-<?= $booking['id'] ?>', 'Decline this booking?', 'Are you sure you want to decline <?= htmlspecialchars($booking['first_name']) ?>\'s booking request? This cannot be undone.', 'decline')" class="px-3 py-1.5 text-xs font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 transition duration-150">Decline</button>
                                </form>
                                <a href="<?= APP_URL ?>/profile?id=<?= $booking['homeowner_id'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition duration-150">View Homeowner</a>
                            </div>

                            <?php elseif ($booking['status'] === 'counter_offered'): ?>
                            <div class="mt-3 pt-3 border-t border-orange-100 bg-orange-50 -mx-5 -mb-5 px-5 py-3 rounded-b-lg">
                                <p class="text-xs font-semibold text-orange-700 mb-1">📤 Counter-offer sent — waiting for homeowner response</p>
                                <?php if (!empty($booking['counter_note'])): ?>
                                <p class="text-xs text-gray-600 italic">"<?= htmlspecialchars($booking['counter_note']) ?>"</p>
                                <?php endif; ?>
                            </div>

                            <?php elseif ($booking['status'] === 'in_progress'): ?>
                            <div class="mt-3 pt-3 border-t border-gray-100 flex items-center space-x-2">
                                <form id="complete-booking-<?= $booking['id'] ?>" action="<?= APP_URL ?>/bookings/complete" method="POST" class="inline">
                                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                    <button type="button" onclick="showConfirmModal('complete-booking-<?= $booking['id'] ?>', 'Mark as Complete?', 'The homeowner will need to confirm the work is done before the job is fully closed.', 'accept')" class="px-3 py-1.5 text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition duration-150">Mark as Complete</button>
                                </form>
                                <a href="<?= APP_URL ?>/profile?id=<?= $booking['homeowner_id'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition duration-150">View Homeowner</a>
                            </div>

                            <?php elseif ($booking['status'] === 'pending_completion'): ?>
                            <div class="mt-3 pt-3 border-t border-purple-100 bg-purple-50 -mx-5 -mb-5 px-5 py-3 rounded-b-lg">
                                <p class="text-xs font-semibold text-purple-700">⏳ Waiting for homeowner to confirm the job is complete</p>
                            </div>

                            <?php elseif ($booking['status'] === 'completed'): ?>
                            <div class="mt-3 pt-3 border-t border-green-100 bg-green-50 -mx-5 -mb-5 px-5 py-3 rounded-b-lg">
                                <p class="text-xs font-semibold text-green-700">✅ Job completed and confirmed</p>
                            </div>

                            <?php else: ?>
                            <div class="mt-3 pt-3 border-t border-gray-100 flex items-center space-x-2">
                                <a href="<?= APP_URL ?>/profile?id=<?= $booking['homeowner_id'] ?>" class="px-3 py-1.5 text-xs font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition duration-150">View Homeowner</a>
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
                        <h3 class="mt-3 text-sm font-medium text-gray-900">No booking requests yet</h3>
                        <p class="mt-1 text-sm text-gray-500">When homeowners send you booking requests, they'll appear here.</p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Tab: Reviews -->
                <div id="tab-reviews" class="tab-content hidden">
                    <?php if (!empty($reviews)): ?>
                    <div class="mb-4 bg-white rounded-lg shadow-sm border border-gray-100 p-5">
                        <div class="flex items-center space-x-4">
                            <div class="text-center">
                                <p class="text-4xl font-extrabold text-gray-900"><?= $rating['avg_rating'] ?></p>
                                <div class="flex items-center justify-center mt-1">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <svg class="h-5 w-5 <?= $i <= round($rating['avg_rating']) ? 'text-yellow-400' : 'text-gray-300' ?>" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    <?php endfor; ?>
                                </div>
                                <p class="text-xs text-gray-500 mt-1"><?= $rating['total_reviews'] ?> review<?= $rating['total_reviews'] !== 1 ? 's' : '' ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3">
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
                    <div class="bg-white rounded-lg border-2 border-dashed border-gray-200 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        <h3 class="mt-3 text-sm font-medium text-gray-900">No reviews yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Complete jobs to start receiving reviews from homeowners.</p>
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
                        <a href="<?= APP_URL ?>/jobs" class="flex items-center w-full px-4 py-3 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition duration-150">
                            <svg class="h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                            Browse Job Board
                        </a>
                        <a href="<?= APP_URL ?>/profile?id=<?= $_SESSION['user_id'] ?>" class="flex items-center w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-150 border border-gray-200">
                            <svg class="h-5 w-5 mr-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            View Public Profile
                        </a>
                        <a href="<?= APP_URL ?>/profile/edit" class="flex items-center w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-150 border border-gray-200">
                            <svg class="h-5 w-5 mr-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit Profile & Portfolio
                        </a>
                        <a href="<?= APP_URL ?>/search" class="flex items-center w-full px-4 py-3 text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-150 border border-gray-200">
                            <svg class="h-5 w-5 mr-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                            See Other Craftsmen
                        </a>
                    </div>
                </div>

                <!-- Your Presence Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Your Stats</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Quotes Won</span>
                            <span class="text-sm font-bold text-green-600"><?= $activeBookings ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Quote Success Rate</span>
                            <span class="text-sm font-bold text-gray-900">
                                <?= $submittedBids > 0 ? round(($activeBookings / $submittedBids) * 100) : 0 ?>%
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Pending Bids</span>
                            <span class="text-sm font-bold text-yellow-600"><?= $pendingBids ?></span>
                        </div>
                    </div>
                </div>

                <!-- Tip Card -->
                <div class="bg-gradient-to-br from-green-600 to-green-800 rounded-xl shadow-lg p-6 text-white">
                    <h3 class="font-bold text-lg">💡 Pro Tip</h3>
                    <p class="mt-2 text-green-100 text-sm leading-relaxed">
                        Craftsmen who include a detailed cover message with their quotes are 
                        <span class="font-bold text-white">5x more likely</span> to get hired!
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Counter-Offer Modal -->
<div id="counter-modal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" onclick="closeCounterModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full transform transition-all">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0 bg-orange-100 rounded-full p-2 mr-3">
                        <svg class="h-6 w-6 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Send Counter-Offer</h3>
                </div>
                <p class="text-sm text-gray-500 mb-4">Edit the booking details below and send back to the homeowner. They can accept or cancel.</p>
                <form id="counter-offer-form" action="<?= APP_URL ?>/bookings/counter-offer" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                    <input type="hidden" name="booking_id" id="counter-booking-id">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Updated Description</label>
                            <textarea name="counter_description" id="counter-description" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border px-3 py-2" 
                                placeholder="Describe what you'll do and any changes..." required></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Your Price ($)</label>
                                <input type="number" name="counter_price" id="counter-price" step="0.01" min="0" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border px-3 py-2" 
                                    placeholder="0.00" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Proposed Date</label>
                                <input type="datetime-local" name="counter_date" id="counter-date" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border px-3 py-2" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Note to Homeowner <span class="text-gray-400">(optional)</span></label>
                            <input type="text" name="counter_note" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm border px-3 py-2" 
                                placeholder="e.g., I suggest a different date because...">
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeCounterModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-150">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 rounded-lg transition duration-150">Send Counter-Offer</button>
                    </div>
                </form>
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

<!-- Tab Switching + Confirmation Modal + Counter-Offer Script -->
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
    // Strip any unexpected query strings from the hash just in case
    hash = hash.split('?')[0];
    
    // Legacy mapping: old notifications sent #jobs instead of #active
    if (hash === 'jobs') hash = 'active';

    if (hash && document.getElementById('tab-' + hash)) {
        switchTab(hash);
    } else {
        // Default: show first tab
        switchTab('quotes');
    }
})();

// Listen for hash changes when navigating via back/forward or typing in the address bar
window.addEventListener('hashchange', function() {
    var hash = window.location.hash.substring(1).split('?')[0];
    
    // Legacy mapping: old notifications sent #jobs instead of #active
    if (hash === 'jobs') hash = 'active';

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
    }
}

// Counter-Offer Modal
function openCounterModal(bookingId, description, scheduledDate) {
    document.getElementById('counter-booking-id').value = bookingId;
    document.getElementById('counter-description').value = description;
    // Convert the date to datetime-local format
    if (scheduledDate) {
        var d = new Date(scheduledDate);
        var local = d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0') + 'T' + String(d.getHours()).padStart(2,'0') + ':' + String(d.getMinutes()).padStart(2,'0');
        document.getElementById('counter-date').value = local;
    }
    document.getElementById('counter-modal').classList.remove('hidden');
}

function closeCounterModal() {
    document.getElementById('counter-modal').classList.add('hidden');
}
</script>
