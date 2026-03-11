<!-- Admin Dashboard -->
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 flex items-center">
                        <svg class="h-8 w-8 text-indigo-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Admin Dashboard
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Platform overview and management tools</p>
                </div>
                <div class="flex space-x-3">
                    <a href="<?= APP_URL ?>/admin/users" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                        <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                        Manage Users
                    </a>
                    <a href="<?= APP_URL ?>/admin/verifications" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                        <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Verifications
                        <?php if ($stats['pending_verification'] > 0): ?>
                        <span class="ml-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-0.5 rounded-full"><?= $stats['pending_verification'] ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <!-- Total Users -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Users</p>
                        <p class="text-3xl font-extrabold text-gray-900 mt-1"><?= number_format($stats['total_users']) ?></p>
                    </div>
                    <div class="bg-indigo-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs">
                    <span class="text-indigo-600 font-semibold"><?= $stats['homeowners'] ?></span>
                    <span class="text-gray-400 mx-1">homeowners</span>
                    <span class="text-gray-300">·</span>
                    <span class="text-indigo-600 font-semibold ml-1"><?= $stats['craftsmen'] ?></span>
                    <span class="text-gray-400 mx-1">craftsmen</span>
                </div>
            </div>

            <!-- Bookings -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Bookings</p>
                        <p class="text-3xl font-extrabold text-gray-900 mt-1"><?= number_format($stats['total_bookings']) ?></p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs">
                    <span class="text-green-600 font-semibold"><?= $stats['active_bookings'] ?></span>
                    <span class="text-gray-400 mx-1">active</span>
                    <span class="text-gray-300">·</span>
                    <span class="text-green-600 font-semibold ml-1"><?= $stats['completed_bookings'] ?></span>
                    <span class="text-gray-400 mx-1">completed</span>
                </div>
            </div>

            <!-- Jobs -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Job Postings</p>
                        <p class="text-3xl font-extrabold text-gray-900 mt-1"><?= number_format($stats['total_jobs']) ?></p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs">
                    <span class="text-yellow-600 font-semibold"><?= $stats['open_jobs'] ?></span>
                    <span class="text-gray-400 mx-1">open positions</span>
                </div>
            </div>

            <!-- Reviews -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Reviews & Ratings</p>
                        <p class="text-3xl font-extrabold text-gray-900 mt-1"><?= number_format($stats['total_reviews']) ?></p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-3">
                        <svg class="h-6 w-6 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs">
                    <span class="text-yellow-600 font-semibold"><?= $stats['avg_rating'] ?></span>
                    <span class="text-gray-400 mx-1">avg rating</span>
                    <span class="text-gray-300">·</span>
                    <span class="text-green-600 font-semibold ml-1"><?= $stats['verified_craftsmen'] ?></span>
                    <span class="text-gray-400 mx-1">verified</span>
                </div>
            </div>
        </div>

        <!-- Quick Stats Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
            <!-- Verification Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="h-5 w-5 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Verification Status
                </h3>
                <div class="flex items-center space-x-8">
                    <div class="text-center">
                        <p class="text-4xl font-extrabold text-green-600"><?= $stats['verified_craftsmen'] ?></p>
                        <p class="text-sm text-gray-500 mt-1">Verified</p>
                    </div>
                    <div class="text-center">
                        <p class="text-4xl font-extrabold text-yellow-500"><?= $stats['pending_verification'] ?></p>
                        <p class="text-sm text-gray-500 mt-1">Pending</p>
                    </div>
                    <div class="text-center">
                        <p class="text-4xl font-extrabold text-gray-400"><?= $stats['total_messages'] ?></p>
                        <p class="text-sm text-gray-500 mt-1">Messages</p>
                    </div>
                </div>
                <?php if ($stats['pending_verification'] > 0): ?>
                <a href="<?= APP_URL ?>/admin/verifications?filter=pending" class="mt-4 inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                    Review pending verifications →
                </a>
                <?php endif; ?>
            </div>

            <!-- Platform Health -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="h-5 w-5 text-blue-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Platform Health
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Booking Success Rate</span>
                        <?php $successRate = $stats['total_bookings'] > 0 ? round(($stats['completed_bookings'] / $stats['total_bookings']) * 100) : 0; ?>
                        <span class="text-sm font-bold text-gray-900"><?= $successRate ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full transition-all duration-300" style="width: <?= $successRate ?>%"></div>
                    </div>
                    <div class="flex items-center justify-between mt-4">
                        <span class="text-sm text-gray-600">Craftsman Verification Rate</span>
                        <?php $verifyRate = $stats['craftsmen'] > 0 ? round(($stats['verified_craftsmen'] / $stats['craftsmen']) * 100) : 0; ?>
                        <span class="text-sm font-bold text-gray-900"><?= $verifyRate ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-500 h-2 rounded-full transition-all duration-300" style="width: <?= $verifyRate ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Recent Users</h3>
                <a href="<?= APP_URL ?>/admin/users" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">View all →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($recentUsers as $u): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900"><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($u['email']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                    $roleColors = [
                                        'admin' => 'bg-red-100 text-red-800',
                                        'craftsman' => 'bg-indigo-100 text-indigo-800',
                                        'homeowner' => 'bg-green-100 text-green-800'
                                    ];
                                    $rc = $roleColors[$u['role']] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $rc ?> capitalize"><?= htmlspecialchars($u['role']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($u['is_active']): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
