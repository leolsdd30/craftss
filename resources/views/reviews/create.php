<!-- Write a Review -->
<?php
// Pull craftsman profile details for the sidebar
$db = \App\Database\Database::getInstance()->getConnection();
$stmt = $db->prepare("
    SELECT cp.service_category, cp.is_verified,
           ROUND(IFNULL(AVG(r.star_rating),0),1) AS avg_rating,
           COUNT(r.id) AS total_reviews
    FROM craftsmen_profiles cp
    LEFT JOIN reviews r ON r.craftsman_id = cp.user_id
    WHERE cp.user_id = :uid
    GROUP BY cp.id
");
$stmt->execute(['uid' => $craftsman['id']]);
$meta = $stmt->fetch(\PDO::FETCH_ASSOC);

$category     = $meta['service_category'] ?? 'General Handyman';
$isVerified   = !empty($meta['is_verified']);
$avgRating    = (float)($meta['avg_rating']    ?? 0);
$totalReviews = (int)  ($meta['total_reviews'] ?? 0);

$catColors = [
    'Plumbing'=>'bg-blue-100 text-blue-700','Electrical'=>'bg-yellow-100 text-yellow-700',
    'Carpentry'=>'bg-orange-100 text-orange-700','Painting'=>'bg-pink-100 text-pink-700',
    'Roofing'=>'bg-stone-100 text-stone-700','HVAC'=>'bg-cyan-100 text-cyan-700',
    'Landscaping'=>'bg-green-100 text-green-700','Tiling'=>'bg-purple-100 text-purple-700',
    'General Handyman'=>'bg-indigo-100 text-indigo-700',
];
$badgeClass = $catColors[$category] ?? 'bg-indigo-100 text-indigo-700';

$ratingLabels = ['', 'Terrible', 'Poor', 'Average', 'Good', 'Excellent'];
$ratingColors = ['', 'text-red-500', 'text-orange-500', 'text-yellow-500', 'text-lime-500', 'text-green-500'];
?>

<div class="bg-gray-50 min-h-screen py-10">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Back -->
    <a href="<?= APP_URL ?>/homeowner/dashboard"
       class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition mb-7 group">
        <svg class="mr-1.5 h-4 w-4 group-hover:-translate-x-0.5 transition-transform duration-150"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Dashboard
    </a>

    <h1 class="text-3xl font-extrabold text-gray-900 mb-1 tracking-tight">Write a Review</h1>
    <p class="text-sm text-gray-500 mb-8">Share your experience to help other homeowners make the right choice.</p>

    <?php if (!empty($error)): ?>
    <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
        <svg class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span><?= e($error) ?></span>
    </div>
    <?php endif; ?>

    <!-- ── Two-column layout ───────────────────────────────────── -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        <!-- ════ LEFT: sidebar (1/3) ════════════════════════════ -->
        <div class="space-y-4">

            <!-- Craftsman card -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-indigo-600"></div>
                <div class="p-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">You're reviewing</p>

                    <!-- Avatar + name + badge -->
                    <div class="flex items-center gap-3 mb-4">
                        <img class="h-14 w-14 rounded-full object-cover ring-2 ring-indigo-100 flex-shrink-0"
                             src="<?= get_profile_picture_url($craftsman['profile_picture'] ?? 'default.png', $craftsman['first_name'], $craftsman['last_name']) ?>"
                             alt="<?= e($craftsman['first_name']) ?>">
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <h3 class="font-bold text-gray-900 text-base leading-tight">
                                    <?= e($craftsman['first_name'] . ' ' . $craftsman['last_name']) ?>
                                </h3>
                                <?php if ($isVerified): ?>
                                <svg style="width:1.05rem;height:1.05rem" class="text-blue-500 flex-shrink-0"
                                     viewBox="0 0 20 20" fill="currentColor" title="Verified">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <?php endif; ?>
                            </div>
                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-md text-xs font-semibold <?= $badgeClass ?>">
                                <?= e($category) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Existing rating -->
                    <?php if ($totalReviews > 0): ?>
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 text-center">
                        <div class="flex items-center justify-center gap-0.5 mb-1">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <svg class="h-3.5 w-3.5 <?= $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-200' ?>"
                                 viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <?php endfor; ?>
                        </div>
                        <p class="text-sm font-bold text-gray-900"><?= number_format($avgRating, 1) ?></p>
                        <p class="text-xs text-gray-400"><?= $totalReviews ?> review<?= $totalReviews !== 1 ? 's' : '' ?> so far</p>
                    </div>
                    <?php else: ?>
                    <div class="bg-indigo-50 rounded-xl p-3 border border-indigo-100 text-center">
                        <p class="text-xs font-semibold text-indigo-700">No reviews yet</p>
                        <p class="text-xs text-indigo-500 mt-0.5">Be the first to review!</p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($craftsman['wilaya'])): ?>
                    <div class="flex items-center gap-2 text-xs text-gray-500 mt-3">
                        <svg class="h-3.5 w-3.5 text-gray-400 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <?= e(preg_replace('/^\d{2}\s-\s/', '', $craftsman['wilaya'])) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tips card -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Writing a good review</p>
                <div class="space-y-3">
                    <?php foreach ([
                        ['⭐', 'Be honest',        'Rate based on the actual quality of the work done.'],
                        ['🔧', 'Mention the work', 'What did they fix or build? How was the result?'],
                        ['🤝', 'Professionalism',  'Were they punctual, communicative, respectful?'],
                        ['💡', 'Help others',       'Your review helps homeowners make better choices.'],
                    ] as [$emoji, $title, $desc]): ?>
                    <div class="flex items-start gap-2.5">
                        <span class="text-sm flex-shrink-0 mt-0.5"><?= $emoji ?></span>
                        <div>
                            <p class="text-xs font-semibold text-gray-800"><?= $title ?></p>
                            <p class="text-xs text-gray-400 mt-0.5"><?= $desc ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div><!-- end sidebar -->

        <!-- ════ RIGHT: form (2/3) ══════════════════════════════ -->
        <div class="lg:col-span-2">
        <form action="<?= APP_URL ?>/reviews/create" method="POST" id="review-form">
            <input type="hidden" name="csrf_token"  value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="booking_id"  value="<?= e($bookingId) ?>">
            <input type="hidden" name="star_rating" id="star_rating_input" value="0">

            <div class="space-y-5">

                <!-- Star Rating card -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-3">
                        <div class="h-7 w-7 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-xs font-bold">1</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Your Rating <span class="text-red-400">*</span></p>
                            <p class="text-xs text-gray-400">How would you rate this craftsman overall?</p>
                        </div>
                    </div>
                    <div class="px-6 py-6">

                        <!-- Stars -->
                        <div class="flex items-center gap-2 mb-4" id="star-container">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <button type="button"
                                    onclick="setRating(<?= $i ?>)"
                                    data-star="<?= $i ?>"
                                    class="star-btn p-1 rounded-lg hover:scale-110 transition-transform duration-150 focus:outline-none group">
                                <svg class="h-10 w-10 text-gray-200 transition-colors duration-150 group-hover:text-yellow-300"
                                     id="star-<?= $i ?>"
                                     viewBox="0 0 20 20" fill="#e5e7eb">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                            <?php endfor; ?>
                        </div>

                        <!-- Rating label -->
                        <div id="rating-display" class="flex items-center gap-3">
                            <div class="flex gap-0.5" id="rating-dots">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <div class="h-1.5 w-6 rounded-full bg-gray-200 transition-colors duration-150" id="dot-<?= $i ?>"></div>
                                <?php endfor; ?>
                            </div>
                            <span id="rating-label" class="text-sm font-semibold text-gray-400">Click a star to rate</span>
                        </div>

                    </div>
                </div>

                <!-- Comment card -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-3">
                        <div class="h-7 w-7 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-xs font-bold">2</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Your Review
                                <span class="text-gray-400 font-normal ml-1 text-xs">(optional)</span>
                            </p>
                            <p class="text-xs text-gray-400">Describe the quality of work, communication, and professionalism.</p>
                        </div>
                    </div>
                    <div class="px-6 py-5">
                        <textarea name="comment" id="comment" rows="5" maxlength="1000"
                            placeholder="e.g. The plumber arrived on time, fixed the issue quickly and cleanly. Very professional and reasonably priced. Would definitely hire again..."
                            class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                            oninput="document.getElementById('comment-count').textContent=this.value.length"
                        ></textarea>
                        <p class="mt-1.5 text-xs text-gray-400 text-right">
                            <span id="comment-count">0</span>/1000
                        </p>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-between gap-4 pb-4">
                    <a href="<?= APP_URL ?>/homeowner/dashboard"
                       class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" id="submit-btn" disabled
                        class="flex-1 sm:flex-none inline-flex justify-center items-center gap-2 px-8 py-2.5
                               bg-gray-300 text-white text-sm font-semibold rounded-xl shadow-sm transition
                               cursor-not-allowed">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        Submit Review
                    </button>
                </div>

            </div>
        </form>
        </div><!-- end right col -->

    </div>
