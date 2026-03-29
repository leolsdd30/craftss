<!-- Find Craftsmen Page -->
<?php
$activeFilterCount = (int)!empty($filters['category'])
                   + (int)!empty($filters['wilaya'])
                   + (int)!empty($filters['sort']);

$categories = ["Plumbing","Electrical","Carpentry","Painting","Roofing","HVAC","Landscaping","Tiling","General Handyman"];
$wilayas = [
    "01 - Adrar","02 - Chlef","03 - Laghouat","04 - Oum El Bouaghi","05 - Batna",
    "06 - Béjaïa","07 - Biskra","08 - Béchar","09 - Blida","10 - Bouira",
    "11 - Tamanrasset","12 - Tébessa","13 - Tlemcen","14 - Tiaret","15 - Tizi Ouzou",
    "16 - Alger","17 - Djelfa","18 - Jijel","19 - Sétif","20 - Saïda",
    "21 - Skikda","22 - Sidi Bel Abbès","23 - Annaba","24 - Guelma","25 - Constantine",
    "26 - Médéa","27 - Mostaganem","28 - M'Sila","29 - Mascara","30 - Ouargla",
    "31 - Oran","32 - El Bayadh","33 - Illizi","34 - Bordj Bou Arréridj",
    "35 - Boumerdès","36 - El Tarf","37 - Tindouf","38 - Tissemsilt",
    "39 - El Oued","40 - Khenchela","41 - Souk Ahras","42 - Tipaza","43 - Mila",
    "44 - Aïn Defla","45 - Naâma","46 - Aïn Témouchent","47 - Ghardaïa","48 - Relizane",
    "49 - Timimoun","50 - Bordj Badji Mokhtar","51 - Ouled Djellal","52 - Béni Abbès",
    "53 - In Salah","54 - In Guezzam","55 - Touggourt","56 - Djanet",
    "57 - El M'Ghair","58 - El Meniaa",
];
$selectedCat    = $filters['category'] ?? '';
$selectedWilaya = $filters['wilaya']   ?? '';
$selectedSort   = $filters['sort']     ?? '';
$searchQuery    = $filters['search']   ?? '';
?>

