<!-- Post a Job -->
<?php
if (isset($_SESSION['role']) && $_SESSION['role'] !== 'homeowner') {
    header("Location: " . APP_URL . "/jobs");
    exit;
}

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

<div class="bg-gray-50 min-h-screen py-10">
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="mb-8">
        <a href="<?= APP_URL ?>/jobs"
           class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition mb-4 group">
            <svg class="mr-1.5 h-4 w-4 group-hover:-translate-x-0.5 transition-transform duration-150" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Job Board
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Post a Job</h1>
        <p class="mt-1 text-sm text-gray-500">Describe what you need — verified craftsmen will send you their quotes.</p>
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

        <!-- ════ LEFT: sidebar (1/3) ════════════════════════════ -->
        <div class="space-y-4 lg:order-last">

            <!-- Tips card -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-indigo-600"></div>
                <div class="p-5">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Tips for a great post</p>
                    <div class="space-y-3.5">
                        <?php foreach ([
                            ['💡', 'Be specific',      'Mention materials, room size, current state of the problem.'],
                            ['📍', 'Set your location', 'Craftsmen filter by wilaya — selecting yours gets you faster replies.'],
                            ['💰', 'Add a budget',      'Optional but helps craftsmen tailor their quotes to what you expect.'],
                            ['📸', 'Describe clearly',  'The more context you give, the more accurate and competitive the quotes.'],
                        ] as [$emoji, $title, $desc]): ?>
                        <div class="flex items-start gap-3">
                            <span class="text-base flex-shrink-0 mt-0.5"><?= $emoji ?></span>
                            <div>
                                <p class="text-xs font-semibold text-gray-800"><?= $title ?></p>
                                <p class="text-xs text-gray-400 mt-0.5"><?= $desc ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- What happens next -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">What happens next</p>
                <div class="space-y-4">
                    <?php foreach ([
                        ['1', 'bg-indigo-600', 'Your job goes live',      'Verified craftsmen in your wilaya can see and quote on it immediately.'],
                        ['2', 'bg-indigo-600', 'Craftsmen send quotes',   'Review each quote, compare prices and profiles, then accept the best one.'],
                        ['3', 'bg-indigo-600', 'Job gets done',           'Once hired, track the booking in your dashboard and confirm on completion.'],
                    ] as [$num, $bg, $title, $desc]): ?>
                    <div class="flex items-start gap-3">
                        <div class="h-6 w-6 rounded-full <?= $bg ?> flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-white text-xs font-bold"><?= $num ?></span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-800"><?= $title ?></p>
                            <p class="text-xs text-gray-400 mt-0.5"><?= $desc ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Free notice -->
            <div class="bg-green-50 border border-green-100 rounded-2xl p-4 flex items-start gap-3">
                <svg class="h-5 w-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-xs font-bold text-green-800">Completely free</p>
                    <p class="text-xs text-green-700 mt-0.5">Posting a job on Crafts is 100% free. No hidden fees.</p>
                </div>
            </div>

        </div>

        <!-- ════ RIGHT: form (2/3) ══════════════════════════════ -->
        <div class="lg:col-span-2 space-y-5">
        <form action="<?= APP_URL ?>/jobs/create" method="POST" id="job-form">
            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="category" id="category-input" value="<?= e($_POST['category'] ?? '') ?>">

            <!-- ── 1: Title + Description ───────────────────── -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-3">
                    <div class="h-7 w-7 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-xs font-bold">1</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">Job Details</p>
                        <p class="text-xs text-gray-400">Give your job a clear title and detailed description.</p>
                    </div>
                </div>
                <div class="px-6 py-5 space-y-4">

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Job Title <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="title" id="title" required maxlength="100"
                               placeholder="e.g. Fix leaking kitchen faucet"
                               value="<?= e($_POST['title'] ?? '') ?>"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <p class="mt-1 text-xs text-gray-400">A clear title gets more relevant quotes.</p>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Description <span class="text-red-400">*</span>
                        </label>
                        <textarea name="description" id="description" rows="6" required maxlength="2000"
                                  placeholder="Describe the work in detail — materials needed, size of the area, urgency, any specific requirements..."
                                  class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400
                                         focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                                  oninput="document.getElementById('desc-count').textContent=this.value.length"
                        ><?= e($_POST['description'] ?? '') ?></textarea>
                        <p class="mt-1 text-xs text-gray-400 text-right">
                            <span id="desc-count"><?= strlen($_POST['description'] ?? '') ?></span>/2000
                        </p>
                    </div>

                </div>
            </div>

            <!-- ── 2: Category picker ────────────────────────── -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-3">
                    <div class="h-7 w-7 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-xs font-bold">2</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">Service Category <span class="text-red-400">*</span></p>
                        <p class="text-xs text-gray-400">Pick the category that best matches your job.</p>
                    </div>
                </div>
                <div class="px-6 py-5">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" id="category-grid">
                        <?php foreach ($categories as $catName => $catData):
                            $isSelected = ($_POST['category'] ?? '') === $catName;
                        ?>
                        <button type="button"
                                onclick="selectCategory('<?= e($catName) ?>', this)"
                                class="category-btn flex items-center gap-3 p-3 rounded-xl border-2 text-left transition-all duration-150
                                       <?= $isSelected
                                           ? 'border-indigo-500 bg-indigo-50'
                                           : 'border-gray-200 hover:border-indigo-300 hover:bg-gray-50' ?>">
                            <div class="h-8 w-8 rounded-lg flex items-center justify-center flex-shrink-0
                                        <?= $isSelected ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-500' ?>">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="<?= $catData['icon'] ?>"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium <?= $isSelected ? 'text-indigo-700' : 'text-gray-700' ?>">
                                <?= e($catName) ?>
                            </span>
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <p class="text-xs text-red-500 mt-2 hidden" id="category-error">
                        Please select a category before submitting.
                    </p>
                </div>
            </div>

            <!-- ── 3: Location + Budget ──────────────────────── -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-3">
                    <div class="h-7 w-7 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-xs font-bold">3</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">Location &amp; Budget</p>
                        <p class="text-xs text-gray-400">Where is the job and what's your budget?</p>
                    </div>
                </div>
                <div class="px-6 py-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <!-- Wilaya -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Wilaya <span class="text-red-400">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <select name="address" id="address" required
                                        class="block w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white text-gray-900
                                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
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
                            <label for="budget" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Budget
                                <span class="text-gray-400 font-normal ml-1 text-xs">(optional)</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="budget" id="budget"
                                       placeholder="e.g. 5000 – 10000"
                                       value="<?= e($_POST['budget'] ?? '') ?>"
                                       class="block w-full px-4 py-2.5 pr-16 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400
                                              focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span class="text-sm font-semibold text-gray-400">DZD</span>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-400">Helps craftsmen tailor their quotes.</p>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-between gap-4 pt-2 pb-4">
                <a href="<?= APP_URL ?>/jobs"
                   class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 sm:flex-none inline-flex justify-center items-center gap-2 px-8 py-2.5
                               bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold
                               rounded-xl shadow-sm transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Post Job
                </button>
            </div>

        </form>
        </div><!-- end right col -->

    </div><!-- end grid -->
