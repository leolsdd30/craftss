<!-- Admin: Craftsman Verifications -->
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-start justify-between">
                <div>
                    <a href="<?= APP_URL ?>/admin/dashboard" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors">&larr; Back to Dashboard</a>
                    <h1 class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-white">Craftsman Verification</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Review and verify craftsman profiles.</p>
                </div>
                <a href="<?= APP_URL ?>/admin/users"
                   class="mt-6 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 transition duration-150">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Manage Users
                </a>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
            <nav class="flex space-x-8">
                <?php foreach (['pending' => 'Pending', 'verified' => 'Verified', 'all' => 'All'] as $val => $label): ?>
                <a href="<?= APP_URL ?>/admin/verifications?filter=<?= $val ?>"
                   class="<?= $filter === $val ? 'border-indigo-500 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600' ?> whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                    <?= $label ?>
                    <?php if ($filter === $val && $totalCraftsmen > 0): ?>
                    <span class="ml-2 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-xs font-bold px-2 py-0.5 rounded-full"><?= $totalCraftsmen ?></span>
                    <?php endif; ?>
                </a>
                <?php endforeach; ?>
            </nav>
        </div>

        <!-- Search + Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-6">
            <form method="GET" action="<?= APP_URL ?>/admin/verifications" class="flex flex-wrap items-end gap-4">
                <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Search by name</label>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="First or last name..." autocomplete="off"
                           class="w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border">
                </div>

                <?php if (!empty($wilayas)): ?>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Wilaya</label>
                    <select name="wilaya" class="border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border">
                        <option value="">All Wilayas</option>
                        <?php foreach ($wilayas as $w): ?>
                        <option value="<?= htmlspecialchars($w) ?>" <?= $wilayaFilter === $w ? 'selected' : '' ?>>
                            <?= htmlspecialchars(preg_replace('/^\d{2}\s-\s/', '', $w)) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <?php if (!empty($categories)): ?>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Category</label>
                    <select name="category" class="border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>" <?= $categoryFilter === $cat ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Sort By</label>
                    <select name="sort" class="border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border">
                        <option value="date_desc" <?= ($sortFilter ?? 'date_desc') === 'date_desc' ? 'selected' : '' ?>>Newest First</option>
                        <option value="date_asc"  <?= ($sortFilter ?? '') === 'date_asc'  ? 'selected' : '' ?>>Oldest First</option>
                        <option value="name_asc"  <?= ($sortFilter ?? '') === 'name_asc'  ? 'selected' : '' ?>>Name A–Z</option>
                        <option value="name_desc" <?= ($sortFilter ?? '') === 'name_desc' ? 'selected' : '' ?>>Name Z–A</option>
                    </select>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 transition duration-150">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Filter
                    </button>
                    <?php if (!empty($search) || !empty($wilayaFilter) || !empty($categoryFilter) || !empty($sortFilter)): ?>
                    <a href="<?= APP_URL ?>/admin/verifications?filter=<?= htmlspecialchars($filter) ?>" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Success Message -->
        <?php if (!empty($_GET['success'])): ?>
        <div class="rounded-lg bg-green-50 dark:bg-green-900/30 p-4 mb-6 border border-green-200 dark:border-green-800">
            <div class="flex">
                <svg class="h-5 w-5 text-green-400 dark:text-green-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="ml-3 text-sm font-medium text-green-800 dark:text-green-300">Verification status updated successfully!</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Results bar: count + inline pagination -->
        <?php
            $qs = http_build_query(['filter' => $filter, 'search' => $search, 'wilaya' => $wilayaFilter, 'category' => $categoryFilter, 'sort' => $sortFilter ?? '']);
        ?>
        <?php if ($totalCraftsmen > 0): ?>
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Showing <span class="font-semibold text-gray-700 dark:text-gray-300"><?= count($craftsmen) ?></span>
                of <span class="font-semibold text-gray-700 dark:text-gray-300"><?= $totalCraftsmen ?></span>
                craftsman<?= $totalCraftsmen !== 1 ? 'en' : '' ?>
            </p>
            <?php if ($totalPages > 1): ?>
            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm">
                <?php if ($page > 1): ?>
                <a href="?<?= $qs ?>&page=<?= $page - 1 ?>" class="relative inline-flex items-center rounded-l-md px-2 py-1.5 text-gray-400 dark:text-gray-500 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd"/></svg>
                </a>
                <?php endif; ?>
                <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                <a href="?<?= $qs ?>&page=<?= $i ?>" class="relative inline-flex items-center px-3 py-1.5 text-sm font-semibold <?= $i === $page ? 'z-10 bg-indigo-600 dark:bg-indigo-500 text-white' : 'text-gray-900 dark:text-gray-300 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700' ?>"><?= $i ?></a>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                <a href="?<?= $qs ?>&page=<?= $page + 1 ?>" class="relative inline-flex items-center rounded-r-md px-2 py-1.5 text-gray-400 dark:text-gray-500 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
                </a>
                <?php endif; ?>
            </nav>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Craftsmen Grid -->
        <?php if (empty($craftsmen)): ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">No craftsmen found</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400"><?= $filter === 'pending' ? 'No pending verifications. All caught up!' : 'No craftsmen match this filter.' ?></p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($craftsmen as $c): ?>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border dark:border-gray-700 <?= $c['is_verified'] ? 'border-green-200 dark:border-green-800/50' : 'border-yellow-200 dark:border-yellow-800/50' ?> overflow-hidden hover:shadow-md transition-shadow duration-200">
                <div class="p-6">

                    <!-- Profile Header -->
                    <div class="flex items-center space-x-4 mb-4">
                        <img src="<?= get_profile_picture_url($c['profile_picture'] ?? 'default.png', $c['first_name'], $c['last_name']) ?>"
                             alt="<?= htmlspecialchars($c['first_name']) ?>"
                             class="h-14 w-14 rounded-full object-cover border-2 <?= $c['is_verified'] ? 'border-green-300 dark:border-green-600' : 'border-yellow-200 dark:border-yellow-600' ?>">
                        <div class="flex-1 min-w-0">
                            <!-- Name + verified badge inline -->
                            <div class="flex items-center gap-1.5">
                                <a href="<?= APP_URL ?>/profile/<?= htmlspecialchars($c['username']) ?>"
                                   class="text-base font-bold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors truncate">
                                    <?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?>
                                </a>
                                <?php if ($c['is_verified']): ?>
                                <svg class="h-4 w-4 text-blue-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" title="Verified">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <?php endif; ?>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate"><?= htmlspecialchars($c['email']) ?></p>
                            <?php if (!empty($c['wilaya'])): ?>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 flex items-center">
                                <svg class="h-3 w-3 mr-1 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <?= htmlspecialchars(preg_replace('/^\d{2}\s-\s/', '', $c['wilaya'])) ?>
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="space-y-2 mb-4">
                        <?php if (!empty($c['service_category'])): ?>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Category</span>
                            <span class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($c['service_category']) ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($c['hourly_rate'])): ?>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Hourly Rate</span>
                            <span class="font-medium text-gray-900 dark:text-white"><?= number_format($c['hourly_rate'], 2) ?> DZD</span>
                        </div>
                        <?php endif; ?>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Joined</span>
                            <span class="text-gray-400 dark:text-gray-500">
                                <?php
                                    $diff = time() - strtotime($c['user_created']);
                                    if ($diff < 86400)       echo floor($diff / 3600) . 'h ago';
                                    elseif ($diff < 604800)  echo floor($diff / 86400) . 'd ago';
                                    elseif ($diff < 2592000) echo floor($diff / 604800) . 'w ago';
                                    else                     echo date('M d, Y', strtotime($c['user_created']));
                                ?>
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Status</span>
                            <?php if ($c['is_verified']): ?>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded-full">
                                <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Verified
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center text-xs font-semibold text-yellow-700 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/20 px-2 py-0.5 rounded-full">Pending</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($c['bio'])): ?>
                    <p class="text-xs text-gray-500 dark:text-gray-400 italic line-clamp-2 mb-4">"<?= htmlspecialchars(substr($c['bio'], 0, 120)) . (strlen($c['bio']) > 120 ? '...' : '') ?>"</p>
                    <?php endif; ?>

                    <!-- Action -->
                    <form id="verify-form-<?= $c['user_id'] ?>" method="POST" action="<?= APP_URL ?>/admin/verifications/toggle">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="user_id"   value="<?= $c['user_id'] ?>">
                        <input type="hidden" name="filter"    value="<?= htmlspecialchars($filter) ?>">
                        <?php if ($c['is_verified']): ?>
                        <button type="button"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 dark:border-red-800 shadow-sm text-sm font-medium rounded-lg text-red-700 dark:text-red-400 bg-white dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900/20 transition duration-150"
                                onclick="showConfirmModal('verify-form-<?= $c['user_id'] ?>', 'Remove Verification', 'Are you sure you want to remove the verified status from <?= htmlspecialchars($c['first_name']) ?>?', 'decline')">
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Remove Verification
                        </button>
                        <?php else: ?>
                        <button type="button"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 dark:bg-green-500 hover:bg-green-700 dark:hover:bg-green-600 transition duration-150"
                                onclick="showConfirmModal('verify-form-<?= $c['user_id'] ?>', 'Verify Craftsman', 'Are you sure you want to verify <?= htmlspecialchars($c['first_name']) ?> as a professional?', 'accept')">
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Verify Craftsman
                        </button>
                        <?php endif; ?>
                    </form>

                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Bottom Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="mt-8 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 sm:px-6 rounded-xl shadow-sm">
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <p class="text-sm text-gray-700 dark:text-gray-300">Page <span class="font-medium dark:text-white"><?= $page ?></span> of <span class="font-medium dark:text-white"><?= $totalPages ?></span></p>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm">
                    <?php if ($page > 1): ?>
                    <a href="?<?= $qs ?>&page=<?= $page - 1 ?>" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 dark:text-gray-500 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                    <a href="?<?= $qs ?>&page=<?= $i ?>" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold <?= $i === $page ? 'z-10 bg-indigo-600 dark:bg-indigo-500 text-white' : 'text-gray-900 dark:text-gray-300 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                    <?php if ($page < $totalPages): ?>
                    <a href="?<?= $qs ?>&page=<?= $page + 1 ?>" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 dark:text-gray-500 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
                    </a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
        <?php endif; ?>

        <?php endif; ?>

    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirm-modal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50" onclick="hideConfirmModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full p-6">
            <div class="flex items-center mb-4">
                <div id="modal-icon-accept" class="hidden h-10 w-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center mr-3">
                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <div id="modal-icon-decline" class="hidden h-10 w-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mr-3">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </div>
                <h3 id="modal-title" class="text-lg font-bold text-gray-900 dark:text-white"></h3>
            </div>
            <p id="modal-message" class="text-sm text-gray-600 dark:text-gray-400 mb-6"></p>
            <div class="flex justify-end space-x-3">
                <button onclick="hideConfirmModal()" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition duration-150">Cancel</button>
                <button id="modal-confirm-btn" onclick="confirmAction()" class="px-4 py-2 text-sm font-medium text-white rounded-lg transition duration-150">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