<div class="bg-gray-50 min-h-screen">
<div class="max-w-screen-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Find Skilled Professionals</h1>
        <p class="mt-1 text-sm text-gray-500">Browse verified craftsmen with experience in your specific home project needs.</p>
    </div>

    <!-- Active filter tags (quick remove) -->
    <?php if ($activeFilterCount > 0): ?>
    <div class="flex flex-wrap items-center gap-2 mb-5" id="active-tags">
        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider mr-1">Active:</span>
        <?php if (!empty($selectedCat)): ?>
            <?php $tagCat = get_category_classes($selectedCat); ?>
            <a href="<?= APP_URL ?>/search?<?= http_build_query(array_diff_key($_GET, ['category'=>''])) ?>"
               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold <?= $tagCat['badge'] ?> hover:opacity-80 transition">
                <?= e($selectedCat) ?>
                <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </a>
        <?php endif; ?>
        <?php if (!empty($selectedWilaya)): ?>
            <a href="<?= APP_URL ?>/search?<?= http_build_query(array_diff_key($_GET, ['wilaya'=>''])) ?>"
               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 hover:opacity-80 transition">
                <svg class="w-3 h-3 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                <?= e(preg_replace('/^\d{2}\s-\s/', '', $selectedWilaya)) ?>
                <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </a>
        <?php endif; ?>
        <?php if (!empty($selectedSort)): ?>
            <?php $sortLabels = ['top_rated'=>'Top Rated','rate_low'=>'Rate: Low→High','rate_high'=>'Rate: High→Low']; ?>
            <a href="<?= APP_URL ?>/search?<?= http_build_query(array_diff_key($_GET, ['sort'=>''])) ?>"
               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 hover:opacity-80 transition">
                <?= $sortLabels[$selectedSort] ?? 'Custom Sort' ?>
                <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </a>
        <?php endif; ?>
        <a href="<?= APP_URL ?>/search" class="text-xs text-red-500 hover:text-red-700 font-semibold ml-1 transition">Clear all</a>
    </div>
    <?php endif; ?>

    <!-- Mobile: Filters button -->
    <button type="button" onclick="openFilterDrawer()"
            class="lg:hidden mb-5 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition">
        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
        Filters
        <?php if ($activeFilterCount > 0): ?>
        <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold rounded-full bg-indigo-600 text-white"><?= $activeFilterCount ?></span>
        <?php endif; ?>
    </button>

    <!-- ── Two-column layout ─────────────────────────── -->
    <div class="flex gap-8 items-start">

        <!-- ════ LEFT SIDEBAR: Filters (desktop only) ════ -->
        <aside class="hidden lg:block w-72 flex-shrink-0 sticky top-[80px] self-start max-h-[calc(100vh-80px)] overflow-y-auto" style="-ms-overflow-style:none;scrollbar-width:none">
            <form action="<?= APP_URL ?>/search" method="GET" id="search-form" class="pb-8">

                <!-- Categories -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-5">
                    <div class="h-1.5 w-full bg-indigo-500"></div>
                    <div class="p-5">
                        <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-4">Category</h3>
                        <input type="hidden" name="category" id="category-input" value="<?= e($selectedCat) ?>">
                        <div class="flex flex-wrap gap-2" id="category-pills">
                            <?php foreach ($categories as $cat):
                                $catClasses = get_category_classes($cat);
                                $isActive = ($selectedCat === $cat);
                            ?>
                            <button type="button"
                                    onclick="selectCategory('<?= $cat ?>')"
                                    data-cat="<?= $cat ?>"
                                    class="cat-pill px-3 py-1.5 rounded-lg text-xs font-semibold transition-all duration-150 border
                                           <?= $isActive
                                               ? $catClasses['badge'] . ' border-current shadow-sm ring-1 ring-current/20'
                                               : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' ?>">
                                <?= $cat ?>
                            </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Sort -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 mb-5">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-4">Sort By</h3>
                    <div class="space-y-2">
                        <?php
                        $sortOptions = ['' => 'Newest First', 'top_rated' => 'Top Rated', 'rate_low' => 'Rate: Low to High', 'rate_high' => 'Rate: High to Low'];
                        foreach ($sortOptions as $val => $label):
                            $isActive = ($selectedSort === $val);
                        ?>
                        <label class="flex items-center gap-3 px-3 py-2 rounded-lg cursor-pointer transition-colors
                                      <?= $isActive ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-50 text-gray-600' ?>">
                            <input type="radio" name="sort" value="<?= $val ?>"
                                   <?= $isActive ? 'checked' : '' ?>
                                   onchange="document.getElementById('search-form').submit()"
                                   class="w-3.5 h-3.5 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="text-sm font-medium"><?= $label ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Clear all -->
                <?php if ($activeFilterCount > 0): ?>
                <a href="<?= APP_URL ?>/search"
                   class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-xl text-sm font-semibold text-red-600 bg-red-50 border border-red-100 hover:bg-red-100 transition-colors">
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
                    <input type="text" name="q" id="q" form="search-form"
                           value="<?= htmlspecialchars($searchQuery) ?>"
                           placeholder="Search by name, skill..."
                           class="w-full pl-9 pr-8 py-3 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 bg-white shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    <button type="button" id="clear-search-btn" onclick="clearSearch()" class="absolute inset-y-0 right-0 pr-3 items-center text-gray-300 hover:text-red-500 transition <?= !empty($searchQuery) ? 'flex' : 'hidden' ?>">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    </button>
                </div>

                <!-- Wilaya (searchable) -->
                <div class="relative" id="wilaya-wrapper">
                    <input type="hidden" name="wilaya" id="wilaya-input" form="search-form" value="<?= e($selectedWilaya) ?>">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-emerald-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                    </div>
                    <input type="text" id="wilaya-search" autocomplete="off"
                           placeholder="<?= !empty($selectedWilaya) ? e(preg_replace('/^\d{2}\s-\s/', '', $selectedWilaya)) : 'Search locations...' ?>"
                           class="w-full pl-9 pr-8 py-3 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 bg-white shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors
                                  <?= !empty($selectedWilaya) ? 'font-semibold text-emerald-700' : '' ?>">
                    <?php if (!empty($selectedWilaya)): ?>
                    <button type="button" onclick="clearWilaya()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    <?php endif; ?>
                    <div id="wilaya-dropdown" class="hidden absolute left-0 right-0 top-full mt-2 bg-white border border-gray-200 rounded-2xl shadow-xl max-h-64 overflow-y-auto z-50"></div>
                </div>
            </div>

            <?php if (!empty($craftsmen)): ?>

            <!-- Result text -->
            <div class="flex items-center justify-between mb-5">
                <p class="text-sm text-gray-500">
                    Showing <span class="font-bold text-gray-800 text-base"><?= $totalResults ?></span>
                    <?= $totalResults === 1 ? 'craftsman' : 'craftsmen' ?>
                    <?php if ($activeFilterCount > 0): ?>matching your filters<?php endif; ?>
                </p>
                <?php if ($activeFilterCount > 0): ?>
                <a href="<?= APP_URL ?>/search" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium lg:hidden">Clear limits</a>
                <?php endif; ?>
            </div>

            <!-- Craftsman Grid: 3 columns -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php foreach ($craftsmen as $craft): ?>
                <?php $catStyles = get_category_classes($craft['service_category']); ?>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col overflow-hidden relative group">

                    <!-- Favorite heart -->
                    <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') !== 'admin'): ?>
                    <button type="button"
                            onclick="toggleFavorite(<?= $craft['user_id'] ?>, this)"
                            class="absolute top-4 right-4 p-1.5 rounded-full z-20 bg-white/90 backdrop-blur-sm shadow-sm border
                                   <?= $craft['is_favorite'] ? 'border-pink-200 text-pink-500' : 'border-gray-200 text-gray-300 hover:text-pink-400 hover:border-pink-200' ?>
                                   transition-colors duration-200 focus:outline-none"
                            title="<?= $craft['is_favorite'] ? 'Remove from favorites' : 'Save to favorites' ?>">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                             <?= $craft['is_favorite'] ? 'viewBox="0 0 20 20" fill="currentColor"' : 'fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"' ?>>
                            <path <?= $craft['is_favorite']
                                ? 'fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"'
                                : 'stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"'
                            ?> />
                        </svg>
                    </button>
                    <?php endif; ?>

                    <!-- Card body -->
                    <div class="p-6 flex-grow flex flex-col">

                        <!-- Avatar + Name row -->
                        <div class="flex items-start gap-3.5 mb-4">
                            <div class="relative flex-shrink-0">
                                <img class="h-14 w-14 rounded-full object-cover ring-2 shadow-sm <?= $catStyles['ring'] ?? 'ring-gray-100' ?>"
                                     src="<?= get_profile_picture_url($craft['profile_picture'] ?? 'default.png', $craft['first_name'], $craft['last_name']) ?>"
                                     alt="<?= htmlspecialchars($craft['first_name']) ?>">
                            </div>

                            <div class="min-w-0 flex-1 pt-0.5">
                                <h2 class="text-sm font-bold text-gray-900 leading-tight truncate flex items-center gap-1">
                                    <?= htmlspecialchars($craft['first_name'] . ' ' . $craft['last_name']) ?>
                                    <?php if ($craft['is_verified']): ?>
                                    <svg class="h-4 w-4 text-blue-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" title="Verified">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <?php endif; ?>
                                </h2>
                                <span class="inline-flex items-center mt-1.5 px-2 py-0.5 rounded-md text-xs font-semibold <?= $catStyles['badge'] ?? 'bg-indigo-50 text-indigo-700' ?>">
                                    <?= htmlspecialchars($craft['service_category']) ?>
                                </span>
                                <?php if (!empty($craft['wilaya'])): ?>
                                <p class="text-xs text-gray-400 mt-1 flex items-center">
                                    <svg class="h-3 w-3 mr-1 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                    <?= htmlspecialchars(preg_replace('/^\d{2}\s-\s/', '', $craft['wilaya'])) ?>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Star rating -->
                        <div class="flex items-center gap-1.5 mb-3">
                            <?php $avg = (float)($craft['avg_rating'] ?? 0); $total = (int)($craft['total_reviews'] ?? 0); ?>
                            <?php if ($total > 0): ?>
                                <div class="flex items-center">
                                    <?php for ($s = 1; $s <= 5; $s++): ?>
                                    <svg class="h-3.5 w-3.5 <?= $s <= round($avg) ? 'text-amber-400' : 'text-gray-200' ?>" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <?php endfor; ?>
                                </div>
                                <span class="text-xs font-bold text-gray-700"><?= $avg ?></span>
                                <span class="text-xs text-gray-400">(<?= $total ?>)</span>
                            <?php else: ?>
                                <div class="flex items-center">
                                    <?php for ($s = 1; $s <= 5; $s++): ?>
                                    <svg class="h-3.5 w-3.5 text-gray-200" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <?php endfor; ?>
                                </div>
                                <span class="text-xs text-gray-400">No reviews</span>
                            <?php endif; ?>
                        </div>

                        <!-- Bio snippet -->
                        <?php if (!empty($craft['bio'])): ?>
                        <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed mb-4 flex-grow break-words"><?= htmlspecialchars($craft['bio']) ?></p>
                        <?php else: ?>
                        <div class="flex-grow"></div>
                        <?php endif; ?>

                        <!-- Rate + Verified footer -->
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100 mt-auto">
                            <div>
                                <span class="text-xs text-gray-400 uppercase tracking-wider">Hourly</span>
                                <p class="font-bold text-gray-900 text-base leading-tight"><?= number_format($craft['hourly_rate'], 2) ?> DZD</p>
                            </div>
                            <?php if ($craft['is_verified']): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                <svg class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Verified
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Card footer: actions -->
                    <div class="px-6 py-3.5 bg-gray-50/50 border-t border-gray-100 flex gap-2">
                        <a href="<?= APP_URL ?>/profile/<?= htmlspecialchars($craft['username']) ?>"
                           class="flex-1 text-center py-2 px-3 rounded-xl text-sm font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 transition duration-150">
                            View Profile
                        </a>
                        <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') !== 'admin'): ?>
                        <a href="<?= APP_URL ?>/bookings/create/<?= htmlspecialchars($craft['username']) ?>"
                           class="flex-1 text-center py-2 px-3 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                            Book Now
                        </a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="mt-10 flex justify-center">
                <nav class="relative z-0 inline-flex rounded-xl shadow-sm -space-x-px" aria-label="Pagination">
                    <?php
                        $buildUrl = function($pageNum) use ($filters) {
                            $params = $_GET;
                            $params['page'] = $pageNum;
                            return APP_URL . '/search?' . http_build_query($params);
                        };
                    ?>
                    <?php if ($page > 1): ?>
                    <a href="<?= $buildUrl($page - 1) ?>" class="relative inline-flex items-center px-3 py-2 rounded-l-xl border border-gray-200 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </a>
                    <?php else: ?>
                    <span class="relative inline-flex items-center px-3 py-2 rounded-l-xl border border-gray-200 bg-gray-50 text-sm text-gray-300 cursor-not-allowed">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </span>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i == $page): ?>
                        <span class="z-10 bg-indigo-600 text-white relative inline-flex items-center px-4 py-2 border border-indigo-600 text-sm font-bold"><?= $i ?></span>
                        <?php else: ?>
                        <a href="<?= $buildUrl($i) ?>" class="bg-white border-gray-200 text-gray-600 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                    <a href="<?= $buildUrl($page + 1) ?>" class="relative inline-flex items-center px-3 py-2 rounded-r-xl border border-gray-200 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                    </a>
                    <?php else: ?>
                    <span class="relative inline-flex items-center px-3 py-2 rounded-r-xl border border-gray-200 bg-gray-50 text-sm text-gray-300 cursor-not-allowed">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                    </span>
                    <?php endif; ?>
                </nav>
            </div>
            <?php endif; ?>

            <?php else: ?>

            <!-- Empty state -->
            <div class="text-center py-24 bg-white rounded-2xl border border-gray-200 shadow-sm">
                <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">No craftsmen found</h3>
                <p class="text-sm text-gray-500 mb-6">Try adjusting your filters or search keywords.</p>
                <a href="<?= APP_URL ?>/search"
                   class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150 shadow-sm">
                    Clear all filters
                </a>
            </div>

            <?php endif; ?>

        </div><!-- /main content -->

    </div><!-- /two-column -->

