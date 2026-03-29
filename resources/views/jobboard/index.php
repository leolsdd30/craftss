<!-- Job Board Listing -->
<?php
// ── Time-ago helper ───────────────────────────────────────────────────
function jobTimeAgo($datetime) {
    $diff = time() - strtotime($datetime);
    if      ($diff < 60)     return 'Just now';
    elseif  ($diff < 3600)   return floor($diff / 60) . 'm ago';
    elseif  ($diff < 86400)  return floor($diff / 3600) . 'h ago';
    elseif  ($diff < 604800) return floor($diff / 86400) . 'd ago';
    else                     return date('M d', strtotime($datetime));
}

// ── Build pagination URL preserving existing filters ──────────────────
$buildUrl = function($p) use ($filters) {
    $q = array_filter([
        'q'        => $filters['search']   ?? '',
        'category' => $filters['category'] ?? '',
        'wilaya'   => $filters['wilaya']   ?? '',
        'sort'     => $filters['sort']     ?? '',
        'page'     => $p > 1 ? $p : '',
    ]);
    return APP_URL . '/jobs' . (!empty($q) ? '?' . http_build_query($q) : '');
};

$activeFilterCount = (int)!empty($filters['category'])
                   + (int)!empty($filters['wilaya'])
                   + (int)!empty($filters['sort']);
