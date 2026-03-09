<!-- Admin: User Management -->
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <a href="<?= APP_URL ?>/admin/dashboard" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors">&larr; Back to Dashboard</a>
                    <h1 class="mt-2 text-3xl font-extrabold text-gray-900">User Management</h1>
                    <p class="text-sm text-gray-500 mt-1">Search, filter, and manage all platform users.</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
            <form method="GET" action="<?= APP_URL ?>/admin/users" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Name or email..." 
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Role</label>
                    <select name="role" class="border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border bg-white">
                        <option value="">All Roles</option>
                        <option value="homeowner" <?= $roleFilter === 'homeowner' ? 'selected' : '' ?>>Homeowner</option>
                        <option value="craftsman" <?= $roleFilter === 'craftsman' ? 'selected' : '' ?>>Craftsman</option>
                        <option value="admin" <?= $roleFilter === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                    <select name="status" class="border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border bg-white">
                        <option value="">All</option>
                        <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $statusFilter === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                    <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Filter
                </button>
                <?php if (!empty($search) || !empty($roleFilter) || !empty($statusFilter)): ?>
                <a href="<?= APP_URL ?>/admin/users" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Success Message -->
        <?php if (!empty($_GET['success'])): ?>
        <div class="rounded-lg bg-green-50 p-4 mb-6 border border-green-200">
            <div class="flex">
                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="ml-3 text-sm font-medium text-green-800">User status updated successfully!</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Users Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <p class="text-sm text-gray-500"><?= count($users) ?> user<?= count($users) !== 1 ? 's' : '' ?> found</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verified</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500">No users found matching your criteria.</td>
                        </tr>
                        <?php endif; ?>
                        <?php foreach ($users as $u): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 font-mono">#<?= $u['id'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="<?= APP_URL ?>/profile?id=<?= $u['id'] ?>" class="text-sm font-medium text-gray-900 hover:text-indigo-600 transition-colors">
                                    <?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?>
                                </a>
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
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $rc ?> capitalize"><?= $u['role'] ?></span>
                                <?php if ($u['role'] === 'craftsman' && !empty($u['service_category'])): ?>
                                <span class="block text-xs text-gray-400 mt-0.5"><?= htmlspecialchars($u['service_category']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($u['role'] === 'craftsman'): ?>
                                    <?php if (!empty($u['is_verified'])): ?>
                                        <span class="inline-flex items-center text-green-600">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs text-yellow-600 font-medium">Pending</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($u['is_active']): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                <form id="status-form-<?= $u['id'] ?>" method="POST" action="<?= APP_URL ?>/admin/users/toggle-status" class="inline">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                    <?php if ($u['is_active']): ?>
                                    <button type="button" class="text-xs font-medium text-red-600 hover:text-red-800 transition-colors bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg" 
                                            onclick="showConfirmModal('Deactivate User', 'Are you sure you want to deactivate <?= htmlspecialchars($u['first_name']) ?>? They will not be able to log in.', 'decline', 'status-form-<?= $u['id'] ?>')">
                                        Deactivate
                                    </button>
                                    <?php else: ?>
                                    <button type="button" class="text-xs font-medium text-green-600 hover:text-green-800 transition-colors bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg"
                                            onclick="showConfirmModal('Activate User', 'Are you sure you want to activate <?= htmlspecialchars($u['first_name']) ?>?', 'accept', 'status-form-<?= $u['id'] ?>')">
                                        Activate
                                    </button>
                                    <?php endif; ?>
                                </form>
                                <?php else: ?>
                                <span class="text-xs text-gray-400">You</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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

<script>
let currentActionFormId = null;

function showConfirmModal(title, message, type, formId) {
    document.getElementById('modal-title').innerText = title;
    document.getElementById('modal-message').innerText = message;
    currentActionFormId = formId;

    const acceptIcon = document.getElementById('modal-icon-accept');
    const declineIcon = document.getElementById('modal-icon-decline');
    const confirmBtn = document.getElementById('modal-confirm-btn');

    if (type === 'accept') {
        acceptIcon.classList.remove('hidden');
        declineIcon.classList.add('hidden');
        confirmBtn.className = 'px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition duration-150';
    } else {
        acceptIcon.classList.add('hidden');
        declineIcon.classList.remove('hidden');
        confirmBtn.className = 'px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition duration-150';
    }

    document.getElementById('confirm-modal').classList.remove('hidden');
}

function hideConfirmModal() {
    document.getElementById('confirm-modal').classList.add('hidden');
    currentActionFormId = null;
}

function confirmAction() {
    if (currentActionFormId) {
        document.getElementById(currentActionFormId).submit();
    }
}
</script>