</div>
</div>


<!-- ════ MOBILE FILTER DRAWER ════ -->
<div id="filter-overlay" class="fixed inset-0 bg-gray-900/50 z-40 hidden lg:hidden" onclick="closeFilterDrawer()"></div>
<div id="filter-drawer" class="fixed inset-x-0 bottom-0 z-50 bg-white rounded-t-2xl shadow-2xl transform translate-y-full transition-transform duration-300 ease-out lg:hidden" style="max-height:85vh">
    <div class="flex items-center justify-center pt-3 pb-1">
        <div class="w-10 h-1 rounded-full bg-gray-300"></div>
    </div>
    <div class="px-5 pb-2 flex items-center justify-between border-b border-gray-100">
        <h3 class="text-base font-bold text-gray-900">Filters</h3>
        <?php if ($activeFilterCount > 0): ?>
        <a href="<?= APP_URL ?>/search" class="text-xs font-semibold text-red-500 hover:text-red-700">Clear all</a>
        <?php endif; ?>
    </div>

    <form action="<?= APP_URL ?>/search" method="GET" id="mob-search-form" class="overflow-y-auto" style="max-height:calc(85vh - 130px)">
        <div class="p-5 space-y-6">

            <!-- Search -->
            <div>
                <label class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-2 block">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" name="q" id="mob-q" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search by name, skill..."
                           class="w-full pl-9 pr-8 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <button type="button" id="mob-clear-search-btn" onclick="clearMobSearch()" class="absolute inset-y-0 right-0 pr-3 items-center text-gray-400 hover:text-red-500 transition <?= !empty($searchQuery) ? 'flex' : 'hidden' ?>">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
            </div>

            <!-- Categories -->
            <div>
                <label class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3 block">Category</label>
                <input type="hidden" name="category" id="mob-category-input" value="<?= e($selectedCat) ?>">
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($categories as $cat):
                        $catClasses = get_category_classes($cat);
                        $isActive = ($selectedCat === $cat);
                    ?>
                    <button type="button"
                            onclick="mobSelectCategory('<?= $cat ?>')"
                            data-cat="<?= $cat ?>"
                            class="mob-cat-pill px-3 py-1.5 rounded-lg text-xs font-semibold transition-all border
                                   <?= $isActive ? $catClasses['badge'] . ' border-current' : 'bg-gray-50 text-gray-600 border-gray-200' ?>">
                        <?= $cat ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Wilaya -->
            <div>
                <label class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-2 block">Location</label>
                <select name="wilaya" class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-3 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="">All Regions</option>
                    <?php foreach ($wilayas as $w): ?>
                    <option value="<?= $w ?>" <?= ($selectedWilaya === $w) ? 'selected' : '' ?>><?= $w ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Sort -->
            <div>
                <label class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-2 block">Sort By</label>
                <select name="sort" class="w-full border border-gray-200 rounded-xl text-sm py-2.5 px-3 bg-gray-50/50 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    <option value="" <?= empty($selectedSort) ? 'selected' : '' ?>>Newest First</option>
                    <option value="top_rated" <?= ($selectedSort === 'top_rated') ? 'selected' : '' ?>>Top Rated</option>
                    <option value="rate_low" <?= ($selectedSort === 'rate_low') ? 'selected' : '' ?>>Rate: Low to High</option>
                    <option value="rate_high" <?= ($selectedSort === 'rate_high') ? 'selected' : '' ?>>Rate: High to Low</option>
                </select>
            </div>

        </div>
    </form>

    <!-- Sticky bottom buttons -->
    <div class="px-5 py-4 border-t border-gray-100 bg-white flex gap-3">
        <a href="<?= APP_URL ?>/search" class="flex-1 text-center py-2.5 px-4 rounded-xl text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Clear All</a>
        <button type="button" onclick="document.getElementById('mob-search-form').submit()"
                class="flex-1 py-2.5 px-4 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-sm">
            Apply Filters
        </button>
    </div>