?>
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <!-- Page Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-extrabold text-gray-900">Job Board</h1>
                <p class="mt-1 text-sm text-gray-500">
                    <?php if ($totalJobs > 0): ?>
                        <span class="font-semibold text-gray-700"><?= number_format($totalJobs) ?></span> open job<?= $totalJobs !== 1 ? 's' : '' ?> available
                    <?php else: ?>
                        Browse open jobs posted by homeowners looking for skilled professionals.
                    <?php endif; ?>
                </p>
            </div>
            <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'homeowner'): ?>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="<?= APP_URL ?>/jobs/create"
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Post a Job
                </a>
            </div>
            <?php endif; ?>
        </div>

        <!-- Search & Filter Bar -->
        <div class="mb-8">
            <form action="<?= APP_URL ?>/jobs" method="GET" class="bg-white p-5 shadow-sm rounded-2xl border border-gray-100 space-y-4">

                <!-- Row 1: Search + buttons -->
                <div class="flex flex-col md:flex-row gap-3">
                    <div class="flex-grow relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="q" id="q"
                               value="<?= htmlspecialchars($filters['search'] ?? '') ?>"
                               placeholder="Search jobs by title or description..."
                               class="w-full pl-9 border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 border">
                    </div>
                    <div class="flex gap-2">
                        <button type="button"
                                onclick="document.getElementById('job-filter-section').classList.toggle('hidden')"
                                class="inline-flex justify-center items-center px-4 py-2.5 border border-gray-200 shadow-sm text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 relative">
                            <svg class="h-4 w-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                            </svg>
                            Filters
                            <?php if ($activeFilterCount > 0): ?>
                            <span class="ml-1.5 inline-flex items-center justify-center w-4 h-4 text-xs font-bold rounded-full bg-indigo-600 text-white"><?= $activeFilterCount ?></span>
                            <?php endif; ?>
                        </button>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150 shadow-sm">
                            Search
                        </button>
                    </div>
                </div>

                <!-- Row 2: Collapsible Filters -->
                <div id="job-filter-section" class="<?= $activeFilterCount > 0 ? '' : 'hidden' ?>">
                    <div class="flex flex-col sm:flex-row gap-3 pt-1">

                        <div class="sm:flex-1">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Category</label>
                            <select name="category"
                                    class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border bg-white">
                                <option value="">All Categories</option>
                                <?php foreach (["Plumbing","Electrical","Carpentry","Painting","Roofing","HVAC","Landscaping","Tiling","General Handyman"] as $cat): ?>
                                <option value="<?= $cat ?>" <?= ($filters['category'] ?? '') === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="sm:flex-1">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Location (Wilaya)</label>
                            <select name="wilaya"
                                    class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border bg-white">
                                <option value="">All Regions</option>
                                <?php
                                $wilayas = [
                                    "01 - Adrar","02 - Chlef","03 - Laghouat","04 - Oum El Bouaghi","05 - Batna",
                                    "06 - Béjaïa","07 - Biskra","08 - Béchar","09 - Blida","10 - Bouira",
                                    "11 - Tamanrasset","12 - Tébessa","13 - Tlemcen","14 - Tiaret","15 - Tizi Ouzou",
                                    "16 - Alger","17 - Djelfa","18 - Jijel","19 - Sétif","20 - Saïda",
                                    "21 - Skikda","22 - Sidi Bel Abbès","23 - Annaba","24 - Guelma","25 - Constantine",
                                    "26 - Médéa","27 - Mostaganem","28 - M'Sila","29 - Mascara","30 - Ouargla",
                                    "31 - Oran","32 - El Bayadh","33 - Illizi","34 - Bordj Bou Arréridj","35 - Boumerdès",
                                    "36 - El Tarf","37 - Tindouf","38 - Tissemsilt","39 - El Oued","40 - Khenchela",
                                    "41 - Souk Ahras","42 - Tipaza","43 - Mila","44 - Aïn Defla","45 - Naâma",
                                    "46 - Aïn Témouchent","47 - Ghardaïa","48 - Relizane","49 - Timimoun","50 - Bordj Badji Mokhtar",
                                    "51 - Ouled Djellal","52 - Béni Abbès","53 - In Salah","54 - In Guezzam","55 - Touggourt",
                                    "56 - Djanet","57 - El M'Ghair","58 - El Meniaa"
                                ];
                                foreach ($wilayas as $w): ?>
                                <option value="<?= e($w) ?>" <?= ($filters['wilaya'] ?? '') === $w ? 'selected' : '' ?>><?= e($w) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="sm:flex-1">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Sort By</label>
                            <select name="sort"
                                    class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border bg-white">
                                <option value="" <?= empty($filters['sort']) ? 'selected' : '' ?>>Newest First</option>
                                <option value="oldest" <?= ($filters['sort'] ?? '') === 'oldest' ? 'selected' : '' ?>>Oldest First</option>
                            </select>
                        </div>

                        <?php if ($activeFilterCount > 0): ?>
                        <div class="flex items-end">
                            <a href="<?= APP_URL ?>/jobs"
                               class="inline-flex items-center text-xs text-red-500 hover:text-red-700 font-medium transition-colors whitespace-nowrap pb-2">
                                <svg class="h-3.5 w-3.5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                                Clear
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

            </form>
        </div>

        <!-- Job Grid -->
        <?php if (!empty($jobs)): ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8 items-stretch">
            <?php foreach ($jobs as $job):
                $catStyles = get_category_classes($job['service_category'] ?? 'General Handyman');
                $timeAgo   = jobTimeAgo($job['created_at']);
            ?>
            <div onclick="window.location='<?= APP_URL ?>/jobs/<?= $job['id'] ?>'"
               class="group flex flex-col bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:border-indigo-100 transition-all duration-200 overflow-hidden cursor-pointer">

                <div class="p-5 flex flex-col gap-3 flex-1">

                    <!-- Top content -->
                    <div>
                        <!-- Row 1: title + message icon (craftsmen only) -->
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <h2 class="text-sm font-bold text-gray-900 leading-snug group-hover:text-indigo-600 transition-colors line-clamp-2 flex-1">
                                <?= htmlspecialchars($job['title']) ?>
                            </h2>
                            <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'craftsman'): ?>
                            <a href="<?= APP_URL ?>/messages/<?= htmlspecialchars($job['poster_username'] ?? '') ?>"
                               title="Message homeowner"
                               class="flex-shrink-0 p-1.5 rounded-lg text-gray-400 hover:text-indigo-500 hover:bg-indigo-50 transition-all duration-150">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 12.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </a>
                            <?php endif; ?>
                        </div>

                        <!-- Row 2: category badge + poster name -->
                        <div class="flex items-center gap-2 mb-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold <?= $catStyles['badge'] ?>">
                                <?= htmlspecialchars($job['service_category']) ?>
                            </span>
                            <span class="flex items-center gap-1 text-xs text-gray-400 truncate">
                                <svg class="h-3 w-3 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                <?= htmlspecialchars($job['first_name'] . ' ' . $job['last_name']) ?>
                            </span>
                        </div>

                        <!-- Row 3: Description — 2 lines -->
                        <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed mb-3">
                            <?= htmlspecialchars($job['description']) ?>
                        </p>
                    </div>
                    <div class="border-t border-gray-100 pt-3 mt-auto flex items-center justify-between gap-2">

                        <!-- Left: location + time -->
                        <div class="flex items-center gap-3 text-xs text-gray-400 min-w-0">
                            <span class="flex items-center gap-1 truncate">
                                <svg class="h-3.5 w-3.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <?= htmlspecialchars(preg_replace('/^\d{2}\s-\s/', '', $job['address'])) ?>
                            </span>
                            <span class="flex items-center gap-1 flex-shrink-0">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                <?= $timeAgo ?>
                            </span>
                        </div>

                        <!-- Right: budget -->
                        <?php if (!empty($job['budget_range'])): ?>
                        <span class="flex items-center gap-1 text-xs font-semibold text-green-600 flex-shrink-0">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                            </svg>
                            <?= htmlspecialchars($job['budget_range']) ?> DZD
                        </span>
                        <?php else: ?>
                        <span class="text-xs text-gray-300 flex-shrink-0">No budget set</span>
                        <?php endif; ?>

                    </div>

                </div>

            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex flex-1 justify-between sm:hidden">
                <?php if ($page > 1): ?>
                <a href="<?= $buildUrl($page - 1) ?>" class="relative inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
                <?php endif; ?>
                <?php if ($page < $totalPages): ?>
                <a href="<?= $buildUrl($page + 1) ?>" class="relative ml-3 inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
                <?php endif; ?>
            </div>
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <p class="text-sm text-gray-500">
                    Showing <span class="font-semibold text-gray-700"><?= (($page - 1) * 12) + 1 ?></span>–<span class="font-semibold text-gray-700"><?= min($page * 12, $totalJobs) ?></span> of <span class="font-semibold text-gray-700"><?= number_format($totalJobs) ?></span> jobs
                </p>
                <nav class="isolate inline-flex -space-x-px rounded-lg shadow-sm">
                    <?php if ($page > 1): ?>
                    <a href="<?= $buildUrl($page - 1) ?>" class="relative inline-flex items-center rounded-l-lg px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                    <a href="<?= $buildUrl($i) ?>"
                       class="relative inline-flex items-center px-4 py-2 text-sm font-semibold <?= $i === $page ? 'z-10 bg-indigo-600 text-white' : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50' ?>">
                        <?= $i ?>
                    </a>
                    <?php endfor; ?>
                    <?php if ($page < $totalPages): ?>
                    <a href="<?= $buildUrl($page + 1) ?>" class="relative inline-flex items-center rounded-r-lg px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
                    </a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <!-- Empty State -->
        <div class="bg-white rounded-2xl border-2 border-dashed border-gray-200 text-center py-16 px-8">
            <div class="mx-auto h-16 w-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-900">No jobs found</h3>
            <p class="mt-1 text-sm text-gray-400">Try adjusting your filters or search keywords.</p>
            <?php if ($activeFilterCount > 0 || !empty($filters['search'])): ?>
            <a href="<?= APP_URL ?>/jobs" class="mt-4 inline-flex items-center text-sm font-semibold text-red-500 hover:text-red-700 transition-colors">
                <svg class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                Clear all filters
            </a>
            <?php endif; ?>
            <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'homeowner'): ?>
            <div class="mt-4">
                <a href="<?= APP_URL ?>/jobs/create"
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                    Post a Job
                </a>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</div>