<!-- Post a Job -->
<?php
if (isset($_SESSION['role']) && $_SESSION['role'] !== 'homeowner') {
    header("Location: " . APP_URL . "/jobs");
    exit;
}
$hideFooter = true;

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

$categories = [
    'Plumbing'         => ['icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4',        'bg' => 'bg-blue-50',   'border' => 'border-blue-200',   'icon_color' => 'text-blue-600 bg-blue-100'],
    'Electrical'       => ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z',                                                                                                                       'bg' => 'bg-yellow-50', 'border' => 'border-yellow-200', 'icon_color' => 'text-yellow-600 bg-yellow-100'],
    'Carpentry'        => ['icon' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z', 'bg' => 'bg-orange-50', 'border' => 'border-orange-200', 'icon_color' => 'text-orange-600 bg-orange-100'],
    'Painting'         => ['icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'bg' => 'bg-pink-50',   'border' => 'border-pink-200',   'icon_color' => 'text-pink-600 bg-pink-100'],
    'Roofing'          => ['icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'bg' => 'bg-stone-50',  'border' => 'border-stone-200',  'icon_color' => 'text-stone-600 bg-stone-100'],
    'HVAC'             => ['icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',                                   'bg' => 'bg-cyan-50',   'border' => 'border-cyan-200',   'icon_color' => 'text-cyan-600 bg-cyan-100'],
    'Landscaping'      => ['icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',                                    'bg' => 'bg-green-50',  'border' => 'border-green-200',  'icon_color' => 'text-green-600 bg-green-100'],
    'Tiling'           => ['icon' => 'M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm6 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 11a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm6 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z', 'bg' => 'bg-purple-50', 'border' => 'border-purple-200', 'icon_color' => 'text-purple-600 bg-purple-100'],
    'General Handyman' => ['icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'bg' => 'bg-indigo-50', 'border' => 'border-indigo-200', 'icon_color' => 'text-indigo-600 bg-indigo-100'],
];
?>

<div class="bg-gray-50 min-h-screen py-8 pb-32">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <a href="<?= APP_URL ?>/jobs"
               class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition mb-4 group">
                <svg class="mr-1.5 h-4 w-4 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Job Board
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Post a Job</h1>
            <p class="mt-2 text-sm text-gray-500">Describe your project — top-rated verified craftsmen in your wilaya will send you competitive quotes in minutes.</p>
        </div>

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

            <!-- ════ FORM (2/3) ══════════════════════════════ -->
            <div class="lg:col-span-2 space-y-6">
            <form action="<?= APP_URL ?>/jobs/create" method="POST" id="job-form" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                <input type="hidden" name="category" id="category-input" value="<?= e($_POST['category'] ?? '') ?>">

                <!-- ── 1: Title + Description ───────────────────── -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                        <div class="h-7 w-7 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0 shadow-sm shadow-indigo-200">
                            <span class="text-white text-xs font-bold">1</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Job Details</p>
                            <p class="text-xs text-gray-500">Give your job a clear title and detailed description.</p>
                        </div>
                    </div>
                    <div class="px-6 py-5 space-y-5">

                        <div>
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5 pt-1">
                                Job Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" required maxlength="100"
                                   placeholder="e.g. Fix leaking kitchen faucet"
                                   value="<?= e($_POST['title'] ?? '') ?>"
                                   class="block w-full px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400
                                          focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <p class="mt-1.5 text-xs text-gray-500">A clear title gets more relevant quotes.</p>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" id="description" rows="6" required maxlength="2000"
                                      placeholder="Describe the work in detail — materials needed, size of the area, urgency, any specific requirements..."
                                      class="block w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400
                                             focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none leading-relaxed"
                                      oninput="document.getElementById('desc-count').textContent=this.value.length"
                            ><?= e($_POST['description'] ?? '') ?></textarea>
                            <div class="mt-1.5 flex justify-between items-center text-xs text-gray-500">
                                <span>Be as descriptive as possible.</span>
                                <div><span id="desc-count" class="font-medium"><?= strlen($_POST['description'] ?? '') ?></span>/2000</div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ── 2: Category picker ────────────────────────── -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mt-6">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                        <div class="h-7 w-7 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0 shadow-sm shadow-indigo-200">
                            <span class="text-white text-xs font-bold">2</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Service Category <span class="text-red-500">*</span></p>
                            <p class="text-xs text-gray-500">Pick the category that best matches your job.</p>
                        </div>
                    </div>
                    <div class="px-6 py-5">
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" id="category-grid">
                            <?php foreach ($categories as $catName => $catData):
                                $isSelected = ($_POST['category'] ?? '') === $catName;
                                $colors = get_category_classes($catName); // e.g. ['badge' => 'bg-blue-50 text-blue-700 ring-blue-600/20', 'text' => 'text-blue-600']
                                
                                // Extract the core tailwind color name like "blue", "indigo", "orange" from the text value
                                preg_match('/text-([a-z]+)-[0-9]+/', $colors['text'], $matches);
                                $colorName = $matches[1] ?? 'indigo';
                                
                                $activeBtnClass = "bg-{$colorName}-50 border-{$colorName}-300 ring-1 ring-{$colorName}-500/50";
                                $activeIconClass = "bg-white text-{$colorName}-600 shadow-sm shadow-{$colorName}-100";
                                $activeTextClass = "text-{$colorName}-800";
                                
                                $defaultBtnClass = "bg-white border-gray-200 hover:border-gray-300 hover:bg-gray-50 hover:shadow-sm";
                                $defaultIconClass = "bg-gray-100 text-gray-500";
                                $defaultTextClass = "text-gray-700";
                            ?>
                            <button type="button"
                                    data-name="<?= e($catName) ?>"
                                    data-active-btn="<?= $activeBtnClass ?>"
                                    data-active-icon="<?= $activeIconClass ?>"
                                    data-active-text="<?= $activeTextClass ?>"
                                    data-default-btn="<?= $defaultBtnClass ?>"
                                    data-default-icon="<?= $defaultIconClass ?>"
                                    data-default-text="<?= $defaultTextClass ?>"
                                    onclick="selectCategory(this)"
                                    class="category-btn flex flex-col sm:flex-row items-center justify-center sm:justify-start gap-1.5 sm:gap-3 p-3 rounded-xl border transition-all duration-200 text-center sm:text-left
                                           <?= $isSelected ? $activeBtnClass : $defaultBtnClass ?>">
                                
                                <div class="cat-icon h-8 w-8 sm:h-9 sm:w-9 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors
                                            <?= $isSelected ? $activeIconClass : $defaultIconClass ?>">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="<?= $catData['icon'] ?>"/>
                                    </svg>
                                </div>
                                <span class="cat-label text-xs sm:text-sm font-semibold tracking-tight leading-tight <?= $isSelected ? $activeTextClass : $defaultTextClass ?>">
                                    <?= e($catName) ?>
                                </span>
                            </button>
                            <?php endforeach; ?>
                        </div>
                        <div id="category-error" class="hidden mt-3 text-sm font-medium text-red-600 bg-red-50 border border-red-100 rounded-lg px-4 py-2.5 flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Please select a service category to proceed.
                        </div>
                    </div>
                </div>

                <!-- ── 3: Location + Budget ──────────────────────── -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mt-6">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                        <div class="h-7 w-7 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0 shadow-sm shadow-indigo-200">
                            <span class="text-white text-xs font-bold">3</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Location &amp; Budget</p>
                            <p class="text-xs text-gray-500">Where is the job and what's your expected budget?</p>
                        </div>
                    </div>
                    <div class="px-6 py-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                            <!-- Wilaya -->
                            <div>
                                <label for="address" class="block text-sm font-semibold text-gray-700 mb-1.5 pt-1">
                                    Wilaya <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <select name="address" id="address" required
                                            class="block w-full pl-10 pr-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm text-gray-900
                                                   focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                        <option value="">— Select your Wilaya —</option>
                                        <?php foreach ($wilayas as $w): ?>
                                        <option value="<?= e($w) ?>" <?= ($_POST['address'] ?? '') === $w ? 'selected' : '' ?>>
                                            <?= e($w) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Budget -->
                            <div>
                                <label for="budget" class="block text-sm font-semibold text-gray-700 mb-1.5 pt-1">
                                    Budget
                                    <span class="text-gray-400 font-normal ml-1 text-xs">(optional)</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="budget" id="budget"
                                           placeholder="e.g. 5000 – 10000"
                                           value="<?= e($_POST['budget'] ?? '') ?>"
                                           class="block w-full px-4 py-2.5 pr-14 bg-gray-50/50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400
                                                  focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none border-l border-gray-200 my-1.5 pl-3">
                                        <span class="text-xs font-bold text-gray-400">DZD</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ── 4: Photos ───────────────────────────────── -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mt-6">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center gap-3">
                        <div class="h-7 w-7 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0 shadow-sm shadow-indigo-200">
                            <span class="text-white text-xs font-bold">4</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">Photos <span class="text-gray-400 font-normal ml-1 text-xs">(optional)</span></p>
                            <p class="text-xs text-gray-500">Photos help craftsmen understand your project better. Max 3 images.</p>
                        </div>
                    </div>
                    <div class="px-6 py-5">
                        <!-- Drop Zone -->
                        <div id="drop-zone" class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer transition-colors hover:border-indigo-400 hover:bg-indigo-50/30"
                             onclick="document.getElementById('file-input').click()">
                            <input type="file" name="images[]" id="file-input" multiple accept="image/jpeg,image/png,image/webp" class="hidden" onchange="handleFiles(this.files)">
                            <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-2 text-sm font-semibold text-gray-700">Click to upload or drag & drop</p>
                            <p class="mt-1 text-xs text-gray-500">JPG, PNG, or WebP • Max 2 MB each • Up to 3 images</p>
                        </div>
                        <!-- Image Previews -->
                        <div id="image-previews" class="mt-4 grid grid-cols-3 gap-3" style="display:none"></div>
                        <div id="image-error" class="hidden mt-3 text-sm text-red-600 bg-red-50 border border-red-100 rounded-lg px-4 py-2 flex items-center gap-2">
                            <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span id="image-error-text"></span>
                        </div>
                    </div>
                </div>

            </form>
            </div><!-- end left col -->

            <!-- ════ Sidebar for Tips & Info (1/3) ════════════════════════════ -->
            <div class="space-y-5 order-last">

                <!-- Tips card -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="h-1.5 w-full bg-indigo-500"></div>
                    <div class="p-6">
                        <div class="flex items-center gap-2.5 mb-5">
                            <div class="h-6 w-6 rounded flex items-center justify-center bg-indigo-50">
                                <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            <h2 class="text-xs font-bold text-gray-800 tracking-wider uppercase">Tips for Success</h2>
                        </div>
                        
                        <div class="space-y-5">
                            <?php foreach ([
                                ['M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'Be Highly Specific',   'Mention specific dimensions, materials needed, and current state of the problem.', 'text-amber-500', 'bg-amber-50'],
                                ['M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z', 'Set Exact Location', 'Craftsmen sort jobs by proximity. Selecting your exact wilaya gets you faster replies.', 'text-rose-500', 'bg-rose-50'],
                                ['M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'Mention a Budget',   'Providing a realistic budget sets expectations instantly and filters out extreme quotes.', 'text-emerald-500', 'bg-emerald-50'],
                            ] as [$iconPath, $title, $desc, $iconColor, $iconBg]): ?>
                            <div class="flex items-start gap-3.5">
                                <div class="h-8 w-8 shrink-0 rounded flex items-center justify-center <?= $iconBg ?>">
                                    <svg class="h-4 w-4 <?= $iconColor ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="<?= $iconPath ?>"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800"><?= $title ?></p>
                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed"><?= $desc ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Free notice -->
                <div class="bg-emerald-50 border border-emerald-200/60 rounded-2xl p-4 flex items-start gap-3">
                    <svg class="h-5 w-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-emerald-900 leading-tight">100% Free</p>
                        <p class="text-xs text-emerald-700 mt-1 leading-relaxed">Posting a job on Crafts is completely free. We do not charge homeowners any hidden platform fees.</p>
                    </div>
                </div>

            </div>
        </div><!-- end grid -->
    </div>

    <!-- Sticky Save Bar -->
    <div class="fixed bottom-0 left-0 right-0 z-40 bg-white/80 backdrop-blur-lg border-t border-gray-200 shadow-[0_-4px_20px_rgba(0,0,0,0.06)]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            <a href="<?= APP_URL ?>/jobs"
               class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" form="job-form"
                class="inline-flex items-center gap-2 px-8 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Publish Job Post
            </button>
        </div>
    </div>
</div>

<!-- Lightbox -->
<div id="lightbox" class="fixed inset-0 z-[60] hidden">
    <div class="fixed inset-0 bg-black bg-opacity-95 backdrop-blur-sm" onclick="closeLightbox()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <button onclick="closeLightbox()" class="absolute top-4 right-4 z-[70] text-white hover:text-gray-300 transition p-2 bg-white/10 rounded-full hover:bg-white/20">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                 <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <img id="lightbox-img" src="" class="max-h-[85vh] max-w-[90vw] object-contain rounded-lg shadow-2xl relative z-[60]">
    </div>
</div>

<script>
function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
});

function selectCategory(btn) {
    const catName = btn.getAttribute('data-name');
    document.getElementById('category-input').value = catName;
    document.getElementById('category-error').classList.add('hidden');

    // Reset all buttons to default classes
    document.querySelectorAll('.category-btn').forEach(function(b) {
        // remove active, add default
        b.className = 'category-btn flex flex-col sm:flex-row items-center justify-center sm:justify-start gap-1.5 sm:gap-3 p-3 rounded-xl border transition-all duration-200 text-center sm:text-left ' + b.getAttribute('data-default-btn');
        const icon = b.querySelector('.cat-icon');
        const label = b.querySelector('.cat-label');
        if (icon)  icon.className = 'cat-icon h-8 w-8 sm:h-9 sm:w-9 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors ' + b.getAttribute('data-default-icon');
        if (label) label.className = 'cat-label text-xs sm:text-sm font-semibold tracking-tight leading-tight ' + b.getAttribute('data-default-text');
    });

    // Apply active classes to clicked button
    btn.className = 'category-btn flex flex-col sm:flex-row items-center justify-center sm:justify-start gap-1.5 sm:gap-3 p-3 rounded-xl border transition-all duration-200 text-center sm:text-left ' + btn.getAttribute('data-active-btn');
    const icon = btn.querySelector('.cat-icon');
    const label = btn.querySelector('.cat-label');
    if (icon)  icon.className = 'cat-icon h-8 w-8 sm:h-9 sm:w-9 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors ' + btn.getAttribute('data-active-icon');
    if (label) label.className = 'cat-label text-xs sm:text-sm font-semibold tracking-tight leading-tight ' + btn.getAttribute('data-active-text');
}

document.getElementById('job-form').addEventListener('submit', function(e) {
    if (!document.getElementById('category-input').value) {
        e.preventDefault();
        var errEl = document.getElementById('category-error');
        errEl.classList.remove('hidden');
        // Add a slight shake animation to the category grid to draw attention
        var grid = document.getElementById('category-grid');
        grid.style.transform = 'translate(-5px, 0)';
        setTimeout(() => grid.style.transform = 'translate(5px, 0)', 100);
        setTimeout(() => grid.style.transform = 'translate(-3px, 0)', 200);
        setTimeout(() => grid.style.transform = 'translate(0, 0)', 300);
        
        grid.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});

/* Image upload preview */
const MAX_IMAGES = 3;
const MAX_SIZE = 2 * 1024 * 1024;
let selectedFiles = [];

function handleFiles(fileList) {
    const errEl = document.getElementById('image-error');
    const errText = document.getElementById('image-error-text');
    errEl.classList.add('hidden');

    for (let f of fileList) {
        if (selectedFiles.length >= MAX_IMAGES) {
            errText.textContent = 'Maximum ' + MAX_IMAGES + ' images allowed.';
            errEl.classList.remove('hidden');
            break;
        }
        if (!['image/jpeg','image/png','image/webp'].includes(f.type)) {
            errText.textContent = '"' + f.name + '" is not a supported format (JPG, PNG, WebP only).';
            errEl.classList.remove('hidden');
            continue;
        }
        if (f.size > MAX_SIZE) {
            errText.textContent = '"' + f.name + '" is too large (max 2 MB).';
            errEl.classList.remove('hidden');
            continue;
        }
        selectedFiles.push(f);
    }
    renderPreviews();
    syncFileInput();
}

function removeImage(idx) {
    selectedFiles.splice(idx, 1);
    renderPreviews();
    syncFileInput();
}

function renderPreviews() {
    const container = document.getElementById('image-previews');
    container.innerHTML = '';
    if (selectedFiles.length === 0) { container.style.display = 'none'; return; }
    container.style.display = 'grid';

    selectedFiles.forEach((file, idx) => {
        const div = document.createElement('div');
        div.className = 'relative group rounded-xl overflow-hidden border border-gray-200 aspect-square bg-gray-100';
        const img = document.createElement('img');
        img.className = 'w-full h-full object-cover cursor-zoom-in';
        const objectUrl = URL.createObjectURL(file);
        img.src = objectUrl;
        img.onclick = () => openLightbox(objectUrl);
        
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'absolute top-1.5 right-1.5 h-6 w-6 rounded-full bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 text-white flex items-center justify-center shadow-lg transition-colors';
        btn.innerHTML = '<svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';
        btn.onclick = () => removeImage(idx);
        div.appendChild(img);
        div.appendChild(btn);
        container.appendChild(div);
    });
}

function syncFileInput() {
    const dt = new DataTransfer();
    selectedFiles.forEach(f => dt.items.add(f));
    document.getElementById('file-input').files = dt.files;
}

/* Drag and drop */
const dropZone = document.getElementById('drop-zone');
['dragenter','dragover'].forEach(ev => dropZone.addEventListener(ev, e => { e.preventDefault(); dropZone.classList.add('border-indigo-500','bg-indigo-50'); }));
['dragleave','drop'].forEach(ev => dropZone.addEventListener(ev, e => { e.preventDefault(); dropZone.classList.remove('border-indigo-500','bg-indigo-50'); }));
dropZone.addEventListener('drop', e => { handleFiles(e.dataTransfer.files); });
</script>