</div>


<script>
/* ── Category selection (Desktop) ── */
function selectCategory(cat) {
    var input = document.getElementById('category-input');
    input.value = (input.value === cat) ? '' : cat;
    document.getElementById('search-form').submit();
}

/* ── Category selection (Mobile) ── */
function mobSelectCategory(cat) {
    var input = document.getElementById('mob-category-input');
    var pills = document.querySelectorAll('.mob-cat-pill');
    if (input.value === cat) {
        input.value = '';
        pills.forEach(function(p) { p.className = 'mob-cat-pill px-3 py-1.5 rounded-lg text-xs font-semibold transition-all border bg-gray-50 text-gray-600 border-gray-200'; });
    } else {
        input.value = cat;
        pills.forEach(function(p) {
            if (p.getAttribute('data-cat') === cat) {
                p.className = 'mob-cat-pill px-3 py-1.5 rounded-lg text-xs font-semibold transition-all border border-current bg-indigo-50 text-indigo-700';
            } else {
                p.className = 'mob-cat-pill px-3 py-1.5 rounded-lg text-xs font-semibold transition-all border bg-gray-50 text-gray-600 border-gray-200';
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
    // "All Regions" option
    html += '<button type="button" onmousedown="selectWilaya(\'\')" class="w-full text-left px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition-colors ' + (!wilayaInput.value ? 'font-bold text-indigo-700 bg-indigo-50' : 'text-gray-600') + '">All Regions</button>';

    allWilayas.forEach(function(w) {
        var name = w.replace(/^\d{2}\s-\s/, '');
        if (!q || w.toLowerCase().indexOf(q) > -1 || name.toLowerCase().indexOf(q) > -1) {
            var isSelected = (wilayaInput.value === w);
            html += '<button type="button" onmousedown="selectWilaya(\'' + w.replace(/'/g, "\\'") + '\')" class="w-full text-left px-4 py-2.5 text-sm hover:bg-indigo-50 hover:text-indigo-700 transition-colors ' + (isSelected ? 'font-bold text-indigo-700 bg-indigo-50' : 'text-gray-600') + '">' + w + '</button>';
            count++;
        }
    });

    if (count === 0 && q) {
        html = '<div class="px-4 py-3 text-sm text-gray-400">No wilayas found</div>';
    }

    wilayaDropdown.innerHTML = html;
    wilayaDropdown.classList.remove('hidden');
}

function selectWilaya(w) {
    wilayaInput.value = w;
    wilayaDropdown.classList.add('hidden');
    document.getElementById('search-form').submit();
}

function clearWilaya() {
    wilayaInput.value = '';
    document.getElementById('search-form').submit();
}

/* ── Search input: clear button + live toggle ── */
var searchInput = document.getElementById('q');
var clearBtn = document.getElementById('clear-search-btn');

function clearSearch() {
    if (searchInput) { searchInput.value = ''; }
    if (clearBtn) { clearBtn.classList.add('hidden'); clearBtn.classList.remove('flex'); }
    document.getElementById('search-form').submit();
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
            document.getElementById('search-form').submit();
        }
    });
}

/* ── Clear search (Mobile) ── */
var mobSearchInput = document.getElementById('mob-q');
var mobClearBtn = document.getElementById('mob-clear-search-btn');

function clearMobSearch() {
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
            document.getElementById('mob-search-form').submit();
        }
    });
}

