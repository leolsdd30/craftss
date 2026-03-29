<!-- Request Booking Page -->
<?php
$db = \App\Database\Database::getInstance()->getConnection();
$stmt = $db->prepare("
    SELECT cp.service_category, cp.hourly_rate, cp.is_verified,
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
$hourlyRate   = (float)($meta['hourly_rate']   ?? 0);
$avgRating    = (float)($meta['avg_rating']    ?? 0);
$totalReviews = (int)  ($meta['total_reviews'] ?? 0);
$isVerified   = !empty($meta['is_verified']);

$catColors = [
    'Plumbing'=>'bg-blue-100 text-blue-700','Electrical'=>'bg-yellow-100 text-yellow-700',
    'Carpentry'=>'bg-orange-100 text-orange-700','Painting'=>'bg-pink-100 text-pink-700',
    'Roofing'=>'bg-stone-100 text-stone-700','HVAC'=>'bg-cyan-100 text-cyan-700',
    'Landscaping'=>'bg-green-100 text-green-700','Tiling'=>'bg-purple-100 text-purple-700',
    'General Handyman'=>'bg-indigo-100 text-indigo-700',
];
$badgeClass = $catColors[$category] ?? 'bg-indigo-100 text-indigo-700';

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
    "46 - Aïn Témouchent","47 - Ghardaïa","48 - Relizane","49 - Timimoun",
    "50 - Bordj Badji Mokhtar","51 - Ouled Djellal","52 - Béni Abbès","53 - In Salah",
    "54 - In Guezzam","55 - Touggourt","56 - Djanet","57 - El M'Ghair","58 - El Meniaa"
];

$postDate    = $_POST['scheduled_date'] ?? '';
$postAddress = $_POST['address']        ?? '';
$postDesc    = $_POST['description']    ?? '';

$savedWilaya = '';
$savedStreet = $postAddress;
foreach ($wilayas as $w) {
    if ($postAddress && strpos($postAddress, $w) === 0) {
        $savedWilaya = $w;
        $savedStreet = ltrim(substr($postAddress, strlen($w)), ', ');
        break;
    }
}

$savedDateStr = '';
$savedHour    = '09';
$savedMin     = '00';
if ($postDate) {
    $parts = explode(' ', $postDate);
    $savedDateStr = $parts[0] ?? '';
    $t = explode(':', $parts[1] ?? '09:00');
    $savedHour = str_pad($t[0] ?? '09', 2, '0', STR_PAD_LEFT);
    $savedMin  = str_pad($t[1] ?? '00', 2, '0', STR_PAD_LEFT);
}
?>

<style>
/* ─── Shared popup base ───────────────────────────────────── */
.dtp-popup {
    display: none;
    position: fixed;
    z-index: 99999;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 1rem;
    box-shadow: 0 16px 48px rgba(0,0,0,.16);
    overflow: hidden;
    user-select: none;
    flex-direction: column;
}
.dtp-popup.open { display: flex; }

/* date popup */
#dp-popup { width: 310px; max-width: calc(100vw - 24px); }
/* time popup */
#tp-popup { width: 148px; max-width: calc(100vw - 24px); }

/* ─── Date popup header ───────────────────────────────────── */
.dp-header {
    background: #4f46e5;
    padding: 8px 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 4px;
    flex-shrink: 0;
}
.dp-nav-btn {
    background: rgba(255,255,255,.18);
    border: none; border-radius: 6px;
    color: #fff; cursor: pointer;
    width: 26px; height: 26px;
    display: flex; align-items: center; justify-content: center;
    transition: background .15s; flex-shrink: 0;
}
.dp-nav-btn:hover { background: rgba(255,255,255,.32); }
.dp-title {
    color: #fff; font-weight: 700; font-size: 0.8125rem;
    cursor: pointer; padding: 2px 6px; border-radius: 6px;
    transition: background .15s; white-space: nowrap; flex: 1; text-align: center;
}
.dp-title:hover { background: rgba(255,255,255,.2); }

