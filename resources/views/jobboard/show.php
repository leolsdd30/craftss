<!-- Job Board: Show Job -->
<?php
$isOwner      = isset($_SESSION['user_id']) && $job['posted_by_user_id'] == $_SESSION['user_id'];
$isCraftsman  = isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'craftsman';
$isGuest      = !isset($_SESSION['user_id']);
$canQuote     = $isCraftsman && $job['status'] === 'open' && !$isOwner;

$catStyles = get_category_classes($job['service_category'] ?? 'General Handyman');

$diff = time() - strtotime($job['created_at']);
if ($diff < 3600)        $timeAgo = floor($diff / 60) . 'm ago';
elseif ($diff < 86400)   $timeAgo = floor($diff / 3600) . 'h ago';
elseif ($diff < 604800)  $timeAgo = floor($diff / 86400) . 'd ago';
elseif ($diff < 2592000) $timeAgo = floor($diff / 604800) . 'w ago';
else                     $timeAgo = date('M d, Y', strtotime($job['created_at']));
$hideFooter = true;
?>

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Back -->
        <a href="<?= APP_URL ?>/jobs" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors mb-6">
            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Job Board
        </a>

        <!-- Flash Messages -->
        <?php if (!empty($_GET['success'])): ?>
        <div class="rounded-xl bg-green-50 border border-green-200 p-4 mb-6 flex items-center gap-3">
            <svg class="h-5 w-5 text-green-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <p class="text-sm font-medium text-green-800">
                <?php
                    switch($_GET['success']) {
                        case 'quote_submitted': echo 'Your quote has been submitted! The homeowner will review it shortly.'; break;
                        case 'quote_accepted':  echo 'Quote accepted! The craftsman has been notified.'; break;
                        case 'quote_rejected':  echo 'Quote declined.'; break;
                        default: echo 'Action completed successfully.';
                    }
                ?>
            </p>
        </div>
        <?php endif; ?>

        <?php if (!empty($_GET['error'])): ?>
        <div class="rounded-xl bg-red-50 border border-red-200 p-4 mb-6 flex items-center gap-3">
            <svg class="h-5 w-5 text-red-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            <p class="text-sm font-medium text-red-800">
                <?php
                    switch($_GET['error']) {
                        case 'price_required':  echo 'Please enter your price.'; break;
                        case 'own_job':         echo 'You cannot submit a quote on your own job.'; break;
                        case 'already_quoted':  echo 'You have already submitted a quote for this job.'; break;
                        case 'submit_failed':   echo 'Something went wrong. Please try again.'; break;
                        default: echo htmlspecialchars($_GET['error']);
                    }
                ?>
            </p>
        </div>
        <?php endif; ?>

        <!-- Two-column layout -->
        <div class="flex flex-col lg:flex-row gap-8 items-start">

            <!-- LEFT: Job Details -->
            <div class="flex-1 min-w-0 space-y-6">

                <!-- Job Header Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="h-1.5 <?= $catStyles['border'] ?? 'bg-indigo-500' ?>"></div>
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center gap-2 mb-4 flex-wrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                <?= $job['status'] === 'open' ? 'bg-green-100 text-green-800' : ($job['status'] === 'assigned' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600') ?>">
                                <?= $job['status'] === 'open' ? '● Open' : ucfirst($job['status']) ?>
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold <?= $catStyles['badge'] ?>">
                                <?= htmlspecialchars($job['service_category']) ?>
                            </span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-tight mb-4 break-words">
                            <?= htmlspecialchars($job['title']) ?>
                        </h1>
                        <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-gray-500">
                            <span class="flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                                <?= htmlspecialchars($job['first_name'] . ' ' . $job['last_name']) ?>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                <?= htmlspecialchars(preg_replace('/^\d{2}\s-\s/', '', $job['address'])) ?>
                            </span>
                            <span class="flex items-center gap-1.5 text-gray-400">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                <?= $timeAgo ?>
                            </span>
                            <?php if (!empty($job['budget_range'])): ?>
                            <span class="flex items-center gap-1.5 font-semibold text-gray-700">
                                <svg class="h-4 w-4 text-green-500" viewBox="0 0 20 20" fill="currentColor"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/></svg>
                                <?= htmlspecialchars($job['budget_range']) ?> DZD
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Job Description
                    </h2>
                    <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line break-words">
                        <?= htmlspecialchars($job['description']) ?>
                    </div>
                </div>

                <!-- Image Gallery -->
                <?php
                    $jobImages = [];
                    if (!empty($job['images'])) {
                        $decoded = is_string($job['images']) ? json_decode($job['images'], true) : $job['images'];
                        if (is_array($decoded)) $jobImages = $decoded;
                    }
                ?>
                <?php if (!empty($jobImages)): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Photos
                        </h2>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100"><?= count($jobImages) ?> photo<?= count($jobImages) !== 1 ? 's' : '' ?></span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4">
                        <?php foreach ($jobImages as $index => $img): ?>
                        <div class="relative rounded-xl overflow-hidden bg-gray-100 cursor-pointer group shadow-sm hover:shadow-lg transition-all duration-300 <?= $index === 0 ? 'col-span-2 row-span-2 aspect-[4/3] sm:aspect-auto' : 'aspect-square min-h-[120px]' ?>"
                             onclick="openLightbox(<?= $index ?>)">
                            <img src="<?= APP_URL . '/' . htmlspecialchars($img) ?>"
                                 alt="Job photo <?= $index + 1 ?>"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <!-- Premium Hover Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <div class="bg-white/20 backdrop-blur-md p-3 rounded-full transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 ring-1 ring-white/30 hidden sm:block">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Lightbox -->
                <div id="portfolio-lightbox" class="fixed inset-0 z-[60] hidden">
                    <div class="fixed inset-0 bg-black bg-opacity-95 backdrop-blur-sm" onclick="closeLightbox()"></div>
                    <div class="fixed inset-0 flex items-center justify-center p-4">
                        <button onclick="closeLightbox()" class="absolute top-4 right-4 z-[70] text-white hover:text-gray-300 transition p-2 bg-white/10 rounded-full hover:bg-white/20">
                            <svg class="h-6 w-6 sm:h-7 sm:w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        <div class="absolute top-4 left-4 z-[70] text-white text-xs sm:text-sm font-medium bg-white/10 px-3 py-1.5 rounded-full">
                            <span id="lightbox-counter"></span>
                        </div>
                        <?php if (count($jobImages) > 1): ?>
                        <button onclick="event.stopPropagation(); lbNav(-1)" class="absolute left-2 sm:left-6 z-[70] text-white p-2 sm:p-3 rounded-full bg-white/10 hover:bg-white/20 transition backdrop-blur-sm">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <?php endif; ?>
                        <div class="relative flex min-h-0 min-w-0" onclick="event.stopPropagation()">
                            <img id="lightbox-image" src="" alt="Portfolio" class="max-h-[85vh] max-w-[90vw] object-contain rounded-lg shadow-2xl origin-center transition-opacity duration-300">
                        </div>
                        <?php if (count($jobImages) > 1): ?>
                        <button onclick="event.stopPropagation(); lbNav(1)" class="absolute right-2 sm:right-6 z-[70] text-white p-2 sm:p-3 rounded-full bg-white/10 hover:bg-white/20 transition backdrop-blur-sm">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- HOMEOWNER: Quotes received -->
                <?php if ($isOwner): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <h2 class="text-base font-bold text-gray-900 mb-5 flex items-center gap-2">
                        <svg class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Quotes Received
                        <span class="ml-1 bg-indigo-100 text-indigo-700 text-xs font-bold px-2 py-0.5 rounded-full"><?= count($quotes) ?></span>
                    </h2>
                    <?php if (!empty($quotes)): ?>
                    <div class="space-y-4">
                        <?php foreach ($quotes as $quote): ?>
                        <div class="rounded-xl border <?= $quote['status'] === 'accepted' ? 'border-green-200 bg-green-50' : ($quote['status'] === 'rejected' ? 'border-gray-100 bg-gray-50 opacity-60' : 'border-gray-200 bg-white') ?> p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-center gap-3 min-w-0">
                                    <img class="h-10 w-10 rounded-full object-cover flex-shrink-0"
                                         src="<?= get_profile_picture_url($quote['profile_picture'] ?? 'default.png', $quote['first_name'], $quote['last_name']) ?>"
                                         alt="<?= htmlspecialchars($quote['first_name']) ?>">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <a href="<?= APP_URL ?>/profile/<?= htmlspecialchars($quote['username']) ?>"
                                               class="text-sm font-bold text-gray-900 hover:text-indigo-600 transition-colors truncate">
                                                <?= htmlspecialchars($quote['first_name'] . ' ' . $quote['last_name']) ?>
                                            </a>
                                            <?php if (!empty($quote['is_verified'])): ?>
                                            <svg class="h-4 w-4 text-blue-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" title="Verified">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-0.5"><?= date('M d, Y', strtotime($quote['created_at'])) ?></p>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-xl font-extrabold text-gray-900"><?= number_format($quote['quoted_price'], 0) ?> <span class="text-sm font-semibold text-gray-400">DZD</span></p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                        <?= $quote['status'] === 'accepted' ? 'bg-green-100 text-green-800' : ($quote['status'] === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-800') ?>">
                                        <?= ucfirst($quote['status']) ?>
                                    </span>
                                </div>
                            </div>
                            <?php if (!empty($quote['cover_message'])): ?>
                            <div class="mt-3 bg-white rounded-lg border border-gray-100 p-3">
                                <p class="text-sm text-gray-600 italic leading-relaxed">"<?= htmlspecialchars($quote['cover_message']) ?>"</p>
                            </div>
                            <?php endif; ?>
                            <?php if ($quote['status'] === 'pending' && $job['status'] === 'open'): ?>
                            <div class="mt-4 flex items-center justify-between">
                                <a href="<?= APP_URL ?>/profile/<?= htmlspecialchars($quote['username']) ?>"
                                   class="text-xs font-medium text-indigo-600 hover:text-indigo-700 transition-colors">
                                    View Profile →
                                </a>
                                <div class="flex gap-2">
                                    <button onclick="openRejectModal(<?= $quote['id'] ?>, '<?= htmlspecialchars($quote['first_name']) ?>')"
                                            class="px-4 py-1.5 text-xs font-semibold rounded-lg text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition duration-150">
                                        Decline
                                    </button>
                                    <button onclick="openAcceptModal(<?= $quote['id'] ?>, '<?= htmlspecialchars($quote['first_name'] . ' ' . $quote['last_name']) ?>', '<?= number_format($quote['quoted_price'], 0) ?>')"
                                            class="px-4 py-1.5 text-xs font-semibold rounded-lg text-white bg-green-600 hover:bg-green-700 transition duration-150">
                                        Accept Quote
                                    </button>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-10 border-2 border-dashed border-gray-200 rounded-xl">
                        <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-sm text-gray-500">No quotes yet. Craftsmen will start submitting proposals soon.</p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- CRAFTSMAN: Quote status -->
                <?php if ($isCraftsman && $alreadyQuoted): ?>
                    <?php if (($myQuoteStatus ?? null) === 'rejected'): ?>
                    <!-- Rejected -->
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 flex items-start gap-4">
                        <div class="h-10 w-10 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-700">Quote Not Selected</h3>
                            <p class="text-sm text-gray-500 mt-0.5">The homeowner went with another craftsman for this job.</p>
                            <a href="<?= APP_URL ?>/jobs" class="inline-flex items-center mt-3 text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">Browse more jobs →</a>
                        </div>
                    </div>
                    <?php elseif (($myQuoteStatus ?? null) === 'accepted'): ?>
                    <!-- Accepted -->
                    <div class="bg-green-50 border border-green-200 rounded-2xl p-6 flex items-start gap-4">
                        <div class="h-10 w-10 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-green-900">Quote Accepted!</h3>
                            <p class="text-sm text-green-700 mt-0.5">Congratulations! The homeowner accepted your quote for this job.</p>
                            <a href="<?= APP_URL ?>/craftsman/dashboard#active" class="inline-flex items-center mt-3 text-sm font-semibold text-green-700 hover:text-green-800 transition-colors">View active jobs →</a>
                        </div>
                    </div>
                    <?php else: ?>
                    <!-- Pending -->
                    <div class="bg-green-50 border border-green-200 rounded-2xl p-6 flex items-start gap-4">
                        <div class="h-10 w-10 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-green-900">Quote Submitted!</h3>
                            <p class="text-sm text-green-700 mt-0.5">The homeowner will review your quote and get back to you.</p>
                            <a href="<?= APP_URL ?>/craftsman/dashboard#quotes" class="inline-flex items-center mt-3 text-sm font-semibold text-green-700 hover:text-green-800 transition-colors">View my quotes →</a>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- GUEST: Login prompt -->
                <?php if ($isGuest): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                    <svg class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <p class="text-sm text-gray-600 mb-4">
                        <a href="<?= APP_URL ?>/login" class="font-semibold text-indigo-600 hover:text-indigo-500">Log in</a>
                        or <a href="<?= APP_URL ?>/register" class="font-semibold text-indigo-600 hover:text-indigo-500">create an account</a> to submit a quote.
                    </p>
                </div>
                <?php endif; ?>

            </div><!-- end left col -->

            <!-- RIGHT: Sticky Sidebar -->
            <div class="w-full lg:w-80 flex-shrink-0 lg:sticky lg:top-24 space-y-4">

                <!-- Info Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Job Details</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Category</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold <?= $catStyles['badge'] ?>">
                                <?= htmlspecialchars($job['service_category']) ?>
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Location</span>
                            <span class="font-medium text-gray-700"><?= htmlspecialchars(preg_replace('/^\d{2}\s-\s/', '', $job['address'])) ?></span>
                        </div>
                        <?php if (!empty($job['budget_range'])): ?>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Budget</span>
                            <span class="font-semibold text-green-700"><?= htmlspecialchars($job['budget_range']) ?> DZD</span>
                        </div>
                        <?php endif; ?>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Posted</span>
                            <span class="text-gray-500"><?= $timeAgo ?></span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Status</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                <?= $job['status'] === 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' ?>">
                                <?= ucfirst($job['status']) ?>
                            </span>
                        </div>
                        <?php if ($isOwner): ?>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Quotes</span>
                            <span class="font-semibold text-indigo-600"><?= count($quotes) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Craftsman Action Buttons -->
                <?php if ($isCraftsman && !$isOwner): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-3">
                    <?php if ($canQuote && !$alreadyQuoted): ?>
                    <button onclick="openQuoteModal()"
                            class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition duration-150">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Submit Quote
                    </button>
                    <?php elseif ($alreadyQuoted && ($myQuoteStatus ?? null) === 'rejected'): ?>
                    <div class="w-full inline-flex items-center justify-center px-4 py-3 text-sm font-semibold rounded-xl text-gray-500 bg-gray-50 border border-gray-200">
                        Quote Not Selected
                    </div>
                    <?php elseif ($alreadyQuoted): ?>
                    <div class="w-full inline-flex items-center justify-center px-4 py-3 text-sm font-semibold rounded-xl text-green-700 bg-green-50 border border-green-200">
                        <svg class="mr-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Quote Submitted
                    </div>
                    <?php elseif ($job['status'] !== 'open'): ?>
                    <div class="w-full inline-flex items-center justify-center px-4 py-3 text-sm font-semibold rounded-xl text-gray-500 bg-gray-50 border border-gray-200">
                        Job is no longer open
                    </div>
                    <?php endif; ?>
                    <a href="<?= APP_URL ?>/messages/<?= htmlspecialchars($job['poster_username'] ?? '') ?>"
                       class="w-full inline-flex items-center justify-center px-4 py-3 border border-gray-200 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                        <svg class="mr-2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Message Homeowner
                    </a>
                </div>
                <?php endif; ?>

                <!-- Guest Action -->
                <?php if ($isGuest): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-3">
                    <a href="<?= APP_URL ?>/register"
                       class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition duration-150">
                        Sign up to Quote
                    </a>
                    <a href="<?= APP_URL ?>/login"
                       class="w-full inline-flex items-center justify-center px-4 py-3 border border-gray-200 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                        Log In
                    </a>
                </div>
                <?php endif; ?>

                <!-- Owner: Action Buttons -->
                <?php if ($isOwner): ?>
                <div class="space-y-3">
                    <a href="<?= APP_URL ?>/homeowner/dashboard#jobs"
                       class="w-full inline-flex items-center justify-center px-4 py-3 border border-gray-200 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition duration-150 shadow-sm">
                        <svg class="mr-2 h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Manage in Dashboard
                    </a>
                    
                    <?php if ($job['status'] === 'open'): ?>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="<?= APP_URL ?>/jobs/edit/<?= $job['id'] ?>?source=job_view" class="inline-flex items-center justify-center px-4 py-2 border border-amber-200 text-sm font-semibold rounded-xl text-amber-700 bg-amber-50 hover:bg-amber-100 transition duration-150">
                            Edit Job
                        </a>
                        <button onclick="openDeleteModal()" class="inline-flex items-center justify-center px-4 py-2 border border-red-200 text-sm font-semibold rounded-xl text-red-700 bg-red-50 hover:bg-red-100 transition duration-150">
                            Cancel Job
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            </div><!-- end sidebar -->

        </div><!-- end two-col -->
    </div>
</div>

<!-- QUOTE MODAL -->
<?php if ($canQuote && !$alreadyQuoted): ?>
<div id="quote-modal" class="fixed inset-0 z-50" style="display:none;">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-60" onclick="closeQuoteModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Submit Your Quote</h3>
                        <p class="text-xs text-gray-500 mt-0.5 truncate max-w-xs"><?= htmlspecialchars($job['title']) ?></p>
                    </div>
                </div>
                <button onclick="closeQuoteModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <!-- Body -->
            <form action="<?= APP_URL ?>/jobs/quote" method="POST" class="px-6 py-5 space-y-4">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                <input type="hidden" name="job_posting_id" value="<?= $job['id'] ?>">
                <!-- Price -->
                <div>
                    <label for="modal_quoted_price" class="block text-sm font-semibold text-gray-700 mb-1">
                        Your Price <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" name="quoted_price" id="modal_quoted_price"
                               step="1" min="1" required placeholder="e.g. 5000"
                               class="w-full border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2.5 pr-16">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                            <span class="text-sm font-semibold text-gray-400">DZD</span>
                        </div>
                    </div>
                    <?php if (!empty($job['budget_range'])): ?>
                    <p class="mt-1 text-xs text-gray-400">Homeowner's budget: <span class="font-semibold text-gray-600"><?= htmlspecialchars($job['budget_range']) ?> DZD</span></p>
                    <?php endif; ?>
                </div>
                <!-- Cover Message — bigger, no tips -->
                <div>
                    <label for="modal_cover_message" class="block text-sm font-semibold text-gray-700 mb-1">
                        Cover Message <span class="text-gray-400 font-normal">(recommended)</span>
                    </label>
                    <textarea name="cover_message" id="modal_cover_message" rows="7"
                              maxlength="1000"
                              placeholder="Introduce yourself, describe your experience with this type of work, your approach, what's included in your price, and estimated timeline..."
                              class="w-full border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2.5"
                              oninput="document.getElementById('modal-char-count').textContent = this.value.length"></textarea>
                    <div class="flex justify-between mt-1">
                        <p class="text-xs text-gray-400">A strong message greatly increases your chances of being hired.</p>
                        <p class="text-xs text-gray-400"><span id="modal-char-count">0</span>/1000</p>
                    </div>
                </div>
                <!-- Actions -->
                <div class="flex items-center justify-between pt-1">
                    <button type="button" onclick="closeQuoteModal()"
                            class="text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">Cancel</button>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-xl shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Submit Quote
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ACCEPT MODAL -->
<?php if ($isOwner): ?>
<div id="acceptModal" class="fixed inset-0 z-50" style="display:none;">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-60" onclick="closeAcceptModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
            <div class="flex items-center mb-4">
                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                    <svg class="h-6 w-6 text-green-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Accept Quote</h3>
            </div>
            <p id="acceptModalText" class="text-sm text-gray-600 mb-6"></p>
            <div class="flex justify-end gap-3">
                <button onclick="closeAcceptModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Cancel</button>
                <form method="POST" action="<?= APP_URL ?>/jobs/accept-quote" class="inline">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                    <input type="hidden" name="quote_id" id="acceptQuoteId" value="">
                    <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-green-600 hover:bg-green-700 rounded-lg transition">Confirm & Accept</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- REJECT MODAL -->
<div id="rejectModal" class="fixed inset-0 z-50" style="display:none;">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-60" onclick="closeRejectModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
            <div class="flex items-center mb-4">
                <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                    <svg class="h-6 w-6 text-red-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Decline Quote</h3>
            </div>
            <p id="rejectModalText" class="text-sm text-gray-600 mb-6"></p>
            <div class="flex justify-end gap-3">
                <button onclick="closeRejectModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Cancel</button>
                <form method="POST" action="<?= APP_URL ?>/jobs/reject-quote" class="inline">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                    <input type="hidden" name="quote_id" id="rejectQuoteId" value="">
                    <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition">Yes, Decline</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- DELETE JOB MODAL -->
<?php if ($isOwner && $job['status'] === 'open'): ?>
<div id="deleteModal" class="fixed inset-0 z-50" style="display:none;">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-60" onclick="closeDeleteModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
            <div class="flex items-center mb-4">
                <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center mr-3">
                    <svg class="h-6 w-6 text-red-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Cancel Job Post</h3>
            </div>
            <p class="text-sm text-gray-600 mb-6">Are you sure you want to cancel this job? It will be removed from the public job board and no new craftsmen will be able to submit quotes. This cannot be undone.</p>
            <div class="flex justify-end gap-3">
                <button onclick="closeDeleteModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Nevermind</button>
                <form method="POST" action="<?= APP_URL ?>/jobs/delete" class="inline">
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                    <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
                    <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition">Yes, Cancel Job</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
function openQuoteModal() {
    document.getElementById('quote-modal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}
function closeQuoteModal() {
    document.getElementById('quote-modal').style.display = 'none';
    document.body.style.overflow = '';
}
function openAcceptModal(quoteId, name, price) {
    document.getElementById('acceptQuoteId').value = quoteId;
    document.getElementById('acceptModalText').textContent =
        'Accept ' + name + '\u2019s quote of ' + price + ' DZD? All other quotes will be declined and the job will be assigned.';
    document.getElementById('acceptModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}
function closeAcceptModal() {
    document.getElementById('acceptModal').style.display = 'none';
    document.body.style.overflow = '';
}
function openRejectModal(quoteId, name) {
    document.getElementById('rejectQuoteId').value = quoteId;
    document.getElementById('rejectModalText').textContent =
        'Are you sure you want to decline ' + name + '\u2019s quote? This action cannot be undone.';
    document.getElementById('rejectModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}
function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
    document.body.style.overflow = '';
}
function openDeleteModal() {
    document.getElementById('deleteModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}
function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeQuoteModal();
        <?php if ($isOwner): ?>
        closeAcceptModal();
        closeRejectModal();
        if (typeof closeDeleteModal === 'function') closeDeleteModal();
        <?php endif; ?>
        <?php if (!empty($jobImages)): ?>
        closeLightbox();
        <?php endif; ?>
    }
    <?php if (!empty($jobImages) && count($jobImages) > 1): ?>
    if (e.key === 'ArrowLeft')  lbNav(-1);
    if (e.key === 'ArrowRight') lbNav(1);
    <?php endif; ?>
});

<?php if (!empty($jobImages)): ?>
/* Gallery Lightbox */
const portfolioImages = <?= json_encode(array_map(fn($p) => APP_URL . '/' . $p, $jobImages)) ?>;
let currentLightboxIndex = 0;

function openLightbox(index) {
    currentLightboxIndex = index;
    const lightbox = document.getElementById('portfolio-lightbox');
    const image = document.getElementById('lightbox-image');
    const counter = document.getElementById('lightbox-counter');
    
    // Set image with a slight fade effect
    image.style.opacity = '0';
    image.src = portfolioImages[index];
    image.onload = () => { image.style.opacity = '1'; };
    
    if (counter && portfolioImages.length > 1) {
        counter.textContent = `${index + 1} / ${portfolioImages.length}`;
    } else if (counter) {
        counter.style.display = 'none';
    }
    
    lightbox.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('portfolio-lightbox').classList.add('hidden');
    document.body.style.overflow = '';
}

function lbNav(direction) {
    currentLightboxIndex = (currentLightboxIndex + direction + portfolioImages.length) % portfolioImages.length;
    
    const image = document.getElementById('lightbox-image');
    image.style.opacity = '0';
    
    setTimeout(() => {
        image.src = portfolioImages[currentLightboxIndex];
        image.onload = () => { image.style.opacity = '1'; };
        
        const counter = document.getElementById('lightbox-counter');
        if (counter) counter.textContent = `${currentLightboxIndex + 1} / ${portfolioImages.length}`;
    }, 150);
}
<?php endif; ?>
</script>
