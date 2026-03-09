<!-- Single Job Detail View -->
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-6">
            <a href="<?= APP_URL ?>/jobs" onclick="if(document.referrer) { event.preventDefault(); window.history.back(); }" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors duration-200 bg-white px-3 py-1.5 rounded-md shadow-sm border border-gray-100 w-fit">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Go Back
            </a>
        </div>

        <!-- Success / Error Messages -->
        <?php if (isset($_GET['success'])): ?>
        <div class="rounded-md bg-green-50 p-4 mb-6">
            <div class="flex">
                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="ml-3 text-sm font-medium text-green-800">
                    <?php if ($_GET['success'] === 'quote_submitted'): ?>
                        Your quote has been submitted successfully!
                    <?php elseif ($_GET['success'] === 'quote_accepted'): ?>
                        Quote accepted! The job is now assigned.
                    <?php elseif ($_GET['success'] === 'quote_rejected'): ?>
                        Quote declined successfully.
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
        <div class="rounded-md bg-red-50 p-4 mb-6">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <p class="ml-3 text-sm font-medium text-red-800">
                    <?php if ($_GET['error'] === 'price_required'): ?>
                        Please enter your quoted price.
                    <?php elseif ($_GET['error'] === 'own_job'): ?>
                        You cannot submit a quote on your own job.
                    <?php elseif ($_GET['error'] === 'already_quoted'): ?>
                        You have already submitted a quote for this job.
                    <?php else: ?>
                        Something went wrong. Please try again.
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Job Card -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($job['title']) ?></h1>
                        <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                            <span class="flex items-center">
                                <svg class="mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                Posted by <?= htmlspecialchars($job['first_name'] . ' ' . $job['last_name']) ?>
                            </span>
                            <span class="flex items-center">
                                <svg class="mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                <?= date('F d, Y', strtotime($job['created_at'])) ?>
                            </span>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        <?= $job['status'] === 'open' ? 'bg-green-100 text-green-800' : ($job['status'] === 'assigned' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') ?>">
                        <?= ucfirst(htmlspecialchars($job['status'])) ?>
                    </span>
                </div>
            </div>

            <!-- Body -->
            <div class="px-6 py-5 space-y-6">
                <!-- Category & Budget -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                <?= htmlspecialchars($job['service_category']) ?>
                            </span>
                        </dd>
                    </div>
                    <?php if (!empty($job['budget_range'])): ?>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Budget Range</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900"><?= htmlspecialchars($job['budget_range']) ?></dd>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Location -->
                <div>
                    <dt class="text-sm font-medium text-gray-500">Location</dt>
                    <dd class="mt-1 text-sm text-gray-900 flex items-center">
                        <svg class="mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        <?= htmlspecialchars($job['address']) ?>
                    </dd>
                </div>

                <!-- Description -->
                <div>
                    <dt class="text-sm font-medium text-gray-500">Full Description</dt>
                    <dd class="mt-2 text-sm text-gray-700 leading-relaxed whitespace-pre-line"><?= htmlspecialchars($job['description']) ?></dd>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- CRAFTSMAN VIEW: Submit a Quote Form -->
        <!-- ============================================================ -->
        <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'craftsman' && $job['status'] === 'open' && $job['posted_by_user_id'] != $_SESSION['user_id']): ?>
            <?php if ($alreadyQuoted): ?>
            <div class="mt-6 bg-white shadow rounded-lg p-6">
                <div class="flex items-center space-x-3">
                    <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm font-medium text-gray-700">You have already submitted a quote for this job. The homeowner will review it shortly.</p>
                </div>
            </div>
            <?php else: ?>
            <div class="mt-6 bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-indigo-50 border-b border-indigo-100">
                    <h2 class="text-lg font-semibold text-indigo-900">Submit Your Quote</h2>
                    <p class="text-sm text-indigo-700">Tell the homeowner why you're the right person for this job.</p>
                </div>
                <form action="<?= APP_URL ?>/jobs/quote" method="POST" class="px-6 py-5 space-y-4">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                    <input type="hidden" name="job_posting_id" value="<?= $job['id'] ?>">

                    <div>
                        <label for="quoted_price" class="block text-sm font-medium text-gray-700">Your Price ($) <span class="text-red-500">*</span></label>
                        <input type="number" name="quoted_price" id="quoted_price" step="0.01" min="1" required placeholder="e.g. 150.00"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border">
                    </div>

                    <div>
                        <label for="cover_message" class="block text-sm font-medium text-gray-700">Cover Message</label>
                        <textarea name="cover_message" id="cover_message" rows="3" placeholder="Explain your experience, approach, and timeline..."
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="inline-flex items-center px-5 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                            Submit Quote
                        </button>
                    </div>
                </form>
            </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- ============================================================ -->
        <!-- HOMEOWNER VIEW: List of Received Quotes -->
        <!-- ============================================================ -->
        <?php if (isset($_SESSION['user_id']) && $job['posted_by_user_id'] == $_SESSION['user_id']): ?>
        <div class="mt-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Quotes Received (<?= count($quotes) ?>)</h2>

            <?php if (!empty($quotes)): ?>
            <div class="space-y-4">
                <?php foreach ($quotes as $quote): ?>
                <div class="bg-white shadow rounded-lg overflow-hidden border <?= $quote['status'] === 'accepted' ? 'border-green-300' : ($quote['status'] === 'rejected' ? 'border-red-200 opacity-60' : 'border-gray-200') ?>">
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($quote['first_name'] . ' ' . $quote['last_name']) ?></p>
                                <p class="text-xs text-gray-500"><?= date('M d, Y', strtotime($quote['created_at'])) ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold text-gray-900">$<?= number_format($quote['quoted_price'], 2) ?></p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium 
                                    <?= $quote['status'] === 'accepted' ? 'bg-green-100 text-green-800' : ($quote['status'] === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                                    <?= ucfirst($quote['status']) ?>
                                </span>
                            </div>
                        </div>

                        <?php if (!empty($quote['cover_message'])): ?>
                        <p class="mt-3 text-sm text-gray-600 bg-gray-50 rounded-md p-3"><?= htmlspecialchars($quote['cover_message']) ?></p>
                        <?php endif; ?>

                        <?php if ($quote['status'] === 'pending' && $job['status'] === 'open'): ?>
                        <div class="mt-4 flex justify-end space-x-3">
                            <button onclick="openRejectModal(<?= $quote['id'] ?>, '<?= htmlspecialchars($quote['first_name'] . ' ' . $quote['last_name']) ?>')"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                                Decline
                            </button>
                            <button onclick="openAcceptModal(<?= $quote['id'] ?>, '<?= htmlspecialchars($quote['first_name'] . ' ' . $quote['last_name']) ?>', '<?= number_format($quote['quoted_price'], 2) ?>')"
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 transition duration-150">
                                Accept Quote
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <p class="text-sm text-gray-500">No quotes received yet. Craftsmen will start submitting their proposals soon.</p>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- ============================================================ -->
        <!-- GUEST VIEW: Login prompt -->
        <!-- ============================================================ -->
        <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="mt-6 bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-500">
                <a href="<?= APP_URL ?>/login" class="font-medium text-indigo-600 hover:text-indigo-500">Log in</a> or 
                <a href="<?= APP_URL ?>/register" class="font-medium text-indigo-600 hover:text-indigo-500">create an account</a> to submit a quote on this job.
            </p>
        </div>
        <?php endif; ?>

    </div>
</div>

<!-- Accept Confirmation Modal -->
<div id="acceptModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeAcceptModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Accept Quote</h3>
                        <div class="mt-2 text-sm text-gray-500">
                            <p>Are you sure you want to accept the quote from <span id="craftsmanNameAccept" class="font-bold"></span> for <span class="font-bold">$<span id="quotePriceAccept"></span></span>?</p>
                            <p class="mt-2 text-indigo-600 font-medium italic">Note: All other quotes will be automatically rejected and the job will be marked as assigned.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="acceptForm" method="POST" action="" class="w-full sm:w-auto sm:ml-3">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                    <input type="hidden" name="quote_id" id="acceptQuoteId" value="">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:w-auto sm:text-sm">
                        Confirm & Proceed
                    </button>
                </form>
                <button type="button" onclick="closeAcceptModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Decline Confirmation Modal -->
<div id="rejectModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeRejectModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Decline Quote</h3>
                        <div class="mt-2 text-sm text-gray-500">
                            <p>Are you sure you want to decline the quote from <span id="craftsmanNameReject" class="font-bold"></span>?</p>
                            <p class="mt-1 text-gray-500 text-xs italic">This craftsman will be hidden from your main view.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <form id="rejectForm" method="POST" action="" class="w-full sm:w-auto sm:ml-3">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                    <input type="hidden" name="quote_id" id="rejectQuoteId" value="">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm">
                        Decline Quote
                    </button>
                </form>
                <button type="button" onclick="closeRejectModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openAcceptModal(quoteId, name, price) {
    document.getElementById('craftsmanNameAccept').innerText = name;
    document.getElementById('quotePriceAccept').innerText = price;
    document.getElementById('acceptForm').action = '<?= APP_URL ?>/jobs/accept-quote';
    document.getElementById('acceptQuoteId').value = quoteId;
    document.getElementById('acceptModal').classList.remove('hidden');
}

function closeAcceptModal() {
    document.getElementById('acceptModal').classList.add('hidden');
}

function openRejectModal(quoteId, name) {
    document.getElementById('craftsmanNameReject').innerText = name;
    document.getElementById('rejectForm').action = '<?= APP_URL ?>/jobs/reject-quote';
    document.getElementById('rejectQuoteId').value = quoteId;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
            