/* ─── Date popup calendar body ───────────────────────────── */
.dp-body { padding: 10px 10px 12px; flex-shrink: 0; }
.dp-dow-row {
    display: grid; grid-template-columns: repeat(7,1fr);
    margin-bottom: 3px;
}
.dp-dow {
    text-align: center; font-size: 0.625rem; font-weight: 700;
    color: #6366f1; padding: 3px 0;
}
.dp-days-grid {
    display: grid; grid-template-columns: repeat(7,1fr); gap: 1px;
}
.dp-day {
    text-align: center; padding: 6px 1px; border-radius: 6px;
    font-size: 0.775rem; font-weight: 500; cursor: pointer;
    color: #374151; border: none; background: transparent;
    line-height: 1; transition: background .1s, color .1s;
    width: 100%;
}
.dp-day:hover:not(.disabled):not(.other-month) { background: #eef2ff; color: #4f46e5; }
.dp-day.today   { border: 2px solid #a5b4fc; color: #4f46e5; font-weight: 700; }
.dp-day.selected { background: #4f46e5 !important; color: #fff !important; font-weight: 700; }
.dp-day.disabled { color: #d1d5db; cursor: default; }
.dp-day.other-month { color: #e5e7eb; cursor: default; pointer-events: none; }

/* ─── Month grid ──────────────────────────────────────────── */
.dp-months-grid {
    display: grid; grid-template-columns: repeat(3,1fr);
    gap: 5px; padding: 8px;
}
.dp-month-btn {
    background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px;
    font-size: 0.775rem; font-weight: 600; color: #374151;
    padding: 7px 2px; cursor: pointer; text-align: center;
    transition: background .12s, border-color .12s, color .12s;
}
.dp-month-btn:hover  { background: #eef2ff; border-color: #a5b4fc; color: #4f46e5; }
.dp-month-btn.active { background: #4f46e5; border-color: #4f46e5; color: #fff; }

/* ─── Date popup footer ───────────────────────────────────── */
.dp-footer {
    border-top: 1px solid #f3f4f6;
    padding: 7px 10px;
    display: flex; justify-content: space-between; align-items: center; gap: 8px;
    flex-shrink: 0;
}
.dp-footer-text { font-size: 0.7rem; color: #4f46e5; font-weight: 600; flex: 1; }
.dp-footer-text.empty { color: #9ca3af; font-weight: 400; }
.dp-confirm {
    background: #4f46e5; color: #fff; border: none;
    border-radius: 7px; font-size: 0.7rem; font-weight: 700;
    padding: 5px 12px; cursor: pointer; transition: background .15s; flex-shrink: 0;
}
.dp-confirm:hover { background: #4338ca; }

/* ─── Time popup ──────────────────────────────────────────── */
.tp-header {
    background: #4f46e5;
    padding: 8px 10px;
    text-align: center;
    color: #fff; font-size: 0.75rem; font-weight: 700;
    letter-spacing: .04em; text-transform: uppercase;
    flex-shrink: 0;
}
.tp-cols {
    display: flex;
    height: 220px;    /* fixed height — both columns scroll within this */
}
.tp-col {
    flex: 1;
    overflow-y: scroll;
    scroll-snap-type: y mandatory;
    scrollbar-width: thin;
    scrollbar-color: #c7d2fe #f5f5ff;
}
.tp-col::-webkit-scrollbar { width: 4px; }
.tp-col::-webkit-scrollbar-track { background: #f5f5ff; }
.tp-col::-webkit-scrollbar-thumb { background: #c7d2fe; border-radius: 4px; }
.tp-col::-webkit-scrollbar-thumb:hover { background: #818cf8; }
.tp-col + .tp-col { border-left: 1px solid #f0f0f0; }
.tp-col-labels {
    display: flex;
    border-bottom: 1px solid #f0f0f0;
    flex-shrink: 0;
}
.tp-col-label {
    flex: 1;
    text-align: center; font-size: 0.65rem; font-weight: 700; color: #9ca3af;
    padding: 5px 0 4px;
    background: #fafafa;
}
.tp-col-label + .tp-col-label { border-left: 1px solid #f0f0f0; }
.tp-item {
    scroll-snap-align: start;
    text-align: center; padding: 8px 2px;
    font-size: 0.8125rem; font-weight: 500; color: #6b7280;
    cursor: pointer; border-radius: 6px; margin: 1px 3px;
    transition: background .1s, color .1s;
}
.tp-item:hover    { background: #eef2ff; color: #4f46e5; }
.tp-item.selected { background: #4f46e5; color: #fff; font-weight: 700; }
.tp-footer {
    border-top: 1px solid #f0f0f0;
    padding: 7px 10px;
    display: flex; justify-content: space-between; align-items: center;
    flex-shrink: 0;
}
.tp-footer-text { font-size: 0.7rem; color: #4f46e5; font-weight: 700; }
.tp-footer-text.empty { color: #9ca3af; font-weight: 400; }
.tp-confirm {
    background: #4f46e5; color: #fff; border: none;
    border-radius: 7px; font-size: 0.7rem; font-weight: 700;
    padding: 5px 10px; cursor: pointer; transition: background .15s;
}
.tp-confirm:hover { background: #4338ca; }

/* ─── Trigger buttons ─────────────────────────────────────── */
.dtp-trigger-btn {
    flex: 1;
    display: flex; align-items: center; gap: 8px;
    padding: 10px 12px;
    border: 1.5px solid #e5e7eb; border-radius: 12px;
    background: #fff; cursor: pointer; font-size: 0.875rem; text-align: left;
    transition: border-color .15s;
    min-width: 0;
}
.dtp-trigger-btn:hover    { border-color: #a5b4fc; }
.dtp-trigger-btn.filled   { border-color: #6366f1; }
.dtp-trigger-btn.error    { border-color: #f87171; box-shadow: 0 0 0 3px rgba(248,113,113,.12); }
.dtp-trigger-btn.open     { border-color: #6366f1; background: #fafafe; }
.dtp-btn-icon   { color: #6366f1; flex-shrink: 0; }
.dtp-btn-text   { flex: 1; color: #9ca3af; font-size: 0.8125rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.dtp-btn-text.filled { color: #111827; font-weight: 600; }
.dtp-btn-chevron { color: #9ca3af; flex-shrink: 0; transition: transform .2s; }
.dtp-trigger-btn.open .dtp-btn-chevron { transform: rotate(180deg); }
</style>

<div class="bg-gray-50 min-h-screen py-10">
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Back -->
    <a href="<?= APP_URL ?>/profile/<?= e($craftsman['username']) ?>"
       class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition mb-7 group">
        <svg class="mr-1.5 h-4 w-4 group-hover:-translate-x-0.5 transition-transform duration-150"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Profile
    </a>

    <h1 class="text-3xl font-extrabold text-gray-900 mb-1 tracking-tight">Request a Booking</h1>
    <p class="text-sm text-gray-500 mb-8">Fill in the details — the craftsman will review and respond to your request.</p>

    <?php if (!empty($error)): ?>
    <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
        <svg class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span><?= e($error) ?></span>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        <!-- ══ LEFT: FORM (2 cols) ══════════════════════════════════ -->
        <div class="lg:col-span-2 space-y-5">
        <form action="<?= APP_URL ?>/bookings/create" method="POST" id="booking-form">
            <input type="hidden" name="csrf_token"     value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="craftsman_id"   value="<?= e($craftsman['id']) ?>">
            <input type="hidden" name="scheduled_date" id="scheduled_date_input" value="<?= e($postDate) ?>">
            <input type="hidden" name="address"        id="address_combined"     value="<?= e($postAddress) ?>">

            <!-- 1 · Description -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-3">
                    <div class="h-7 w-7 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-xs font-bold">1</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">Describe the Job</p>
                        <p class="text-xs text-gray-400">Be specific — the more detail, the better the craftsman can prepare.</p>
                    </div>
                </div>
                <div class="px-6 py-5">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Job Description <span class="text-red-400">*</span>
                    </label>
                    <textarea id="description" name="description" rows="5" required maxlength="1000"
                        placeholder="e.g. I need to fix a leaking pipe under the kitchen sink. The water has been dripping for 2 days..."
                        class="block w-full px-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                        oninput="document.getElementById('desc-count').textContent=this.value.length"
                    ><?= e($postDesc) ?></textarea>
                    <p class="mt-1.5 text-xs text-gray-400 text-right">
                        <span id="desc-count"><?= strlen($postDesc) ?></span>/1000
                    </p>
                </div>
            </div>

            <!-- 2 · Location -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-3">
                    <div class="h-7 w-7 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-xs font-bold">2</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">Job Location</p>
                        <p class="text-xs text-gray-400">Where should the craftsman come to?</p>
                    </div>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label for="wilaya_select" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Wilaya <span class="text-red-400">*</span>
                        </label>
                        <select id="wilaya_select" required
                            class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white text-gray-900
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="">— Select Wilaya —</option>
                            <?php foreach ($wilayas as $w): ?>
                            <option value="<?= e($w) ?>" <?= $savedWilaya === $w ? 'selected' : '' ?>><?= e($w) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="street_address" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Street Address <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <input id="street_address" type="text" required
                                placeholder="e.g. 12 Rue des Frères, Cité des Pins"
                                value="<?= e($savedStreet) ?>"
                                class="block w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                    </div>
                </div>
            </div>

                        <!-- 3 · Schedule -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-3">
                    <div class="h-7 w-7 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-xs font-bold">3</span>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">Preferred Schedule</p>
                        <p class="text-xs text-gray-400">Pick a date and time — the craftsman may suggest an alternative.</p>
                    </div>
                </div>
                <div class="px-6 py-5">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Date &amp; Time <span class="text-red-400">*</span>
                    </label>

                    <!-- Two buttons side by side -->
                    <div class="flex gap-3">

                        <!-- Date trigger -->
                        <button type="button" id="dp-trigger" class="dtp-trigger-btn" aria-expanded="false">
                            <svg class="dtp-btn-icon h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span id="dp-trigger-text" class="dtp-btn-text">Select date</span>
                            <svg class="dtp-btn-chevron h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Time trigger -->
                        <button type="button" id="tp-trigger" class="dtp-trigger-btn" style="max-width:140px;flex:0 0 140px" aria-expanded="false">
                            <svg class="dtp-btn-icon h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span id="tp-trigger-text" class="dtp-btn-text">Time</span>
                            <svg class="dtp-btn-chevron h-3.5 w-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                    </div>

                    <!-- DATE POPUP -->
                    <div id="dp-popup" class="dtp-popup" role="dialog" aria-label="Date picker">
                        <div class="dp-header">
                            <button type="button" class="dp-nav-btn" id="dp-prev-yr">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 17l-5-5 5-5M18 17l-5-5 5-5"/></svg>
                            </button>
                            <button type="button" class="dp-nav-btn" id="dp-prev-mo">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <span class="dp-title" id="dp-title" title="Click for month view"></span>
                            <button type="button" class="dp-nav-btn" id="dp-next-mo">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 5l7 7-7 7"/></svg>
                            </button>
                            <button type="button" class="dp-nav-btn" id="dp-next-yr">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M13 7l5 5-5 5M6 7l5 5-5 5"/></svg>
                            </button>
                        </div>
                        <div class="dp-body" id="dp-body"></div>
                        <div class="dp-footer">
                            <span class="dp-footer-text empty" id="dp-footer-text">No date selected</span>
                            <button type="button" class="dp-confirm" id="dp-confirm">Confirm</button>
                        </div>
                    </div>

                    <!-- TIME POPUP -->
                    <div id="tp-popup" class="dtp-popup" role="dialog" aria-label="Time picker">
                        <div class="tp-header">Select Time</div>
                        <!-- Labels row — outside scroll container so scroll starts at 00 -->
                        <div class="tp-col-labels">
                            <div class="tp-col-label">hr</div>
                            <div class="tp-col-label">min</div>
                        </div>
                        <div class="tp-cols">
                            <div class="tp-col" id="tp-hours"></div>
                            <div class="tp-col" id="tp-mins"></div>
                        </div>
                        <div class="tp-footer">
                            <span class="tp-footer-text empty" id="tp-footer-text">——:——</span>
                            <button type="button" class="tp-confirm" id="tp-confirm">OK</button>
                        </div>
                    </div>

                    <p class="mt-3 flex items-center gap-1.5 text-xs text-gray-400">
                        <svg class="h-3.5 w-3.5 text-amber-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        This is a preferred schedule — the craftsman may suggest an alternative.
                    </p>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-between gap-4 pt-1 pb-8">
                <a href="<?= APP_URL ?>/profile/<?= e($craftsman['username']) ?>"
                   class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit"
                    class="flex-1 sm:flex-none inline-flex justify-center items-center gap-2 px-8 py-2.5
                           bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold
                           rounded-xl shadow-sm transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Send Booking Request
                </button>
            </div>

        </form>
        </div>

        <!-- ══ RIGHT: SIDEBAR (1 col) ══════════════════════════════ -->
        <div class="space-y-4">
            <!-- Craftsman card -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="h-1 w-full bg-indigo-600"></div>
                <div class="p-5">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">You're booking</p>
                    <div class="flex items-center gap-3 mb-4">
                        <img class="h-14 w-14 rounded-full object-cover ring-2 ring-indigo-100 flex-shrink-0"
                             src="<?= get_profile_picture_url($craftsman['profile_picture'] ?? 'default.png', $craftsman['first_name'], $craftsman['last_name']) ?>"
                             alt="<?= e($craftsman['first_name']) ?>">
                        <div class="min-w-0">
                            <!-- Name + verified badge inline -->
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <h3 class="font-bold text-gray-900 text-base leading-tight">
                                    <?= e($craftsman['first_name'] . ' ' . $craftsman['last_name']) ?>
                                </h3>
                                <?php if ($isVerified): ?>
                                <svg style="width:1.05rem;height:1.05rem" class="text-blue-500 flex-shrink-0"
                                     viewBox="0 0 20 20" fill="currentColor" title="Verified Craftsman">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <?php endif; ?>
                            </div>
                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-md text-xs font-semibold <?= $badgeClass ?>">
                                <?= e($category) ?>
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="bg-gray-50 rounded-xl p-3 text-center border border-gray-100">
                            <div class="flex items-center justify-center gap-0.5 mb-1">
                                <?php for ($i=1;$i<=5;$i++): ?>
                                <svg class="h-3 w-3 <?= $i<=round($avgRating)?'text-yellow-400':'text-gray-200' ?>" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <?php endfor; ?>
                            </div>
                            <p class="text-sm font-bold text-gray-900"><?= $avgRating>0?number_format($avgRating,1):'—' ?></p>
                            <p class="text-xs text-gray-400"><?= $totalReviews ?> review<?= $totalReviews!==1?'s':'' ?></p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center border border-gray-100">
                            <p class="text-xs text-gray-400 mb-1">Hourly Rate</p>
                            <p class="text-sm font-bold text-indigo-600"><?= number_format($hourlyRate,0) ?></p>
                            <p class="text-xs text-gray-400">DZD / hr</p>
                        </div>
                    </div>
                    <?php if (!empty($craftsman['wilaya'])): ?>
                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-4">
                        <svg class="h-3.5 w-3.5 text-gray-400 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <?= e(preg_replace('/^\d{2}\s-\s/','',$craftsman['wilaya'])) ?>
                    </div>
                    <?php endif; ?>
                    <a href="<?= APP_URL ?>/profile/<?= e($craftsman['username']) ?>"
                       class="flex items-center justify-center w-full py-2 text-xs font-semibold text-indigo-600
                              bg-indigo-50 hover:bg-indigo-100 rounded-lg transition border border-indigo-100">
                        View Full Profile →
                    </a>
                </div>
            </div>

            <!-- How it works -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">How It Works</p>
                <div class="space-y-4">
                    <?php foreach([
                        ['1','You send the request',  'Describe the job, location, and preferred time.'],
                        ['2','Craftsman reviews it',  'They can accept, decline, or send a counter-offer.'],
                        ['3','Job gets done',         'Track progress and confirm on completion.'],
                    ] as [$n,$t,$d]): ?>
                    <div class="flex items-start gap-3">
                        <div class="h-6 w-6 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <span class="text-indigo-700 text-xs font-bold"><?= $n ?></span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-800"><?= $t ?></p>
                            <p class="text-xs text-gray-400 mt-0.5"><?= $d ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</div>
</div>

<script>
(function () {

    var MONTHS_LONG  = ['January','February','March','April','May','June',
                        'July','August','September','October','November','December'];
    var MONTHS_SHORT = ['Jan','Feb','Mar','Apr','May','Jun',
                        'Jul','Aug','Sep','Oct','Nov','Dec'];
    var DAYS_SHORT   = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    var DAYS_LONG    = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

    function pad(n){ return n < 10 ? '0'+n : ''+n; }
    function localDate(y,m,d){ var dt=new Date(y,m,d); dt.setHours(0,0,0,0); return dt; }

    var today = new Date(); today.setHours(0,0,0,0);

    /* ── State ───────────────────────────────────────────────── */
    var ds = {               // date state
        viewY: today.getFullYear(), viewM: today.getMonth(),
        selY: null, selM: null, selD: null,
        mode: 'days', open: false, confirmed: false
    };
    var ts = {               // time state
        selHour: 9, selMin: 0,
        open: false, confirmed: false
    };

    /* Pre-fill from PHP POST re-fill */
    var phpDate = '<?= addslashes($savedDateStr) ?>';
    var phpHour = <?= (int)$savedHour ?>;
    var phpMin  = <?= (int)$savedMin  ?>;
    if (phpDate) {
        var pp = phpDate.split('-');
        ds.selY = parseInt(pp[0]); ds.selM = parseInt(pp[1])-1; ds.selD = parseInt(pp[2]);
        ds.viewY = ds.selY; ds.viewM = ds.selM; ds.confirmed = true;
        ts.selHour = phpHour; ts.selMin = phpMin; ts.confirmed = true;
        updateDateTrigger(); updateTimeTrigger(); syncHidden();
    }

    /* ── Generic popup positioning ───────────────────────────── */
    function positionPopup(popup, trigger) {
        var rect = trigger.getBoundingClientRect();
        var popW = popup.offsetWidth  || 300;
        var popH = popup.offsetHeight || 300;
        var top  = rect.bottom + 6;
        var left = rect.left;
        if (top + popH > window.innerHeight - 12) top = rect.top - popH - 6;
        if (top < 8) top = 8;
        if (left + popW > window.innerWidth - 12) left = window.innerWidth - popW - 12;
        if (left < 12) left = 12;
        popup.style.top  = top  + 'px';
        popup.style.left = left + 'px';
    }

    /* ── Generic open/close factory ──────────────────────────── */
    function makeToggle(stateObj, popup, trigger, openFn) {
        document.body.appendChild(popup);
        trigger.addEventListener('click', function(e){
            e.stopPropagation();
            if (stateObj.open) { closePopup(stateObj, popup, trigger); }
            else               { openFn(); }
        });
        popup.addEventListener('click', function(e){ e.stopPropagation(); });
    }

    function openPopup(stateObj, popup, trigger, renderFn) {
        // Close the other popup first
        if (stateObj === ds && ts.open) closePopup(ts, tpPopup, tpTrigger);
        if (stateObj === ts && ds.open) closePopup(ds, dpPopup, dpTrigger);
        stateObj.open = true;
        popup.classList.add('open');
        trigger.classList.add('open');
        trigger.setAttribute('aria-expanded','true');
        renderFn();
        requestAnimationFrame(function(){ positionPopup(popup, trigger); });
    }

    function closePopup(stateObj, popup, trigger) {
        stateObj.open = false;
        popup.classList.remove('open');
        trigger.classList.remove('open');
        trigger.setAttribute('aria-expanded','false');
    }

    document.addEventListener('click', function(){
        if (ds.open) closePopup(ds, dpPopup, dpTrigger);
        if (ts.open) closePopup(ts, tpPopup, tpTrigger);
    });
    document.addEventListener('keydown', function(e){
        if (e.key==='Escape'){
            if (ds.open) closePopup(ds, dpPopup, dpTrigger);
            if (ts.open) closePopup(ts, tpPopup, tpTrigger);
        }
    });
    window.addEventListener('scroll', function(){
        if (ds.open) positionPopup(dpPopup, dpTrigger);
        if (ts.open) positionPopup(tpPopup, tpTrigger);
    }, true);
    window.addEventListener('resize', function(){
        if (ds.open) positionPopup(dpPopup, dpTrigger);
        if (ts.open) positionPopup(tpPopup, tpTrigger);
    });

    /* ── DATE PICKER ─────────────────────────────────────────── */
    var dpPopup   = document.getElementById('dp-popup');
    var dpTrigger = document.getElementById('dp-trigger');

    function renderDate(){
        document.getElementById('dp-title').textContent =
            MONTHS_LONG[ds.viewM] + ' ' + ds.viewY;
        if (ds.mode === 'months') renderMonthGrid();
        else                      renderDayGrid();
        updateDateFooter();
    }

    function renderDayGrid(){
        var body = document.getElementById('dp-body');
        body.innerHTML = '';

        /* DOW row */
        var dowRow = document.createElement('div');
        dowRow.className = 'dp-dow-row';
        DAYS_SHORT.forEach(function(d){
            var el = document.createElement('div');
            el.className = 'dp-dow'; el.textContent = d;
            dowRow.appendChild(el);
        });
        body.appendChild(dowRow);

        /* grid */
        var grid = document.createElement('div');
        grid.className = 'dp-days-grid';

        var firstDow   = new Date(ds.viewY, ds.viewM, 1).getDay();
        var daysInM    = new Date(ds.viewY, ds.viewM+1, 0).getDate();
        var daysInPrev = new Date(ds.viewY, ds.viewM, 0).getDate();

        for (var i = firstDow-1; i >= 0; i--) {
            grid.appendChild(makeDayBtn(daysInPrev-i, true, false, false, false));
        }
        for (var d = 1; d <= daysInM; d++) {
            var past  = localDate(ds.viewY, ds.viewM, d) < today;
            var isT   = today.getFullYear()===ds.viewY && today.getMonth()===ds.viewM && today.getDate()===d;
            var isSel = ds.selY===ds.viewY && ds.selM===ds.viewM && ds.selD===d;
            var btn   = makeDayBtn(d, false, past, isT, isSel);
            if (!past) {
                (function(day){
                    btn.addEventListener('click', function(){
                        ds.selY=ds.viewY; ds.selM=ds.viewM; ds.selD=day;
                        renderDayGrid(); updateDateFooter();
                    });
                })(d);
            }
            grid.appendChild(btn);
        }
        var trailing = (firstDow + daysInM) % 7;
        if (trailing > 0) { for (var t=1; t <= 7-trailing; t++) grid.appendChild(makeDayBtn(t,true,false,false,false)); }
        body.appendChild(grid);
    }

    function makeDayBtn(num, other, dis, isT, isSel){
        var b = document.createElement('button');
        b.type='button'; b.textContent=num; b.className='dp-day';
        if (other) b.classList.add('other-month');
        if (dis)   b.classList.add('disabled');
        if (isT)   b.classList.add('today');
        if (isSel) b.classList.add('selected');
        return b;
    }

    function renderMonthGrid(){
        var body = document.getElementById('dp-body');
        body.innerHTML = '';
        var grid = document.createElement('div');
        grid.className = 'dp-months-grid';
        MONTHS_SHORT.forEach(function(m, idx){
            var b = document.createElement('button');
            b.type='button'; b.textContent=m; b.className='dp-month-btn';
            if (idx===ds.viewM) b.classList.add('active');
            b.addEventListener('click', function(){ ds.viewM=idx; ds.mode='days'; renderDate(); });
            grid.appendChild(b);
        });
        body.appendChild(grid);
    }

    function updateDateFooter(){
        var el = document.getElementById('dp-footer-text');
        if (ds.selD !== null) {
            var d = localDate(ds.selY, ds.selM, ds.selD);
            el.textContent = DAYS_SHORT[d.getDay()] + ' ' + MONTHS_SHORT[ds.selM] + ' ' + ds.selD + ' ' + ds.selY;
            el.classList.remove('empty');
        } else {
            el.textContent = 'No date selected';
            el.classList.add('empty');
        }
    }

    function updateDateTrigger(){
        var textEl = document.getElementById('dp-trigger-text');
        var trigEl = document.getElementById('dp-trigger');
        if (ds.confirmed && ds.selD !== null) {
            var d = localDate(ds.selY, ds.selM, ds.selD);
            textEl.textContent = DAYS_SHORT[d.getDay()] + ' ' + MONTHS_SHORT[ds.selM] + ' ' + ds.selD + ' ' + ds.selY;
            textEl.className = 'dtp-btn-text filled';
            trigEl.classList.add('filled'); trigEl.classList.remove('error');
        } else {
            textEl.textContent = 'Select date';
            textEl.className = 'dtp-btn-text';
            trigEl.classList.remove('filled');
        }
    }

    /* date nav */
    document.getElementById('dp-prev-mo').addEventListener('click', function(){ ds.viewM--; if(ds.viewM<0){ds.viewM=11;ds.viewY--;} renderDate(); });
    document.getElementById('dp-next-mo').addEventListener('click', function(){ ds.viewM++; if(ds.viewM>11){ds.viewM=0;ds.viewY++;} renderDate(); });
    document.getElementById('dp-prev-yr').addEventListener('click', function(){ ds.viewY--; renderDate(); });
    document.getElementById('dp-next-yr').addEventListener('click', function(){ ds.viewY++; renderDate(); });
    document.getElementById('dp-title').addEventListener('click', function(){ ds.mode=ds.mode==='months'?'days':'months'; renderDate(); });

    /* date confirm */
    document.getElementById('dp-confirm').addEventListener('click', function(){
        if (ds.selD === null) return;
        ds.confirmed = true;
        // Auto-confirm time with default 09:00 if user hasn't picked one yet
        // This makes the time button show "9:00 AM" instead of blank "Time"
        if (!ts.confirmed) {
            ts.confirmed = true;
            updateTimeTrigger();
            updateTimeFooter();
        }
        updateDateTrigger(); syncHidden();
        closePopup(ds, dpPopup, dpTrigger);
    });

    makeToggle(ds, dpPopup, dpTrigger, function(){
        openPopup(ds, dpPopup, dpTrigger, renderDate);
    });

    /* ── TIME PICKER ─────────────────────────────────────────── */
    var tpPopup   = document.getElementById('tp-popup');
    var tpTrigger = document.getElementById('tp-trigger');

    function renderTime(){
        var hCol = document.getElementById('tp-hours');
        var mCol = document.getElementById('tp-mins');
        hCol.innerHTML = '';
        mCol.innerHTML = '';

        for (var h=0; h<=23; h++) {
            (function(hour){
                var el = document.createElement('div');
                el.className = 'tp-item' + (hour===ts.selHour ? ' selected' : '');
                el.textContent = pad(hour);
                el.addEventListener('click', function(){
                    ts.selHour=hour; renderTime(); updateTimeFooter(); syncHidden();
                });
                hCol.appendChild(el);
            })(h);
        }
        [0,5,10,15,20,25,30,35,40,45,50,55].forEach(function(min){
            var el = document.createElement('div');
            el.className = 'tp-item' + (min===ts.selMin ? ' selected' : '');
            el.textContent = pad(min);
            el.addEventListener('click', function(){
                ts.selMin=min; renderTime(); updateTimeFooter(); syncHidden();
            });
            mCol.appendChild(el);
        });

        /* Scroll selected item to center of column.
           Labels are now outside the scroll container so offsetTop starts at 0. */
        requestAnimationFrame(function(){
            var ITEM_H = 34; // padding 8*2 + font ~18 = ~34px per item
            hCol.scrollTop = ts.selHour * ITEM_H - (hCol.clientHeight / 2) + (ITEM_H / 2);
            if (hCol.scrollTop < 0) hCol.scrollTop = 0;
            var minIdx = [0,5,10,15,20,25,30,35,40,45,50,55].indexOf(ts.selMin);
            mCol.scrollTop = minIdx * ITEM_H - (mCol.clientHeight / 2) + (ITEM_H / 2);
            if (mCol.scrollTop < 0) mCol.scrollTop = 0;
        });
    }

    function updateTimeFooter(){
        var el = document.getElementById('tp-footer-text');
        el.textContent = pad(ts.selHour) + ':' + pad(ts.selMin);
        el.classList.remove('empty');
    }

    function updateTimeTrigger(){
        var textEl = document.getElementById('tp-trigger-text');
        var trigEl = document.getElementById('tp-trigger');
        if (ts.confirmed) {
            textEl.textContent = pad(ts.selHour) + ':' + pad(ts.selMin);
            textEl.className = 'dtp-btn-text filled';
            trigEl.classList.add('filled'); trigEl.classList.remove('error');
        } else {
            textEl.textContent = 'Time';
            textEl.className = 'dtp-btn-text';
            trigEl.classList.remove('filled');
        }
    }

    /* time confirm */
    document.getElementById('tp-confirm').addEventListener('click', function(){
        ts.confirmed = true;
        updateTimeTrigger(); syncHidden();
        closePopup(ts, tpPopup, tpTrigger);
    });

    makeToggle(ts, tpPopup, tpTrigger, function(){
        openPopup(ts, tpPopup, tpTrigger, renderTime);
    });

    /* ── Sync hidden datetime field ──────────────────────────── */
    function syncHidden(){
        if (ds.selD === null) return;
        var dateStr = ds.selY + '-' + pad(ds.selM+1) + '-' + pad(ds.selD);
        document.getElementById('scheduled_date_input').value =
            dateStr + ' ' + pad(ts.selHour) + ':' + pad(ts.selMin) + ':00';
    }

    /* ── Address combine ─────────────────────────────────────── */
    function combineAddress(){
        var wilaya = document.getElementById('wilaya_select').value;
        var street = document.getElementById('street_address').value.trim();
        document.getElementById('address_combined').value =
            wilaya && street ? wilaya+', '+street : wilaya || street;
    }
    document.getElementById('wilaya_select').addEventListener('change', combineAddress);
    document.getElementById('street_address').addEventListener('input',  combineAddress);
    combineAddress();

    /* ── Form validation ─────────────────────────────────────── */
    document.getElementById('booking-form').addEventListener('submit', function(e){
        combineAddress(); syncHidden();
        var ok = true;
        if (!document.getElementById('scheduled_date_input').value) {
            ok = false;
            document.getElementById('dp-trigger').classList.add('error');
            document.getElementById('dp-trigger').scrollIntoView({behavior:'smooth',block:'center'});
        }
        if (!ok) e.preventDefault();
    });

    /* ── Init time footer ────────────────────────────────────── */
    updateTimeFooter();

})();
</script>