var pendingFormId = null;
function showConfirmModal(formId, title, message, type) {
    pendingFormId = formId;
    document.getElementById('modal-title').textContent   = title;
    document.getElementById('modal-message').textContent = message;
    var btn = document.getElementById('modal-confirm-btn');
    var ia  = document.getElementById('modal-icon-accept');
    var id  = document.getElementById('modal-icon-decline');
    if (type === 'accept') {
        btn.className = 'px-4 py-2 text-sm font-medium text-white bg-green-600 dark:bg-green-500 hover:bg-green-700 dark:hover:bg-green-600 rounded-lg transition duration-150';
        btn.textContent = 'Yes, Verify';
        ia.classList.remove('hidden'); id.classList.add('hidden');
    } else {
        btn.className = 'px-4 py-2 text-sm font-medium text-white bg-red-600 dark:bg-red-500 hover:bg-red-700 dark:hover:bg-red-600 rounded-lg transition duration-150';
        btn.textContent = 'Yes, Remove';
        ia.classList.add('hidden'); id.classList.remove('hidden');
    }
    document.getElementById('confirm-modal').classList.remove('hidden');
}
function hideConfirmModal() {
    document.getElementById('confirm-modal').classList.add('hidden');
    pendingFormId = null;
}
function confirmAction() {
    if (pendingFormId) document.getElementById(pendingFormId).submit();
}
</script>
