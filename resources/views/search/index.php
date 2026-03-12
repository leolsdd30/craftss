<!-- Find Craftsmen Page -->
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-1">Find Skilled Professionals</h1>
            <p class="text-gray-500">Browse verified craftsmen with experience in your specific home project needs.</p>
        </div>

        <!-- Search & Filter Form -->
        <div class="bg-white shadow-sm rounded-xl p-5 mb-8 border border-gray-100">
            <form action="<?= APP_URL ?>/search" method="GET" id="search-form">

                <!-- Row 1: Text + Buttons -->
                <div class="flex flex-col md:flex-row gap-3">
                    <div class="flex-grow relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="q" id="q"
                               value="<?= htmlspecialchars($filters['search'] ?? '') ?>"
                               placeholder="Search by name, skill, or bio..."
                               class="w-full pl-9 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 border">
                    </div>
                    <div class="flex gap-2">
                        <?php
                            $activeFilterCount = (int)!empty($filters['category'])
                                              + (int)!empty($filters['wilaya'])
                                              + (int)!empty($filters['sort']);
                        ?>
                        <button type="button"
                                onclick="document.getElementById('filter-section').classList.toggle('hidden')"
                                class="inline-flex items-center px-4 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-colors duration-200 relative">
                            <svg class="h-4 w-4 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                            </svg>
                            Filters
                            <?php if ($activeFilterCount > 0): ?>
                            <span class="ml-1.5 inline-flex items-center justify-center w-4 h-4 text-xs font-bold rounded-full bg-indigo-600 text-white">
                                <?= $activeFilterCount ?>
                            </span>
                            <?php endif; ?>
                        </button>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition duration-150 shadow-sm">
                            Search
                        </button>
                    </div>
                </div>

                <!-- Row 2: Collapsible Filters -->
                <div id="filter-section"
                     class="<?= $activeFilterCount > 0 ? '' : 'hidden' ?> mt-4 pt-4 border-t border-gray-100">
                    <div class="flex flex-col sm:flex-row gap-3">

                        <!-- Category -->
                        <div class="sm:flex-1">
                            <label for="category" class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Category</label>
                            <select name="category" id="category"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-3 py-2 border bg-white">
                                <option value="">All Categories</option>
                                <?php
                                $categories = ["Plumbing","Electrical","Carpentry","Painting","Roofing","HVAC","Landscaping","Tiling","General Handyman"];
                                foreach ($categories as $cat):
                                ?>
                                <option value="<?= $cat ?>" <?= (($filters['category'] ?? '') === $cat) ? 'selected' : '' ?>><?= $cat ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Wilaya -->
                        <div class="sm:flex-1">
                            <label for="wilaya" class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Wilaya</label>
                            <select name="wilaya" id="wilaya"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-3 py-2 border bg-white">
                                <option value="">All Regions</option>
                                <?php
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
                                foreach ($wilayas as $w):
                                ?>
                                <option value="<?= $w ?>" <?= (($filters['wilaya'] ?? '') === $w) ? 'selected' : '' ?>><?= $w ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Sort -->
                        <div class="sm:flex-1">
                            <label for="sort" class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Sort By</label>
                            <select name="sort" id="sort"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-3 py-2 border bg-white">
                                <option value=""       <?= empty($filters['sort']) ? 'selected' : '' ?>>Newest First</option>
                                <option value="top_rated"  <?= (($filters['sort'] ?? '') === 'top_rated')  ? 'selected' : '' ?>>Top Rated</option>
                                <option value="rate_low"   <?= (($filters['sort'] ?? '') === 'rate_low')   ? 'selected' : '' ?>>Rate: Low to High</option>
                                <option value="rate_high"  <?= (($filters['sort'] ?? '') === 'rate_high')  ? 'selected' : '' ?>>Rate: High to Low</option>
                            </select>
                        </div>

                    </div>

                    <?php if ($activeFilterCount > 0): ?>
                    <div class="flex justify-end mt-3">
                        <a href="<?= APP_URL ?>/search"
                           class="inline-flex items-center text-xs text-red-500 hover:text-red-700 font-medium transition-colors duration-150">
                            <svg class="h-3.5 w-3.5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            Clear all filters
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

            </form>
        </div>

        <?php if (!empty($craftsmen)): ?>

        <!-- Result count -->
        <p class="text-sm text-gray-500 mb-5">
            Showing <span class="font-semibold text-gray-800"><?= count($craftsmen) ?></span>
            <?= count($craftsmen) === 1 ? 'craftsman' : 'craftsmen' ?>
            <?php if ($activeFilterCount > 0): ?>
            matching your filters
            <?php endif; ?>
        </p>

        <!-- Craftsman Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <?php foreach ($craftsmen as $craft): ?>

            <!-- Card -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col overflow-hidden relative group">

                <!-- Category color mapping -->
                <?php
                $catColors = [
                    'Plumbing'         => 'bg-blue-500',
                    'Electrical'       => 'bg-yellow-500',
                    'Carpentry'        => 'bg-orange-500',
                    'Painting'         => 'bg-pink-500',
                    'Roofing'          => 'bg-stone-500',
                    'HVAC'             => 'bg-cyan-500',
                    'Tiling'           => 'bg-teal-500',
                    'Landscaping'      => 'bg-green-500',
                    'General Handyman' => 'bg-indigo-500'
                ];
                $topBarColor = $catColors[$craft['service_category']] ?? 'bg-gray-400';
                ?>
                <!-- Verified top banner or Category color banner -->
                <?php if ($craft['is_verified']): ?>
                <div class="h-1 bg-gradient-to-r from-green-400 to-emerald-500 w-full absolute top-0 left-0 z-10" title="Verified Professional"></div>
                <?php else: ?>
                <div class="h-1 <?= $topBarColor ?> w-full absolute top-0 left-0 z-10"></div>
                <?php endif; ?>

                <!-- Favorite heart — homeowners only -->
                <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'homeowner'): ?>
                <button type="button"
                        onclick="toggleFavorite(<?= $craft['user_id'] ?>, this)"
                        class="absolute top-4 right-4 p-1.5 rounded-full z-20 bg-white shadow-sm border
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
                <div class="p-5 pt-6 flex-grow flex flex-col">

                    <!-- Avatar + Name row -->
                    <div class="flex items-start space-x-3 mb-4">
                        <div class="relative flex-shrink-0">
                            <img class="h-14 w-14 rounded-full object-cover border-2 shadow-sm
                                        <?= $craft['is_verified'] ? 'border-green-300' : 'border-gray-100' ?>"
                                 src="<?= get_profile_picture_url($craft['profile_picture'] ?? 'default.png', $craft['first_name'], $craft['last_name']) ?>"
                                 alt="<?= htmlspecialchars($craft['first_name']) ?>">
                            <?php if ($craft['is_verified']): ?>
                            <div class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5 shadow-sm">
                                <svg class="h-3.5 w-3.5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812 3.066 3.066 0 00.723 1.745 3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="min-w-0 flex-1 pt-0.5">
                            <h2 class="text-sm font-bold text-gray-900 leading-tight truncate">
                                <?= htmlspecialchars($craft['first_name'] . ' ' . $craft['last_name']) ?>
                            </h2>
                            <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wide mt-0.5">
                                <?= htmlspecialchars($craft['service_category']) ?>
                            </p>
                            <?php if (!empty($craft['wilaya'])): ?>
                            <p class="text-xs text-gray-400 mt-0.5 flex items-center">
                                <svg class="h-3 w-3 mr-1 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <?= htmlspecialchars(preg_replace('/^\d{2}\s-\s/', '', $craft['wilaya'])) ?>
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Star rating row -->
                    <div class="flex items-center gap-1.5 mb-3">
                        <?php
                            $avg   = (float) ($craft['avg_rating'] ?? 0);
                            $total = (int)   ($craft['total_reviews'] ?? 0);
                        ?>
                        <?php if ($total > 0): ?>
                            <div class="flex items-center">
                                <?php for ($s = 1; $s <= 5; $s++): ?>
                                <svg class="h-3.5 w-3.5 <?= $s <= round($avg) ? 'text-yellow-400' : 'text-gray-200' ?>"
                                     viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <?php endfor; ?>
                            </div>
                            <span class="text-xs font-semibold text-gray-700"><?= $avg ?></span>
                            <span class="text-xs text-gray-400">(<?= $total ?> <?= $total === 1 ? 'review' : 'reviews' ?>)</span>
                        <?php else: ?>
                            <div class="flex items-center">
                                <?php for ($s = 1; $s <= 5; $s++): ?>
                                <svg class="h-3.5 w-3.5 text-gray-200" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <?php endfor; ?>
                            </div>
                            <span class="text-xs text-gray-400">No reviews yet</span>
                        <?php endif; ?>
                    </div>

                    <!-- Bio snippet -->
                    <?php if (!empty($craft['bio'])): ?>
                    <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed mb-4 flex-grow">
                        <?= htmlspecialchars($craft['bio']) ?>
                    </p>
                    <?php else: ?>
                    <div class="flex-grow"></div>
                    <?php endif; ?>

                    <!-- Rate + Verified row -->
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100 mt-auto">
                        <div>
                            <span class="text-xs text-gray-400 uppercase tracking-wider">Hourly</span>
                            <p class="font-bold text-gray-900 text-base leading-tight">
                                $<?= number_format($craft['hourly_rate'], 2) ?>
                            </p>
                        </div>
                        <?php if ($craft['is_verified']): ?>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                            <svg class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Verified
                        </span>
                        <?php endif; ?>
                    </div>

                </div>

                <!-- Card footer — action buttons -->
                <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex gap-2">
                    <a href="<?= APP_URL ?>/profile/<?= htmlspecialchars($craft['username']) ?>"
                       class="flex-1 text-center py-2 px-3 rounded-lg text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 transition duration-150">
                        View Profile
                    </a>
                    <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'homeowner'): ?>
                    <a href="<?= APP_URL ?>/bookings/create/<?= htmlspecialchars($craft['username']) ?>"
                       class="flex-1 text-center py-2 px-3 rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                        Book Now
                    </a>
                    <?php endif; ?>
                </div>

            </div>
            <?php endforeach; ?>
        </div>

        <?php else: ?>

        <!-- Empty state -->
        <div class="text-center py-24 bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">No craftsmen found</h3>
            <p class="text-sm text-gray-500 mb-6">Try adjusting your filters or search keywords.</p>
            <a href="<?= APP_URL ?>/search"
               class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                Clear all filters
            </a>
        </div>

        <?php endif; ?>

    </div>
</div>

<script>
async function toggleFavorite(craftsmanId, btnElement) {
    const isCurrentlyFavorite = btnElement.classList.contains('text-pink-500');

    // Optimistic UI update
    if (isCurrentlyFavorite) {
        btnElement.classList.remove('border-pink-200', 'text-pink-500');
        btnElement.classList.add('border-gray-200', 'text-gray-300');
        btnElement.title = 'Save to favorites';
        btnElement.innerHTML = `<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>`;
    } else {
        btnElement.classList.add('border-pink-200', 'text-pink-500');
        btnElement.classList.remove('border-gray-200', 'text-gray-300');
        btnElement.title = 'Remove from favorites';
        btnElement.innerHTML = `<svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
        </svg>`;
    }

    // Fire the request
    try {
        const res = await fetch('<?= APP_URL ?>/favorites/toggle', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                craftsman_id: craftsmanId,
                csrf_token: '<?= e($_SESSION['csrf_token'] ?? '') ?>'
            })
        });
        const data = await res.json();
        if (!data.success) {
            // Revert on failure
            console.error(data.message);
            location.reload();
        }
    } catch (e) {
        console.error(e);
        location.reload();
    }
}
</script>