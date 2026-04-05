<!-- Job Board Listing -->
<?php
$categories = ["Plumbing","Electrical","Carpentry","Painting","Roofing","HVAC","Landscaping","Tiling","General Handyman"];
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

$selectedCat    = $filters['category'] ?? '';
$selectedWilaya = $filters['wilaya']   ?? '';
$selectedSort   = $filters['sort']     ?? '';
$searchQuery    = $filters['search']   ?? '';

$activeFilterCount = (int)!empty($selectedCat)
                   + (int)!empty($selectedWilaya)
                   + (int)!empty($selectedSort);
?>

<div class="bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-200">
<div class="max-w-screen-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Find the Perfect Job</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                <?php if ($totalJobs > 0): ?>
                    <span class="font-semibold text-gray-700 dark:text-gray-300"><?= number_format($totalJobs) ?></span> open job<?= $totalJobs !== 1 ? 's' : '' ?> available for you to browse and apply to.
                <?php else: ?>
                    Browse hundreds of open projects posted by homeowners looking for skilled professionals.
                <?php endif; ?>
            </p>
        </div>
        <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'homeowner'): ?>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="<?= APP_URL ?>/jobs/create"
               class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Post a Job
            </a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Active filter tags -->
    <?php if ($activeFilterCount > 0): ?>
    <div class="flex flex-wrap items-center gap-2 mb-5" id="active-tags">
        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mr-1">Active:</span>
        <?php if (!empty($selectedCat)): ?>
            <?php $tagCat = get_category_classes($selectedCat); ?>
            <a href="<?= APP_URL ?>/jobs?<?= http_build_query(array_diff_key($_GET, ['category'=>''])) ?>"
               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold <?= $tagCat['badge'] ?> hover:opacity-80 transition">
                <?= e($selectedCat) ?>
                <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </a>
        <?php endif; ?>
        <?php if (!empty($selectedWilaya)): ?>
            <a href="<?= APP_URL ?>/jobs?<?= http_build_query(array_diff_key($_GET, ['wilaya'=>''])) ?>"
               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:opacity-80 transition">
                <svg class="w-3 h-3 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                <?= e(preg_replace('/^\d{2}\s-\s/', '', $selectedWilaya)) ?>
                <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </a>
        <?php endif; ?>
        <?php if (!empty($selectedSort)): ?>
            <?php $sortLabels = ['oldest'=>'Oldest First']; ?>
            <a href="<?= APP_URL ?>/jobs?<?= http_build_query(array_diff_key($_GET, ['sort'=>''])) ?>"
               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 hover:opacity-80 transition">
                <?= $sortLabels[$selectedSort] ?? 'Custom Sort' ?>
                <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </a>
        <?php endif; ?>
        <a href="<?= APP_URL ?>/jobs" class="text-xs text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-semibold ml-1 transition">Clear all</a>
    </div>
    <?php endif; ?>

    <!-- Mobile: Filters button -->
    <button type="button" onclick="openJobFilterDrawer()"
            class="lg:hidden mb-5 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
        Filters
        <?php if ($activeFilterCount > 0): ?>
        <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold rounded-full bg-indigo-600 text-white"><?= $activeFilterCount ?></span>
        <?php endif; ?>
    </button>

    <!-- ── Two-column layout ─────────────────────────── -->
    <div class="flex gap-8 items-start">

        <!-- ════ LEFT SIDEBAR: Filters (desktop only) ════ -->
        <aside class="hidden lg:block w-64 flex-shrink-0 sticky top-[80px] self-start max-h-[calc(100vh-80px)] overflow-y-auto" style="-ms-overflow-style:none;scrollbar-width:none">
            <form action="<?= APP_URL ?>/jobs" method="GET" id="job-filter-form" class="pb-8">

                <!-- Categories -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden mb-5">
                    <div class="h-1.5 w-full bg-indigo-500"></div>
                    <div class="p-5">
                        <h3 class="text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-4">Category</h3>
                        <input type="hidden" name="category" id="jb-category-input" value="<?= e($selectedCat) ?>">
                        <div class="flex flex-wrap gap-2" id="jb-category-pills">
                            <?php foreach ($categories as $cat):
                                $catClasses = get_category_classes($cat);
                                $isActive = ($selectedCat === $cat);
                            ?>
                            <button type="button"
                                    onclick="selectJobCategory('<?= $cat ?>')"
                                    data-cat="<?= $cat ?>"
                                            class="jb-cat-pill px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 border
                                           <?= $isActive
                                               ? $catClasses['badge'] . ' border-current shadow-sm ring-1 ring-current/20'
                                               : 'bg-gray-50 dark:bg-transparent text-gray-600 dark:text-gray-400 border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800' ?>">
                                <?= $cat ?>
                            </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>


                <!-- Sort -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-5 mb-5">
                    <h3 class="text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-4">Sort By</h3>
                    <div class="space-y-2">
                        <?php
                        $sortOptions = ['' => 'Newest First', 'oldest' => 'Oldest First'];
                        foreach ($sortOptions as $val => $label):
                            $isActive = ($selectedSort === $val);
                        ?>
                        <label class="flex items-center gap-3 px-3 py-2 rounded-lg cursor-pointer transition-colors
                                      <?= $isActive ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400' : 'hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300' ?>">
                            <input type="radio" name="sort" value="<?= $val ?>"
                                   <?= $isActive ? 'checked' : '' ?>
                                   onchange="document.getElementById('job-filter-form').submit()"
                                   class="w-3.5 h-3.5 text-indigo-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            <span class="text-sm font-medium"><?= $label ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Clear all -->
                <?php if ($activeFilterCount > 0): ?>
                <a href="<?= APP_URL ?>/jobs"
                   class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-xl text-sm font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-900/50 hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors">
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    Clear All Filters
                </a>
                <?php endif; ?>

            </form>
        </aside>

        <!-- ════ MAIN CONTENT: Cards ════ -->
        <div class="flex-1 min-w-0">

            <!-- Top Filters (Search & Location) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 relative z-30">
                <!-- Search box -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="q" id="job-q" form="job-filter-form"
                           value="<?= htmlspecialchars($searchQuery) ?>"
                           placeholder="Search jobs by title or description..."
                           class="w-full pl-9 pr-8 py-3 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-800 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <button type="button" id="clear-job-search-btn" onclick="clearJobSearch()" class="absolute inset-y-0 right-0 pr-3 items-center text-gray-300 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 transition <?= !empty($searchQuery) ? 'flex' : 'hidden' ?>">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    </button>
                </div>

                <!-- Location (searchable) -->
                <div class="relative" id="wilaya-wrapper">
                    <input type="hidden" name="wilaya" id="wilaya-input" form="job-filter-form" value="<?= e($selectedWilaya) ?>">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-emerald-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                    </div>
                    <input type="text" id="wilaya-search" autocomplete="off"
                           placeholder="<?= !empty($selectedWilaya) ? e(preg_replace('/^\d{2}\s-\s/', '', $selectedWilaya)) : 'Search locations...' ?>"
                           class="w-full pl-9 pr-8 py-3 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-800 shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors
                                  <?= !empty($selectedWilaya) ? 'font-semibold text-emerald-700 dark:text-emerald-400' : '' ?>">
                    <?php if (!empty($selectedWilaya)): ?>
                    <button type="button" onclick="clearWilaya()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-400 transition">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    <?php endif; ?>
                    <div id="wilaya-dropdown" class="hidden absolute left-0 right-0 top-full mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-xl dark:shadow-gray-900/60 max-h-64 overflow-y-auto z-50"></div>
                </div>
            </div>

            <!-- Result count -->
            <?php if (!empty($jobs)): ?>
            <div class="flex items-center justify-between mb-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Showing <span class="font-bold text-gray-800 dark:text-gray-200 text-base"><?= $totalJobs ?></span>
                    open job<?= $totalJobs !== 1 ? 's' : '' ?>
                    <?php if ($activeFilterCount > 0): ?>matching your filters<?php endif; ?>
                </p>
            </div>

            <!-- Job Grid: 3 columns -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
                <?php foreach ($jobs as $job):
                    $catStyles = get_category_classes($job['service_category'] ?? 'General Handyman');
                    $timeAgo   = job_time_ago($job['created_at']);
                ?>
                <div onclick="window.location='<?= APP_URL ?>/jobs/<?= $job['id'] ?>'"
                   class="group flex flex-col bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-500/50 hover:-translate-y-0.5 transition-all duration-200 overflow-hidden cursor-pointer">

                    <?php
                        $cardImages = [];
                        if (!empty($job['images'])) {
                            $decoded = is_string($job['images']) ? json_decode($job['images'], true) : $job['images'];
                            if (is_array($decoded)) $cardImages = $decoded;
                        }
                    ?>
                    <?php if (!empty($cardImages)): ?>
                    <div class="relative h-40 overflow-hidden">
                        <img src="<?= APP_URL . '/' . htmlspecialchars($cardImages[0]) ?>" alt="<?= htmlspecialchars($job['title']) ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <?php if (count($cardImages) > 1): ?>
                        <span class="absolute bottom-2 right-2 bg-black/60 text-white text-xs font-bold px-2 py-0.5 rounded-lg backdrop-blur-sm">
                            <svg class="inline w-3 h-3 mr-0.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <?= count($cardImages) ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <!-- Aesthetic Placeholder for Jobs without Photos -->
                    <div class="relative h-40 overflow-hidden bg-gradient-to-br from-indigo-50 dark:from-indigo-900/20 to-purple-50 dark:to-purple-900/20 flex items-center justify-center">
                        <svg class="h-10 w-10 text-indigo-200/60 dark:text-indigo-500/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 22V12h6v10" />
                        </svg>
                    </div>
                    <?php endif; ?>

                    <div class="p-6 flex flex-col gap-3 flex-1">

                        <!-- Title + message icon -->
                        <div class="flex items-start justify-between gap-2">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-gray-100 leading-snug group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2 flex-1">
                                <?= htmlspecialchars($job['title']) ?>
                            </h2>
                            <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'craftsman'): ?>
                            <a href="<?= APP_URL ?>/messages/<?= htmlspecialchars($job['poster_username'] ?? '') ?>"
                               onclick="event.stopPropagation()"
                               title="Message homeowner"
                               class="flex-shrink-0 p-1.5 rounded-lg text-gray-400 dark:text-gray-500 hover:text-indigo-500 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-gray-700/50 transition-all duration-150">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 12.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </a>
                            <?php endif; ?>
                        </div>

                        <!-- Category badge + poster name -->
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold <?= $catStyles['badge'] ?>">
                                <?= htmlspecialchars($job['service_category']) ?>
                            </span>
                            <span class="flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500 truncate">
                                <svg class="h-3 w-3 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                <?= htmlspecialchars($job['first_name'] . ' ' . $job['last_name']) ?>
                            </span>
                        </div>

                        <!-- Description -->
                        <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed">
                            <?= htmlspecialchars($job['description']) ?>
                        </p>

                        <!-- Footer: location, time, budget -->
                        <div class="border-t border-gray-100 dark:border-gray-700/50 pt-3 mt-auto flex items-center justify-between gap-2">
                            <div class="flex items-center gap-3 text-xs text-gray-400 dark:text-gray-500 min-w-0">
                                <span class="flex items-center gap-1 truncate">
                                    <svg class="h-3.5 w-3.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                    <?= htmlspecialchars(preg_replace('/^\d{2}\s-\s/', '', $job['address'])) ?>
                                </span>
                                <span class="flex items-center gap-1 flex-shrink-0">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                    <?= $timeAgo ?>
                                </span>
                            </div>

                            <?php if (!empty($job['budget_range'])): ?>
                            <span class="flex items-center gap-1 text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-2 py-1 rounded-lg flex-shrink-0">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                </svg>
                                <?= htmlspecialchars($job['budget_range']) ?> DZD
                            </span>
                            <?php else: ?>
                            <span class="text-xs text-gray-300 dark:text-gray-600 flex-shrink-0 italic">No budget</span>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="flex items-center justify-between bg-white dark:bg-gray-800 px-4 py-3 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex flex-1 justify-between sm:hidden">
                    <?php if ($page > 1): ?>
                    <a href="<?= build_job_url($page - 1, $filters) ?>" class="relative inline-flex items-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Previous</a>
                    <?php endif; ?>
                    <?php if ($page < $totalPages): ?>
                    <a href="<?= build_job_url($page + 1, $filters) ?>" class="relative ml-3 inline-flex items-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Next</a>
                    <?php endif; ?>
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Showing <span class="font-semibold text-gray-700 dark:text-gray-300"><?= (($page - 1) * 12) + 1 ?></span>–<span class="font-semibold text-gray-700 dark:text-gray-300"><?= min($page * 12, $totalJobs) ?></span> of <span class="font-semibold text-gray-700 dark:text-gray-300"><?= number_format($totalJobs) ?></span> jobs
                    </p>
                    <nav class="isolate inline-flex -space-x-px rounded-lg shadow-sm">
                        <?php if ($page > 1): ?>
                        <a href="<?= build_job_url($page - 1, $filters) ?>" class="relative inline-flex items-center rounded-l-lg px-2 py-2 text-gray-400 dark:text-gray-500 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd"/></svg>
                        </a>
                        <?php endif; ?>
                        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <a href="<?= build_job_url($i, $filters) ?>"
                           class="relative inline-flex items-center px-4 py-2 text-sm font-semibold <?= $i === $page ? 'z-10 bg-indigo-600 text-white' : 'text-gray-900 dark:text-gray-300 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700/50' ?>">
                            <?= $i ?>
                        </a>
                        <?php endfor; ?>
                        <?php if ($page < $totalPages): ?>
                        <a href="<?= build_job_url($page + 1, $filters) ?>" class="relative inline-flex items-center rounded-r-lg px-2 py-2 text-gray-400 dark:text-gray-500 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/></svg>
                        </a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
            <?php endif; ?>

            <?php else: ?>
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 text-center py-20 px-8">
                <div class="mx-auto h-16 w-16 bg-gray-50 dark:bg-gray-700/50 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-gray-300 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">No jobs found</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Try adjusting your filters or search keywords.</p>
                <?php if ($activeFilterCount > 0 || !empty($searchQuery)): ?>
                <a href="<?= APP_URL ?>/jobs"
                   class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150 shadow-sm">
                    Clear all filters
                </a>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'homeowner'): ?>
                <div class="mt-4">
                    <a href="<?= APP_URL ?>/jobs/create"
                       class="inline-flex items-center px-5 py-2.5 border border-transparent shadow-sm text-sm font-semibold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                        Post a Job
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </div>
    </div>