</div>
</div>

<script>
// ── Category picker ──────────────────────────────────────────────────
function selectCategory(name, btn) {
    document.getElementById('category-input').value = name;
    document.getElementById('category-error').classList.add('hidden');

    document.querySelectorAll('.category-btn').forEach(function(b) {
        b.classList.remove('border-indigo-500', 'bg-indigo-50');
        b.classList.add('border-gray-200');
        var icon  = b.querySelector('div');
        var label = b.querySelector('span');
        if (icon)  { icon.className  = icon.className.replace('bg-indigo-100 text-indigo-600', 'bg-gray-100 text-gray-500'); }
        if (label) { label.className = label.className.replace('text-indigo-700', 'text-gray-700'); }
    });

    btn.classList.remove('border-gray-200');
    btn.classList.add('border-indigo-500', 'bg-indigo-50');
    var icon  = btn.querySelector('div');
    var label = btn.querySelector('span');
    if (icon)  { icon.className  = icon.className.replace('bg-gray-100 text-gray-500', 'bg-indigo-100 text-indigo-600'); }
    if (label) { label.className = label.className.replace('text-gray-700', 'text-indigo-700'); }
}

// ── Form validation ──────────────────────────────────────────────────
document.getElementById('job-form').addEventListener('submit', function(e) {
    if (!document.getElementById('category-input').value) {
        e.preventDefault();
        var errEl = document.getElementById('category-error');
        errEl.classList.remove('hidden');
        document.getElementById('category-grid').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>
