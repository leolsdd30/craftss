<!-- Discover Craftsmen View -->
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <!-- Header and Search Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Find Skilled Professionals</h1>
            <p class="text-gray-500 mb-6">Browse verified craftsmen with experience in your specific home project needs.</p>

            <!-- Search Form -->
            <form action="<?= APP_URL ?>/search" method="GET" class="bg-white p-6 shadow-sm rounded-lg space-y-4">
                <!-- Row 1: Text Search -->
                <div class="flex flex-col md:flex-row gap-3">
                    <div class="flex-grow">
                        <label for="q" class="sr-only">Search</label>
                        <input type="text" name="q" id="q" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" placeholder="Search by name, skill, or bio keywords..."
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2.5 border">
                    </div>
                    <button type="submit" class="inline-flex justify-center items-center px-6 py-2.5 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                        <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                        Search
                    </button>
                </div>
                <!-- Row 2: Filters -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="sm:flex-1">
                        <label for="category" class="block text-xs font-medium text-gray-500 mb-1">Category</label>
                        <select name="category" id="category" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border bg-white">
                            <option value="">All Categories</option>
                            <?php
                                $categories = ["Plumbing", "Electrical", "Carpentry", "Painting", "Roofing", "HVAC", "Landscaping", "Tiling", "General Handyman"];
                                foreach ($categories as $cat):
                            ?>
                                <option value="<?= $cat ?>" <?= (isset($filters['category']) && $filters['category'] === $cat) ? 'selected' : '' ?>><?= $cat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="sm:flex-1">
                        <label for="wilaya" class="block text-xs font-medium text-gray-500 mb-1">Wilaya</label>
                        <select name="wilaya" id="wilaya" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border bg-white">
                            <option value="">All Regions</option>
                            <?php 
                                $wilayas = [
                                    "01 - Adrar", "02 - Chlef", "03 - Laghouat", "04 - Oum El Bouaghi", "05 - Batna", "06 - Béjaïa", "07 - Biskra", "08 - Béchar", "09 - Blida", "10 - Bouira",
                                    "11 - Tamanrasset", "12 - Tébessa", "13 - Tlemcen", "14 - Tiaret", "15 - Tizi Ouzou", "16 - Alger", "17 - Djelfa", "18 - Jijel", "19 - Sétif", "20 - Saïda",
                                    "21 - Skikda", "22 - Sidi Bel Abbès", "23 - Annaba", "24 - Guelma", "25 - Constantine", "26 - Médéa", "27 - Mostaganem", "28 - M'Sila", "29 - Mascara", "30 - Ouargla",
                                    "31 - Oran", "32 - El Bayadh", "33 - Illizi", "34 - Bordj Bou Arréridj", "35 - Boumerdès", "36 - El Tarf", "37 - Tindouf", "38 - Tissemsilt", "39 - El Oued", "40 - Khenchela",
                                    "41 - Souk Ahras", "42 - Tipaza", "43 - Mila", "44 - Aïn Defla", "45 - Naâma", "46 - Aïn Témouchent", "47 - Ghardaïa", "48 - Relizane", "49 - Timimoun", "50 - Bordj Badji Mokhtar",
                                    "51 - Ouled Djellal", "52 - Béni Abbès", "53 - In Salah", "54 - In Guezzam", "55 - Touggourt", "56 - Djanet", "57 - El M'Ghair", "58 - El Meniaa"
                                ];
                                foreach($wilayas as $w):
                            ?>
                                <option value="<?= $w ?>" <?= (isset($filters['wilaya']) && $filters['wilaya'] === $w) ? 'selected' : '' ?>><?= $w ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="sm:flex-1">
                        <label for="sort" class="block text-xs font-medium text-gray-500 mb-1">Sort By</label>
                        <select name="sort" id="sort" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border bg-white">
                            <option value="" <?= empty($filters['sort']) ? 'selected' : '' ?>>Newest First</option>
                            <option value="rate_low" <?= (($filters['sort'] ?? '') === 'rate_low') ? 'selected' : '' ?>>Rate: Low to High</option>
                            <option value="rate_high" <?= (($filters['sort'] ?? '') === 'rate_high') ? 'selected' : '' ?>>Rate: High to Low</option>
                        </select>
                    </div>
                </div>
                <!-- Clear Filters -->
                <?php if (!empty($filters['search']) || !empty($filters['category']) || !empty($filters['wilaya']) || !empty($filters['sort'])): ?>
                <div class="flex justify-center pt-1">
                    <a href="<?= APP_URL ?>/search" class="inline-flex items-center text-xs text-red-500 hover:text-red-700 font-medium transition-colors duration-150">
                        <svg class="h-3.5 w-3.5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Clear all filters
                    </a>
                </div>
                <?php endif; ?>
            </form>
        </div>

        <!-- Craftsman Grid -->
        <?php if (!empty($craftsmen)): ?>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <?php foreach ($craftsmen as $craft): ?>
            <div class="bg-white overflow-hidden shadow rounded-lg flex flex-col hover:shadow-md transition-shadow duration-200 relative group">
                <!-- Favorite Heart -->
                <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'homeowner'): ?>
                <button type="button" onclick="toggleFavorite(<?= $craft['user_id'] ?>, this)" class="absolute top-4 right-4 p-2 rounded-full z-10 bg-white shadow-sm border <?= $craft['is_favorite'] ? 'border-pink-200 text-pink-500 hover:bg-pink-50' : 'border-gray-200 text-gray-300 hover:text-pink-400 hover:border-pink-200' ?> transition-colors duration-200 outline-none focus:outline-none" title="<?= $craft['is_favorite'] ? 'Remove from favorites' : 'Save to favorites' ?>">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" <?= $craft['is_favorite'] ? 'viewBox="0 0 20 20" fill="currentColor"' : 'fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"' ?>>
                        <path <?= $craft['is_favorite'] ? 'fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"' : 'stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"' ?> />
                    </svg>
                </button>
                <?php endif; ?>

                <div class="p-6 flex-grow">
                    <div class="flex items-center space-x-4 mb-4">
                        <img class="h-16 w-16 rounded-full object-cover border-2 <?= $craft['is_verified'] ? 'border-green-300' : 'border-gray-100' ?>" 
                             src="<?= get_profile_picture_url($craft['profile_picture'] ?? 'default.png', $craft['first_name'], $craft['last_name']) ?>" 
                             alt="<?= htmlspecialchars($craft['first_name']) ?>">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 flex items-center">
                                <?= htmlspecialchars($craft['first_name'] . ' ' . $craft['last_name']) ?>
                                <?php if ($craft['is_verified']): ?>
                                <svg class="ml-1.5 h-4 w-4 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812 3.066 3.066 0 00.723 1.745 3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <?php endif; ?>
                            </h2>
                            <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600"><?= htmlspecialchars($craft['service_category']) ?></p>
                            <?php if (!empty($craft['wilaya'])): ?>
                            <p class="text-xs font-medium text-gray-500 mt-1 flex items-center">
                                <svg class="h-3 w-3 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                <?= htmlspecialchars(preg_replace('/^\d{2}\s-\s/', '', $craft['wilaya'])) ?>
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($craft['bio'])): ?>
                    <p class="text-sm text-gray-500 line-clamp-3 mb-4"><?= htmlspecialchars($craft['bio']) ?></p>
                    <?php endif; ?>

                    <div class="flex items-center justify-between text-sm py-3 border-t border-gray-100 mt-auto">
                        <div>
                            <span class="text-xs text-gray-400 uppercase tracking-wider">Hourly Rate</span>
                            <p class="font-bold text-gray-900 text-base">$<?= number_format($craft['hourly_rate'], 2) ?></p>
                        </div>
                        <?php if ($craft['is_verified']): ?>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Verified
                        </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 mt-auto">
                    <a href="<?= APP_URL ?>/profile/<?= $craft['username'] ?>" class="block w-full text-center py-2 px-4 border border-transparent rounded-md text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                        View Full Profile
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="text-center py-24 bg-white shadow rounded-lg">
            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M23 21v-2a4 4 0 00-3-3.87m-4-12a4 4 0 010 7.75" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No craftsmen found</h3>
            <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or search keywords.</p>
            <div class="mt-6">
                <a href="<?= APP_URL ?>/search" class="text-indigo-600 font-semibold hover:text-indigo-500">Clear all filters</a>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<script>