</div>
</div>

<!-- ═══════ Mobile Filter Drawer ═══════ -->
<div id="jb-filter-overlay" class="fixed inset-0 bg-gray-900/50 z-40 hidden lg:hidden" onclick="closeJobFilterDrawer()"></div>
<div id="jb-filter-drawer" class="fixed inset-x-0 bottom-0 z-50 bg-white dark:bg-gray-800 rounded-t-2xl shadow-2xl dark:shadow-gray-900/60 transform translate-y-full transition-transform duration-300 ease-out lg:hidden" style="max-height:85vh">
    <div class="flex items-center justify-center pt-3 pb-1">
        <div class="w-10 h-1 rounded-full bg-gray-300 dark:bg-gray-600"></div>
    </div>
    <div class="px-5 pb-2 flex items-center justify-between border-b border-gray-100 dark:border-gray-700">
        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Filters</h3>
        <?php if ($activeFilterCount > 0): ?>
        <a href="<?= APP_URL ?>/jobs" class="text-xs font-semibold text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">Clear all</a>
        <?php endif; ?>
    </div>

    <form action="<?= APP_URL ?>/jobs" method="GET" id="jb-mobile-filter-form" class="overflow-y-auto" style="max-height:calc(85vh - 130px)">
        <?php if (!empty($searchQuery)): ?><input type="hidden" name="q" value="<?= htmlspecialchars($searchQuery) ?>"><?php endif; ?>
        <div class="p-5 space-y-6">
            <!-- Search -->
            <div>
                <label class="text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-2 block">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="q" id="mob-job-q" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search by title or description..."
                           class="w-full pl-9 pr-8 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl text-sm bg-gray-50/50 dark:bg-gray-700 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <button type="button" id="mob-clear-job-search-btn" onclick="clearMobJobSearch()" class="absolute inset-y-0 right-0 pr-3 items-center text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition <?= !empty($searchQuery) ? 'flex' : 'hidden' ?>">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
            </div>

            <!-- Category -->
            <div>
                <label class="text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-3 block">Category</label>
                <input type="hidden" name="category" id="mob-category-input" value="<?= e($selectedCat) ?>">
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($categories as $cat):
                        $catClasses = get_category_classes($cat);
                        $isActive = ($selectedCat === $cat);
                    ?>
                    <button type="button"
                            onclick="mobSelectJobCategory('<?= $cat ?>')"
                            data-cat="<?= $cat ?>"
                            class="mob-cat-pill px-3 py-1.5 rounded-lg text-xs font-semibold transition-all border
                                   <?= $isActive ? $catClasses['badge'] . ' border-current' : 'bg-gray-50 dark:bg-transparent text-gray-600 dark:text-gray-400 border-gray-200 dark:border-gray-700' ?>">
                        <?= $cat ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Location -->
            <div>
                <label class="text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-2 block">Location</label>
                <select name="wilaya" class="w-full border border-gray-200 dark:border-gray-600 rounded-xl text-sm py-2.5 px-3 bg-gray-50/50 dark:bg-gray-700 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-600 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    <option value="">All Regions</option>
                    <?php foreach ($wilayas as $w): ?>
                    <option value="<?= e($w) ?>" <?= $selectedWilaya === $w ? 'selected' : '' ?>><?= e(preg_replace('/^\d{2}\s-\s/', '', $w)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Sort -->
            <div>
                <label class="text-xs font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider mb-2 block">Sort By</label>
                <select name="sort" class="w-full border border-gray-200 dark:border-gray-600 rounded-xl text-sm py-2.5 px-3 bg-gray-50/50 dark:bg-gray-700 dark:text-gray-100 focus:bg-white dark:focus:bg-gray-600 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="" <?= empty($selectedSort) ? 'selected' : '' ?>>Newest First</option>
                    <option value="oldest" <?= $selectedSort === 'oldest' ? 'selected' : '' ?>>Oldest First</option>
                </select>
            </div>
        </div>
    </form>

    <!-- Sticky bottom buttons -->
    <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 flex gap-3">
        <a href="<?= APP_URL ?>/jobs" class="flex-1 text-center py-2.5 px-4 rounded-xl text-sm font-semibold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">Clear All</a>
        <button type="button" onclick="document.getElementById('jb-mobile-filter-form').submit()"
                class="flex-1 py-2.5 px-4 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-sm">
            Apply Filters
        </button>
    </div>
</div>

<script>
/* ── Category pill selection ── */
function selectJobCategory(cat) {
    var input = document.getElementById('jb-category-input');
    input.value = (input.value === cat) ? '' : cat;
    document.getElementById('job-filter-form').submit();
}

/* ── Mobile Category pill selection ── */
function mobSelectJobCategory(cat) {
    var input = document.getElementById('mob-category-input');
    var pills = document.querySelectorAll('.mob-cat-pill');
    if (input.value === cat) {
        input.value = '';
        pills.forEach(function(p) { p.className = 'mob-cat-pill px-3 py-1.5 rounded-lg text-xs font-semibold transition-all border bg-gray-50 dark:bg-transparent text-gray-600 dark:text-gray-400 border-gray-200 dark:border-gray-700'; });
    } else {
        input.value = cat;
        pills.forEach(function(p) {
            if (p.getAttribute('data-cat') === cat) {
                p.className = 'mob-cat-pill px-3 py-1.5 rounded-lg text-xs font-semibold transition-all border border-current bg-indigo-50 text-indigo-700';
            } else {
                p.className = 'mob-cat-pill px-3 py-1.5 rounded-lg text-xs font-semibold transition-all border bg-gray-50 dark:bg-transparent text-gray-600 dark:text-gray-400 border-gray-200 dark:border-gray-700';
            }
        });
    }
}

/* ── Wilaya searchable dropdown (Desktop) ── */
var allWilayas = <?= json_encode($wilayas) ?>;
var wilayaSearch = document.getElementById('wilaya-search');
var wilayaDropdown = document.getElementById('wilaya-dropdown');
var wilayaInput = document.getElementById('wilaya-input');

if (wilayaSearch) {
    wilayaSearch.addEventListener('focus', function() { filterWilayas(this.value); });
    wilayaSearch.addEventListener('click', function() { filterWilayas(this.value); });
    wilayaSearch.addEventListener('input', function() { filterWilayas(this.value); });
    document.addEventListener('mousedown', function(e) {
        var wrapper = document.getElementById('wilaya-wrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            wilayaDropdown.classList.add('hidden');
        }
    });
}

function filterWilayas(query) {
    var q = query.toLowerCase().trim();
    var html = '';
    var count = 0;
    
    html += '<button type="button" onmousedown="selectWilaya(\'\')" class="w-full text-left px-4 py-2.5 text-sm hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-colors ' + (!wilayaInput.value ? 'font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'text-gray-600 dark:text-gray-300') + '">All Regions</button>';

    allWilayas.forEach(function(w) {
        var name = w.replace(/^\d{2}\s-\s/, '');
        if (!q || w.toLowerCase().indexOf(q) > -1 || name.toLowerCase().indexOf(q) > -1) {
            var isSelected = (wilayaInput.value === w);
            html += '<button type="button" onmousedown="selectWilaya(\'' + w.replace(/'/g, "\\'") + '\')" class="w-full text-left px-4 py-2.5 text-sm hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:text-emerald-700 dark:hover:text-emerald-400 transition-colors ' + (isSelected ? 'font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'text-gray-600 dark:text-gray-300') + '">' + w + '</button>';
            count++;
        }
    });

    if (count === 0 && q) {
        html = '<div class="px-4 py-3 text-sm text-gray-400 dark:text-gray-500">No wilayas found</div>';
    }

    wilayaDropdown.innerHTML = html;
    wilayaDropdown.classList.remove('hidden');
}

function selectWilaya(w) {
    wilayaInput.value = w;
    wilayaDropdown.classList.add('hidden');
    document.getElementById('job-filter-form').submit();
}

function clearWilaya() {
    wilayaInput.value = '';
    document.getElementById('job-filter-form').submit();
}

/* ── Clear search (Desktop) ── */
var searchInput = document.getElementById('job-q');
var clearBtn = document.getElementById('clear-job-search-btn');

function clearJobSearch() {
    if (searchInput) { searchInput.value = ''; }
    if (clearBtn) { clearBtn.classList.add('hidden'); clearBtn.classList.remove('flex'); }
    document.getElementById('job-filter-form').submit();
}

if (searchInput) {
    searchInput.addEventListener('input', function() {
        if (this.value.length > 0) {
            clearBtn.classList.remove('hidden'); clearBtn.classList.add('flex');
        } else {
            clearBtn.classList.add('hidden'); clearBtn.classList.remove('flex');
        }
    });
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('job-filter-form').submit();
        }
    });
}

