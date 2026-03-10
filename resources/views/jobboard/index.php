<!-- Job Board Listing -->
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <!-- Page Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-extrabold text-gray-900">Job Board</h1>
                <p class="mt-1 text-sm text-gray-500">Browse open jobs posted by homeowners looking for skilled professionals.</p>
            </div>
            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="<?= APP_URL ?>/jobs/create" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
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
            <form action="<?= APP_URL ?>/jobs" method="GET" class="bg-white p-6 shadow-sm rounded-lg space-y-4">
                <!-- Row 1: Text Search -->
                <div class="flex flex-col md:flex-row gap-3">
                    <div class="flex-grow">
                        <label for="q" class="sr-only">Search</label>
                        <input type="text" name="q" id="q" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" placeholder="Search jobs by title or description..."
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
                        <label for="wilaya" class="block text-xs font-medium text-gray-500 mb-1">Location (Wilaya)</label>
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
                            <option value="oldest" <?= (($filters['sort'] ?? '') === 'oldest') ? 'selected' : '' ?>>Oldest First</option>
                        </select>
                    </div>
                </div>
                <!-- Clear Filters -->
                <?php if (!empty($filters['search']) || !empty($filters['category']) || !empty($filters['wilaya']) || !empty($filters['sort'])): ?>
                <div class="flex justify-center pt-1">
                    <a href="<?= APP_URL ?>/jobs" class="inline-flex items-center text-xs text-red-500 hover:text-red-700 font-medium transition-colors duration-150">
                        <svg class="h-3.5 w-3.5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Clear all filters
                    </a>
                </div>
                <?php endif; ?>
            </form>
        </div>

        <!-- Job List -->
        <?php if (!empty($jobs)): ?>
        <div class="space-y-4">
            <?php foreach ($jobs as $job): ?>
            <a href="<?= APP_URL ?>/jobs/<?= $job['id'] ?>" class="block bg-white shadow rounded-lg hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <h2 class="text-lg font-semibold text-indigo-600 truncate"><?= htmlspecialchars($job['title']) ?></h2>
                            <div class="mt-1 flex items-center flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500">
                                <span class="flex items-center">
                                    <svg class="mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    <?= htmlspecialchars($job['first_name'] . ' ' . $job['last_name']) ?>
                                </span>
                                <span class="flex items-center">
                                    <svg class="mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    <?= htmlspecialchars(preg_replace('/^\d{2}\s-\s/', '', $job['address'])) ?>
                                </span>
                                <span class="flex items-center">
                                    <svg class="mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    <?= date('M d, Y', strtotime($job['created_at'])) ?>
                                </span>
                            </div>
                        </div>
                        <div class="ml-4 flex flex-col items-end space-y-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                <?= htmlspecialchars($job['service_category']) ?>
                            </span>
                            <?php if (!empty($job['budget_range'])): ?>
                            <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($job['budget_range']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p class="mt-3 text-sm text-gray-500 line-clamp-2"><?= htmlspecialchars(substr($job['description'], 0, 200)) ?>...</p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <!-- Empty State -->
        <div class="text-center py-16 bg-white shadow rounded-lg">
            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No jobs found</h3>
            <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or search keywords.</p>
            <?php if (!empty($filters['search']) || !empty($filters['category']) || !empty($filters['wilaya'])): ?>
            <div class="mt-4">
                <a href="<?= APP_URL ?>/jobs" class="text-red-500 font-semibold hover:text-red-700">Clear all filters</a>
            </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="mt-6">
                <a href="<?= APP_URL ?>/jobs/create" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                    Post a Job
                </a>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

</div>
