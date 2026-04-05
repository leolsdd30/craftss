<!-- Admin: User Management -->
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-start justify-between">
                <div>
                    <a href="<?= APP_URL ?>/admin/dashboard" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors">&larr; Back to Dashboard</a>
                    <h1 class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-white">User Management</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Search, filter, and manage all platform users.</p>
                </div>
                <a href="<?= APP_URL ?>/admin/verifications"
                   class="mt-6 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 transition duration-150">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Verifications
                    <?php if (isset($stats['pending_verification']) && $stats['pending_verification'] > 0): ?>
                    <span class="ml-2 bg-yellow-400 text-yellow-900 border border-yellow-500 text-xs font-bold px-1.5 py-0.5 rounded-full"><?= $stats['pending_verification'] ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 mb-6">
            <form method="GET" action="<?= APP_URL ?>/admin/users" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Search</label>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Name or email..." autocomplete="off"
                           class="w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Role</label>
                    <select name="role" class="border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border">
                        <option value="">All Roles</option>
                        <option value="homeowner" <?= $roleFilter === 'homeowner' ? 'selected' : '' ?>>Homeowner</option>
                        <option value="craftsman" <?= $roleFilter === 'craftsman' ? 'selected' : '' ?>>Craftsman</option>
                        <option value="admin"     <?= $roleFilter === 'admin'     ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status</label>
                    <select name="status" class="border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border">
                        <option value="">All</option>
                        <option value="active"   <?= $statusFilter === 'active'   ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $statusFilter === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
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
                    <?php if (!empty($search) || !empty($roleFilter) || !empty($statusFilter) || !empty($wilayaFilter) || !empty($sortFilter)): ?>
                    <a href="<?= APP_URL ?>/admin/users" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Clear</a>
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
                <p class="ml-3 text-sm font-medium text-green-800 dark:text-green-300">User status updated successfully!</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Users Table -->
        <?php $qs = http_build_query(['search' => $search, 'role' => $roleFilter, 'status' => $statusFilter, 'wilaya' => $wilayaFilter, 'sort' => $sortFilter ?? '']); ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

            <!-- Top bar: count + inline pagination -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Showing <span class="font-semibold text-gray-700 dark:text-gray-300"><?= count($users) ?></span>
                    of <span class="font-semibold text-gray-700 dark:text-gray-300"><?= $totalUsers ?? count($users) ?></span>
                    user<?= ($totalUsers ?? count($users)) !== 1 ? 's' : '' ?>
                </p>
                <?php if (isset($totalPages) && $totalPages > 1): ?>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm">
                    <?php if ($page > 1): ?>
                    <a href="?<?= $qs ?>&page=<?= $page - 1 ?>" class="relative inline-flex items-center rounded-l-md px-2 py-1.5 text-gray-400 dark:text-gray-500 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                    <a href="?<?= $qs ?>&page=<?= $i ?>" class="relative inline-flex items-center px-3 py-1.5 text-sm font-semibold <?= $i === $page ? 'z-10 bg-indigo-600 dark:bg-indigo-500 text-white' : 'text-gray-900 dark:text-gray-300 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700' ?>">
                        <?= $i ?>
                    </a>
                    <?php endfor; ?>
                    <?php if ($page < $totalPages): ?>
                    <a href="?<?= $qs ?>&page=<?= $page + 1 ?>" class="relative inline-flex items-center rounded-r-md px-2 py-1.5 text-gray-400 dark:text-gray-500 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
                    </a>
                    <?php endif; ?>
                </nav>
                <?php endif; ?>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <?php if (empty($users)): ?>
                        <tr><td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">No users found matching your criteria.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($users as $u): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 dark:text-gray-500 font-mono">#<?= $u['id'] ?></td>

                            <!-- User: avatar + name + verified badge + wilaya -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <img class="h-9 w-9 rounded-full object-cover flex-shrink-0"
                                         src="<?= get_profile_picture_url($u['profile_picture'] ?? 'default.png', $u['first_name'], $u['last_name']) ?>"
                                         alt="<?= htmlspecialchars($u['first_name']) ?>">
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <a href="<?= APP_URL ?>/profile/<?= htmlspecialchars($u['username']) ?>"
                                               class="text-sm font-semibold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                                <?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?>
                                            </a>
                                            <?php if ($u['role'] === 'craftsman' && !empty($u['is_verified'])): ?>
                                            <svg class="h-4 w-4 text-blue-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" title="Verified Craftsman">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($u['wilaya'])): ?>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5"><?= htmlspecialchars(preg_replace('/^\d{2}\s-\s/', '', $u['wilaya'])) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($u['email']) ?></td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                    $roleColors = ['admin' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300', 'craftsman' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300', 'homeowner' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'];
                                    $rc = $roleColors[$u['role']] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $rc ?> capitalize"><?= htmlspecialchars($u['role']) ?></span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($u['is_active']): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Active</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">Inactive</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-gray-500 dark:text-gray-400"><?= date('M d, Y', strtotime($u['created_at'])) ?></p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                    <?php
                                        $diff = time() - strtotime($u['created_at']);
                                        if ($diff < 3600)        echo floor($diff / 60) . 'm ago';
                                        elseif ($diff < 86400)   echo floor($diff / 3600) . 'h ago';
                                        elseif ($diff < 604800)  echo floor($diff / 86400) . 'd ago';
                                        elseif ($diff < 2592000) echo floor($diff / 604800) . 'w ago';
                                        else                     echo floor($diff / 2592000) . 'mo ago';
                                    ?>
                                </p>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                <form id="status-form-<?= $u['id'] ?>" method="POST" action="<?= APP_URL ?>/admin/users/toggle-status" class="inline">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                    <?php if ($u['is_active']): ?>
                                    <button type="button" class="text-xs font-medium text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 px-3 py-1.5 rounded-lg transition-colors"
                                            onclick="showConfirmModal('Deactivate User', 'Are you sure you want to deactivate <?= htmlspecialchars($u['first_name']) ?>? They will not be able to log in.', 'decline', 'status-form-<?= $u['id'] ?>')">
                                        Deactivate
                                    </button>
                                    <?php else: ?>
                                    <button type="button" class="text-xs font-medium text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 px-3 py-1.5 rounded-lg transition-colors"
                                            onclick="showConfirmModal('Activate User', 'Are you sure you want to activate <?= htmlspecialchars($u['first_name']) ?>?', 'accept', 'status-form-<?= $u['id'] ?>')">
                                        Activate
                                    </button>
                                    <?php endif; ?>
                                </form>
                                <?php else: ?>
                                <span class="text-xs text-gray-400 dark:text-gray-500">You</span>
                                <?php endif; ?>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bottom Pagination -->
        <?php if (isset($totalPages) && $totalPages > 1): ?>
        <div class="mt-6 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 sm:px-6 rounded-xl shadow-sm">
            <div class="flex flex-1 justify-between sm:hidden">
                <a href="?<?= $qs ?>&page=<?= max(1, $page - 1) ?>" class="relative inline-flex items-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600">Previous</a>
                <a href="?<?= $qs ?>&page=<?= min($totalPages, $page + 1) ?>" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600">Next</a>
            </div>
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

    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirm-modal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity" onclick="hideConfirmModal()"></div>
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
function showConfirmModal(title, message, type, formId) {
    pendingFormId = formId;
    document.getElementById('modal-title').textContent   = title;
    document.getElementById('modal-message').textContent = message;
    var btn = document.getElementById('modal-confirm-btn');
    var ia  = document.getElementById('modal-icon-accept');
    var id  = document.getElementById('modal-icon-decline');
    if (type === 'accept') {
        btn.className = 'px-4 py-2 text-sm font-medium text-white bg-green-600 dark:bg-green-500 hover:bg-green-700 dark:hover:bg-green-600 rounded-lg transition duration-150';
        btn.textContent = 'Yes, Activate';
        ia.classList.remove('hidden'); id.classList.add('hidden');
    } else {
        btn.className = 'px-4 py-2 text-sm font-medium text-white bg-red-600 dark:bg-red-500 hover:bg-red-700 dark:hover:bg-red-600 rounded-lg transition duration-150';
        btn.textContent = 'Yes, Deactivate';
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