/* ── Clear search (Mobile) ── */
var mobSearchInput = document.getElementById('mob-job-q');
var mobClearBtn = document.getElementById('mob-clear-job-search-btn');

function clearMobJobSearch() {
    if (mobSearchInput) { mobSearchInput.value = ''; }
    if (mobClearBtn) { mobClearBtn.classList.add('hidden'); mobClearBtn.classList.remove('flex'); }
}

if (mobSearchInput) {
    mobSearchInput.addEventListener('input', function() {
        if (this.value.length > 0) {
            mobClearBtn.classList.remove('hidden'); mobClearBtn.classList.add('flex');
        } else {
            mobClearBtn.classList.add('hidden'); mobClearBtn.classList.remove('flex');
        }
    });
    mobSearchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('jb-mobile-filter-form').submit();
        }
    });
}

/* ── Mobile filter drawer ── */
function openJobFilterDrawer() {
    document.getElementById('jb-filter-overlay').classList.remove('hidden');
    document.getElementById('jb-filter-drawer').style.transform = 'translateY(0)';
    document.body.style.overflow = 'hidden';
}
function closeJobFilterDrawer() {
    document.getElementById('jb-filter-overlay').classList.add('hidden');
    document.getElementById('jb-filter-drawer').style.transform = 'translateY(100%)';
    document.body.style.overflow = '';
}
// Swipe down to close
(function() {
    var drawer = document.getElementById('jb-filter-drawer');
    if (!drawer) return;
    var startY = 0;
    drawer.addEventListener('touchstart', function(e) { startY = e.touches[0].clientY; }, {passive:true});
    drawer.addEventListener('touchend', function(e) { if (e.changedTouches[0].clientY - startY > 60) closeJobFilterDrawer(); }, {passive:true});
})();
</script>