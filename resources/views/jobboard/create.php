<!-- Post a Job Form -->
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <a href="<?= APP_URL ?>/jobs" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">&larr; Back to Job Board</a>
            <h1 class="mt-2 text-3xl font-extrabold text-gray-900">Post a New Job</h1>
            <p class="mt-1 text-sm text-gray-500">Describe what you need done and professionals will come to you with quotes.</p>
        </div>

        <?php if (!empty($error)): ?>
        <div class="rounded-md bg-red-50 p-4 mb-6">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <p class="ml-3 text-sm font-medium text-red-800"><?= htmlspecialchars($error) ?></p>
            </div>
        </div>
        <?php endif; ?>

        <form action="<?= APP_URL ?>/jobs/create" method="POST" class="bg-white shadow rounded-lg">
            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
            <div class="px-6 py-6 space-y-6">

                <!-- Job Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Job Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" required placeholder="e.g. Fix leaking kitchen faucet"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border">
                </div>

                <!-- Service Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Service Category <span class="text-red-500">*</span></label>
                    <select name="category" id="category" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border bg-white">
                        <option value="">-- Select a category --</option>
                        <option value="Plumbing">Plumbing</option>
                        <option value="Electrical">Electrical</option>
                        <option value="Carpentry">Carpentry</option>
                        <option value="Painting">Painting</option>
                        <option value="Roofing">Roofing</option>
                        <option value="HVAC">HVAC / Air Conditioning</option>
                        <option value="Landscaping">Landscaping</option>
                        <option value="Tiling">Tiling</option>
                        <option value="General Handyman">General Handyman</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="5" required placeholder="Describe the work you need done in detail..."
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border"></textarea>
                    <p class="mt-1 text-xs text-gray-400">Be as specific as possible to get accurate quotes.</p>
                </div>

                <!-- Location (Wilaya) -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Location (Wilaya) <span class="text-red-500">*</span></label>
                    <select name="address" id="address" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border bg-white">
                        <option value="">-- Select a Wilaya --</option>
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
                            <option value="<?= $w ?>"><?= $w ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Budget Range -->
                <div>
                    <label for="budget" class="block text-sm font-medium text-gray-700">Budget Range <span class="text-gray-400">(optional)</span></label>
                    <input type="text" name="budget" id="budget" placeholder="e.g. 100 DZD - 300 DZD"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border">
                    <p class="mt-1 text-xs text-gray-400">Providing a budget helps craftsmen give you more accurate quotes.</p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex items-center justify-end space-x-3">
                <a href="<?= APP_URL ?>/jobs" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500 transition-colors duration-200">Cancel</a>
                <button type="submit" class="inline-flex justify-center px-6 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    Post Job
                </button>
            </div>
        </form>

    </div>
</div>