/* ── Mobile filter drawer ── */
function openFilterDrawer() {
    document.getElementById('filter-overlay').classList.remove('hidden');
    document.getElementById('filter-drawer').style.transform = 'translateY(0)';
    document.body.style.overflow = 'hidden';
}
function closeFilterDrawer() {
    document.getElementById('filter-overlay').classList.add('hidden');
    document.getElementById('filter-drawer').style.transform = 'translateY(100%)';
    document.body.style.overflow = '';
}
// Swipe down to close
(function() {
    var drawer = document.getElementById('filter-drawer');
    if (!drawer) return;
    var startY = 0;
    drawer.addEventListener('touchstart', function(e) { startY = e.touches[0].clientY; }, {passive:true});
    drawer.addEventListener('touchend', function(e) { if (e.changedTouches[0].clientY - startY > 60) closeFilterDrawer(); }, {passive:true});
})();

/* ── Favorites toggle ── */
async function toggleFavorite(craftsmanId, btnElement) {
    var isCurrentlyFavorite = btnElement.classList.contains('text-pink-500');

    if (isCurrentlyFavorite) {
        btnElement.classList.remove('border-pink-200', 'text-pink-500');
        btnElement.classList.add('border-gray-200', 'text-gray-300');
        btnElement.title = 'Save to favorites';
        btnElement.innerHTML = '<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>';
    } else {
        btnElement.classList.add('border-pink-200', 'text-pink-500');
        btnElement.classList.remove('border-gray-200', 'text-gray-300');
        btnElement.title = 'Remove from favorites';
        btnElement.innerHTML = '<svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>';
    }

    try {
        var res = await fetch('<?= APP_URL ?>/favorites/toggle', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ craftsman_id: craftsmanId, csrf_token: '<?= $_SESSION['csrf_token'] ?? '' ?>' })
        });
        var data = await res.json();
        if (!data.success) location.reload();
    } catch (e) { location.reload(); }
}
</script>