async function toggleFavorite(craftsmanId, btnElement) {
    const icon = btnElement.querySelector('svg');
    const isCurrentlyFavorite = btnElement.classList.contains('text-pink-500');

    // Optimistic UI Update
    if (isCurrentlyFavorite) {
        btnElement.classList.remove('border-pink-200', 'text-pink-500', 'hover:bg-pink-50');
        btnElement.classList.add('border-gray-200', 'text-gray-300', 'hover:text-pink-400', 'hover:border-pink-200');
        btnElement.title = 'Save to favorites';
        icon.removeAttribute('fill');
        icon.setAttribute('fill', 'none');
        icon.setAttribute('stroke', 'currentColor');
        icon.setAttribute('stroke-width', '2');
        icon.setAttribute('viewBox', '0 0 24 24');
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />';
    } else {
        btnElement.classList.remove('border-gray-200', 'text-gray-300', 'hover:text-pink-400', 'hover:border-pink-200');
        btnElement.classList.add('border-pink-200', 'text-pink-500', 'hover:bg-pink-50');
        btnElement.title = 'Remove from favorites';
        icon.removeAttribute('stroke');
        icon.removeAttribute('stroke-width');
        icon.setAttribute('fill', 'currentColor');
        icon.setAttribute('viewBox', '0 0 20 20');
        icon.innerHTML = '<path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />';
    }

    try {
        const response = await fetch('<?= APP_URL ?>/favorites/toggle', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ craftsman_id: craftsmanId })
        });
        
        const data = await response.json();
        if (!data.success) {
            alert(data.message || 'Failed to update favorites.');
            window.location.reload(); // revert
        }
    } catch (e) {
        console.error('Error toggling favorite:', e);
        window.location.reload(); // revert
    }
}
</script>