</div>
</div>

<script>
var ratingLabels = ['', 'Terrible', 'Poor', 'Average', 'Good', 'Excellent'];
var dotColors    = ['', 'bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-lime-400', 'bg-green-500'];
var currentRating = 0;

function setRating(stars) {
    currentRating = stars;
    document.getElementById('star_rating_input').value = stars;

    // Update stars — always reset ALL 5 first, then fill up to selected
    for (var i = 1; i <= 5; i++) {
        var svg = document.getElementById('star-' + i);
        svg.setAttribute('fill', i <= stars ? '#facc15' : '#e5e7eb');
    }

    // Update dots
    var dotBg = ['','#f87171','#fb923c','#facc15','#a3e635','#4ade80'];
    for (var j = 1; j <= 5; j++) {
        document.getElementById('dot-' + j).style.backgroundColor =
            j <= stars ? dotBg[stars] : '#e5e7eb';
    }

    // Update label
    var label = document.getElementById('rating-label');
    label.textContent = ratingLabels[stars] + ' (' + stars + '/5)';
    label.className = 'text-sm font-semibold ' + [
        '', 'text-red-500', 'text-orange-500', 'text-yellow-500', 'text-lime-600', 'text-green-600'
    ][stars];

    // Enable submit button
    var btn = document.getElementById('submit-btn');
    btn.disabled = false;
    btn.classList.remove('bg-gray-300', 'cursor-not-allowed');
    btn.classList.add('bg-indigo-600', 'hover:bg-indigo-700', 'active:bg-indigo-800',
                      'focus:outline-none', 'focus:ring-2', 'focus:ring-offset-2', 'focus:ring-indigo-500');
}

// Hover preview
document.querySelectorAll('.star-btn').forEach(function(btn) {
    var star = parseInt(btn.dataset.star);

    btn.addEventListener('mouseenter', function() {
        if (currentRating > 0) return;
        for (var i = 1; i <= 5; i++)
            document.getElementById('star-' + i).setAttribute('fill', i <= star ? '#fde68a' : '#e5e7eb');
    });

    btn.addEventListener('mouseleave', function() {
        if (currentRating > 0) return;
        for (var i = 1; i <= 5; i++)
            document.getElementById('star-' + i).setAttribute('fill', '#e5e7eb');
    });
});
</script>