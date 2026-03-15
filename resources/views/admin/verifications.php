<!-- Admin: Craftsman Verifications -->
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <a href="<?= APP_URL ?>/admin/dashboard" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors">&larr; Back to Dashboard</a>
            <h1 class="mt-2 text-3xl font-extrabold text-gray-900">Craftsman Verification</h1>
            <p class="text-sm text-gray-500 mt-1">Review and verify craftsman profiles.</p>
        </div>

        <!-- Filter Tabs -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex space-x-8" aria-label="Tabs">
                <a href="<?= APP_URL ?>/admin/verifications?filter=pending" 
                   class="<?= $filter === 'pending' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                    Pending
                    <?php 
                        $pendingCount = 0;
                        foreach ($craftsmen as $c) { if (!$c['is_verified']) $pendingCount++; }
                    ?>
                    <?php if ($filter === 'pending' && count($craftsmen) > 0): ?>
                    <span class="ml-2 bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-0.5 rounded-full"><?= count($craftsmen) ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?= APP_URL ?>/admin/verifications?filter=verified" 
                   class="<?= $filter === 'verified' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                    Verified
                </a>
                <a href="<?= APP_URL ?>/admin/verifications?filter=all" 
                   class="<?= $filter === 'all' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors">
                    All
                </a>
            </nav>
        </div>

        <!-- Success Message -->
        <?php if (!empty($_GET['success'])): ?>
        <div class="rounded-lg bg-green-50 p-4 mb-6 border border-green-200">
            <div class="flex">
                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="ml-3 text-sm font-medium text-green-800">Verification status updated successfully!</p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Craftsmen Grid -->
        <?php if (empty($craftsmen)): ?>
        <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-300 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900">No craftsmen found</h3>
            <p class="mt-1 text-sm text-gray-500">
                <?= $filter === 'pending' ? 'No pending verifications. All caught up!' : 'No craftsmen match this filter.' ?>
            </p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($craftsmen as $c): ?>
            <div class="bg-white rounded-xl shadow-sm border <?= $c['is_verified'] ? 'border-green-200' : 'border-yellow-200' ?> overflow-hidden hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <!-- Profile Header -->
                    <div class="flex items-center space-x-4 mb-4">
                        <img src="<?= get_profile_picture_url($c['profile_picture'] ?? 'default.png', $c['first_name'], $c['last_name']) ?>" 
                             alt="<?= htmlspecialchars($c['first_name']) ?>" 
                             class="w-14 h-14 rounded-full object-cover border-2 <?= $c['is_verified'] ? 'border-green-300' : 'border-yellow-300' ?> shadow-sm">
                        <div class="flex-1 min-w-0">
                            <a href="<?= APP_URL ?>/profile/<?= $c['username'] ?>" class="text-base font-bold text-gray-900 hover:text-indigo-600 transition-colors truncate block">
                                <?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?>
                            </a>
                            <p class="text-sm text-indigo-600 font-semibold"><?= htmlspecialchars($c['service_category']) ?></p>
                        </div>
                        <?php if ($c['is_verified']): ?>
                        <span class="flex-shrink-0 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="mr-1 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Verified
                        </span>
                        <?php else: ?>
                        <span class="flex-shrink-0 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                        <?php endif; ?>
                    </div>

                    <!-- Details -->
                    <div class="space-y-2 text-sm text-gray-600 mb-5">
                        <?php if (!empty($c['wilaya'])): ?>
                        <div class="flex items-center">
                            <svg class="mr-2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                            <?= htmlspecialchars($c['wilaya']) ?>
                        </div>
                        <?php endif; ?>
                        <div class="flex items-center">
                            <svg class="mr-2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <?= number_format($c['hourly_rate'], 2) ?> DZD/hr
                        </div>
                        <div class="flex items-center">
                            <svg class="mr-2 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Joined <?= date('M d, Y', strtotime($c['user_created'])) ?>
                        </div>
                        <?php if (!empty($c['bio'])): ?>
                        <p class="text-gray-500 text-xs line-clamp-2 mt-2 italic">"<?= htmlspecialchars(substr($c['bio'], 0, 120)) ?><?= strlen($c['bio']) > 120 ? '...' : '' ?>"</p>
                        <?php endif; ?>
                    </div>

                    <!-- Action -->
                    <form id="verify-form-<?= $c['user_id'] ?>" method="POST" action="<?= APP_URL ?>/admin/verifications/toggle">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="user_id" value="<?= $c['user_id'] ?>">
                        <?php if ($c['is_verified']): ?>
                        <button type="button" class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 transition duration-150"
                                onclick="showConfirmModal('Remove Verification', 'Are you sure you want to remove the verified status from <?= htmlspecialchars($c['first_name']) ?>?', 'decline', 'verify-form-<?= $c['user_id'] ?>')">
                            <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Remove Verification
                        </button>
                        <?php else: ?>
                        <button type="button" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition duration-150"
                                onclick="showConfirmModal('Verify Craftsman', 'Are you sure you want to verify <?= htmlspecialchars($c['first_name']) ?> as a professional?', 'accept', 'verify-form-<?= $c['user_id'] ?>')">
                            <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Verify Craftsman
                        </button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

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
