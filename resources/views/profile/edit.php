<!-- Edit Profile Page -->
<?php
$existingImages = [];
if (!empty($craftsmanDetails['portfolio_images'])) {
    $existingImages = json_decode($craftsmanDetails['portfolio_images'], true) ?: [];
}
$isCraftsman  = $user['role'] === 'craftsman';

$wilayas = [
    "01 - Adrar","02 - Chlef","03 - Laghouat","04 - Oum El Bouaghi","05 - Batna","06 - Béjaïa","07 - Biskra","08 - Béchar","09 - Blida","10 - Bouira",
    "11 - Tamanrasset","12 - Tébessa","13 - Tlemcen","14 - Tiaret","15 - Tizi Ouzou","16 - Alger","17 - Djelfa","18 - Jijel","19 - Sétif","20 - Saïda",
    "21 - Skikda","22 - Sidi Bel Abbès","23 - Annaba","24 - Guelma","25 - Constantine","26 - Médéa","27 - Mostaganem","28 - M'Sila","29 - Mascara","30 - Ouargla",
    "31 - Oran","32 - El Bayadh","33 - Illizi","34 - Bordj Bou Arréridj","35 - Boumerdès","36 - El Tarf","37 - Tindouf","38 - Tissemsilt","39 - El Oued","40 - Khenchela",
    "41 - Souk Ahras","42 - Tipaza","43 - Mila","44 - Aïn Defla","45 - Naâma","46 - Aïn Témouchent","47 - Ghardaïa","48 - Relizane","49 - Timimoun","50 - Bordj Badji Mokhtar",
    "51 - Ouled Djellal","52 - Béni Abbès","53 - In Salah","54 - In Guezzam","55 - Touggourt","56 - Djanet","57 - El M'Ghair","58 - El Meniaa"
];

$categories = ["Plumbing","Electrical","Carpentry","Painting","Roofing","HVAC","Landscaping","Tiling","General Handyman"];

// Username lock logic
$canEditUsername = true;
$daysRemaining   = 0;
if (!empty($user['username_updated_at'])) {
    $lastUpdated = strtotime($user['username_updated_at']);
    $daysPassed  = floor((time() - $lastUpdated) / (24 * 60 * 60));
    if ($daysPassed < 14) {
        $canEditUsername = false;
        $daysRemaining   = 14 - $daysPassed;
    }
}
?>

<div class="bg-gray-50 min-h-screen pb-16">

    <!-- Cover banner -->
    <div class="h-36 w-full bg-gradient-to-r from-indigo-700 to-indigo-500 relative overflow-hidden">
        <div class="absolute inset-0 opacity-20"
             style="background-image:radial-gradient(#fff 1px,transparent 1px);background-size:20px 20px;"></div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-10">

        <!-- Back link -->
        <a href="<?= APP_URL ?>/profile/<?= e($user['username']) ?>"
           class="inline-flex items-center text-sm font-medium text-white hover:text-indigo-100 transition mb-5 drop-shadow group">
            <svg class="mr-1.5 h-4 w-4 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Profile
        </a>

        <form action="<?= APP_URL ?>/profile/edit" method="POST" enctype="multipart/form-data" id="edit-form">
        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

        <!-- ════════════════════════════════════════════════════════════
             TWO-COLUMN LAYOUT
        ════════════════════════════════════════════════════════════ -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <!-- ══ LEFT COLUMN (1/3) ══════════════════════════════════ -->
            <div class="space-y-5">

                <!-- Profile Picture card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="h-1 w-full bg-indigo-600"></div>
                    <div class="p-5">
                        <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Profile Picture</h2>

                        <!-- Avatar preview -->
                        <div class="flex flex-col items-center mb-4">
                            <div class="relative w-24 h-24 rounded-full overflow-hidden bg-gray-100 ring-4 ring-indigo-50 border border-gray-200 shadow-sm mb-3">
                                <img id="profile-preview"
                                     src="<?= get_profile_picture_url($user['profile_picture'] ?? 'default.png', $user['first_name'], $user['last_name']) ?>"
                                     alt="Profile" class="object-cover w-full h-full">
                            </div>
                            <p class="text-sm font-bold text-gray-900"><?= e($user['first_name'] . ' ' . $user['last_name']) ?></p>
                            <p class="text-xs text-gray-400 mt-0.5 capitalize"><?= e($user['role']) ?></p>
                        </div>

                        <!-- Upload controls -->
                        <input type="file" name="profile_picture" id="profile-upload" class="sr-only"
                               accept="image/png,image/jpeg,image/gif,image/webp" onchange="previewImage(event)">
                        <button type="button" onclick="document.getElementById('profile-upload').click()"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Change Photo
                        </button>
                        <p id="file-name" class="mt-1.5 text-xs text-center text-gray-400">JPG, PNG, GIF or WebP · max 5MB</p>

                        <?php if (!empty($user['profile_picture']) && $user['profile_picture'] !== 'default.png'): ?>
                        <input type="checkbox" name="remove_picture" id="remove_picture" value="1" class="hidden">
                        <button type="button" id="remove-btn" onclick="removePhoto()"
                            class="mt-2 w-full text-xs font-medium text-red-500 hover:text-red-700 transition text-center">
                            Remove Photo
                        </button>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($isCraftsman): ?>
                <!-- Portfolio card (craftsman only) -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="h-1 w-full bg-indigo-600"></div>
                    <div class="p-5">
                        <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Portfolio</h2>
                        <p class="text-xs text-gray-400 mb-4">Up to 10 images · JPG PNG GIF WebP · 5MB each</p>

                        <!-- Existing images -->
                        <?php if (!empty($existingImages)): ?>
                        <div class="mb-4">
                            <p class="text-xs font-medium text-gray-500 mb-2">
                                Current (<span id="portfolio-count"><?= count($existingImages) ?></span>) — hover to remove
                            </p>
                            <div class="grid grid-cols-3 gap-2" id="existing-portfolio">
                                <?php foreach ($existingImages as $index => $img): ?>
                                <div class="relative group rounded-xl overflow-hidden border border-gray-200 aspect-square"
                                     id="portfolio-item-<?= $index ?>">
                                    <!-- Click image opens lightbox -->
                                    <img src="<?= APP_URL ?>/uploads/portfolio/<?= e($img) ?>"
                                         alt="Portfolio"
                                         class="w-full h-full object-cover cursor-zoom-in"
                                         onclick="openEditLightbox('<?= APP_URL ?>/uploads/portfolio/<?= e($img) ?>', 'existing')">
                                    <!-- X button top-right -->
                                    <button type="button" onclick="event.stopPropagation(); removePortfolioImage(<?= $index ?>, '<?= e($img) ?>')"
                                        class="absolute top-1.5 right-1.5 z-10 bg-red-500 hover:bg-red-600 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs font-bold shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                        ×
                                    </button>
                                    <input type="hidden" name="existing_images[]" value="<?= e($img) ?>" id="input-portfolio-<?= $index ?>">
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Upload new -->
                        <input type="file" name="portfolio_images[]" id="portfolio-upload" multiple
                               accept="image/png,image/jpeg,image/gif,image/webp" class="hidden">
                        <input type="file" id="portfolio-picker" multiple
                               accept="image/png,image/jpeg,image/gif,image/webp"
                               class="hidden" onchange="addNewPortfolioFiles(this)">
                        <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:border-indigo-300 transition cursor-pointer"
                             id="portfolio-dropzone"
                             onclick="document.getElementById('portfolio-picker').click()">
                            <svg class="mx-auto h-8 w-8 text-gray-300 mb-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-xs font-medium text-gray-500" id="dropzone-text">Click to add images</p>
                        </div>

                        <!-- New image previews -->
                        <div id="portfolio-new-previews" class="grid grid-cols-3 gap-2 mt-3 hidden"></div>
                    </div>
                </div>
                <?php endif; ?>

            </div><!-- end left col -->

            <!-- ══ RIGHT COLUMN (2/3) ═════════════════════════════════ -->
            <div class="lg:col-span-2 space-y-5">

                <!-- Personal Information -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Personal Information</h2>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <input type="text" name="first_name" id="first_name" required
                                   value="<?= e($user['first_name']) ?>"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <input type="text" name="last_name" id="last_name" required
                                   value="<?= e($user['last_name']) ?>"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>

                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <input type="tel" name="phone_number" id="phone_number"
                                   value="<?= e($user['phone_number'] ?? '') ?>"
                                   placeholder="e.g. 0555 123 456"
                                   class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>

                        <div>
                            <label for="wilaya" class="block text-sm font-medium text-gray-700 mb-1">Wilaya</label>
                            <select name="wilaya" id="wilaya"
                                    class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <option value="">— Select Wilaya —</option>
                                <?php foreach ($wilayas as $w): ?>
                                <option value="<?= e($w) ?>" <?= ($user['wilaya'] ?? '') === $w ? 'selected' : '' ?>><?= e($w) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>
                </div>

                <!-- Username -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Username</h2>
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                            Public Username
                            <span class="text-gray-400 font-normal ml-1 text-xs">— appears in your profile URL</span>
                        </label>
                        <?php if (!$canEditUsername): ?>
                        <input type="text" value="<?= e($user['username'] ?? '') ?>" disabled
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
                        <p class="mt-1.5 text-xs text-amber-600 flex items-center gap-1">
                            <svg class="h-3.5 w-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            You can change your username in <?= $daysRemaining ?> day<?= $daysRemaining !== 1 ? 's' : '' ?>.
                        </p>
                        <?php else: ?>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400 text-sm pointer-events-none">@</span>
                            <input type="text" name="username" id="username"
                                   value="<?= e($user['username'] ?? '') ?>"
                                   placeholder="your_username"
                                   class="block w-full pl-8 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        <div id="username-feedback" class="mt-1.5 text-xs font-medium flex items-center transition-all duration-200"></div>
                        <p class="mt-1 text-xs text-gray-400">Must start with a letter · min 3 chars · letters, numbers, _ and - only</p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($isCraftsman): ?>
                <!-- Professional Details (craftsman only) -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Professional Details</h2>
                    <div class="space-y-4">

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="service_category" class="block text-sm font-medium text-gray-700 mb-1">Service Category</label>
                                <select name="service_category" id="service_category"
                                        class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    <?php
                                    $selectedCat = $craftsmanDetails['service_category'] ?? 'General Handyman';
                                    foreach ($categories as $cat):
                                    ?>
                                    <option value="<?= e($cat) ?>" <?= $cat === $selectedCat ? 'selected' : '' ?>><?= e($cat) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="hourly_rate" class="block text-sm font-medium text-gray-700 mb-1">Hourly Rate</label>
                                <div class="relative">
                                    <input type="number" name="hourly_rate" id="hourly_rate"
                                           step="0.01" min="0"
                                           value="<?= e($craftsmanDetails['hourly_rate'] ?? '0.00') ?>"
                                           class="block w-full px-4 py-2.5 pr-14 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-400 text-sm font-medium">DZD</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">
                                Professional Bio
                                <span class="text-gray-400 font-normal ml-1 text-xs">(optional)</span>
                            </label>
                            <textarea id="bio" name="bio" rows="5"
                                      maxlength="500"
                                      placeholder="Describe your experience, skills, and what makes you stand out..."
                                      class="block w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                                      oninput="document.getElementById('bio-count').textContent=this.value.length"
                            ><?= e($craftsmanDetails['bio'] ?? '') ?></textarea>
                            <p class="mt-1 text-xs text-gray-400 text-right">
                                <span id="bio-count"><?= strlen($craftsmanDetails['bio'] ?? '') ?></span>/500
                            </p>
                        </div>

                    </div>
                </div>
                <?php endif; ?>


                <!-- Save / Cancel -->
                <div class="flex items-center justify-between gap-4 pb-4">
                    <a href="<?= APP_URL ?>/profile/<?= e($user['username']) ?>"
                       class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-8 py-2.5 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Changes
                    </button>
                </div>

            </div><!-- end right col -->
        </div><!-- end grid -->
        </form>
    </div>
</div>

<!-- ── Portfolio Lightbox ───────────────────────────────────── -->
<div id="edit-lightbox" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-90" onclick="closeEditLightbox()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <!-- Close button -->
        <button onclick="closeEditLightbox()"
            class="pointer-events-auto absolute top-4 right-4 z-10 text-white hover:text-gray-300 transition p-2">
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <img id="edit-lightbox-image" src="" alt="Portfolio"
             class="pointer-events-auto max-h-[88vh] max-w-[90vw] object-contain rounded-lg shadow-2xl">
    </div>
</div>

<script>
function openEditLightbox(src) {
    document.getElementById('edit-lightbox-image').src = src;
    document.getElementById('edit-lightbox').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeEditLightbox() {
    document.getElementById('edit-lightbox').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeEditLightbox();
});
</script>

<script>
// ── Profile Picture Preview ──────────────────────────────────────────
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('profile-preview');
        output.src = reader.result;
        output.style.opacity = '1';
    };
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
        var nameEl = document.getElementById('file-name');
        nameEl.textContent = event.target.files[0].name;
        nameEl.classList.add('text-indigo-600', 'font-medium');
        nameEl.classList.remove('text-gray-400');
        var removeBox = document.getElementById('remove_picture');
        if (removeBox) removeBox.checked = false;
    }
}

function removePhoto() {
    document.getElementById('remove_picture').checked = true;
    document.getElementById('profile-upload').value = '';
    document.getElementById('file-name').textContent = 'JPG, PNG, GIF or WebP · max 5MB';
    document.getElementById('profile-preview').style.opacity = '0.3';
    var removeBtn = document.getElementById('remove-btn');
    if (removeBtn) removeBtn.style.display = 'none';
}

// ── Portfolio Management ─────────────────────────────────────────────
function removePortfolioImage(index, filename) {
    var item  = document.getElementById('portfolio-item-' + index);
    var input = document.getElementById('input-portfolio-' + index);
    if (item) {
        item.style.opacity = '0';
        item.style.transform = 'scale(0.8)';
        item.style.transition = 'all 0.25s ease';
        setTimeout(function() { item.remove(); updatePortfolioCount(); }, 250);
    }
    if (input) input.remove();
}

function updatePortfolioCount() {
    var el = document.getElementById('portfolio-count');
    if (el) el.textContent = document.querySelectorAll('#existing-portfolio [id^="portfolio-item-"]').length;
}

var pendingFiles = [];

function addNewPortfolioFiles(picker) {
    Array.from(picker.files).forEach(function(f) { pendingFiles.push(f); });
    picker.value = '';
    syncPortfolioInput();
    renderNewPreviews();
}

function removeNewPortfolioFile(index) {
    pendingFiles.splice(index, 1);
    syncPortfolioInput();
    renderNewPreviews();
}

function syncPortfolioInput() {
    var dt = new DataTransfer();
    pendingFiles.forEach(function(f) { dt.items.add(f); });
    document.getElementById('portfolio-upload').files = dt.files;
    var textEl = document.getElementById('dropzone-text');
    textEl.textContent = pendingFiles.length > 0
        ? pendingFiles.length + ' image' + (pendingFiles.length > 1 ? 's' : '') + ' ready to upload'
        : 'Click to add images';
}

function renderNewPreviews() {
    var container = document.getElementById('portfolio-new-previews');
    container.innerHTML = '';
    if (pendingFiles.length === 0) { container.classList.add('hidden'); return; }
    container.classList.remove('hidden');
    pendingFiles.forEach(function(file, i) {
        var wrapper = document.createElement('div');
        wrapper.className = 'relative group rounded-xl overflow-hidden border-2 border-indigo-200 shadow-sm aspect-square';
        var reader = new FileReader();
        reader.onload = function(e) {
            var src = e.target.result;
            // img element — click opens lightbox
            var img = document.createElement('img');
            img.src = src;
            img.alt = 'Preview';
            img.className = 'w-full h-full object-cover cursor-zoom-in';
            img.addEventListener('click', function() { openEditLightbox(src); });
            wrapper.appendChild(img);
            // X button
            var xBtn = document.createElement('button');
            xBtn.type = 'button';
            xBtn.className = 'absolute top-1.5 right-1.5 z-10 bg-red-500 hover:bg-red-600 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs font-bold shadow-lg opacity-0 group-hover:opacity-100 transition-opacity';
            xBtn.innerHTML = '×';
            xBtn.addEventListener('click', function(ev) { ev.stopPropagation(); removeNewPortfolioFile(i); });
            wrapper.appendChild(xBtn);
            // New badge
            var badge = document.createElement('div');
            badge.className = 'absolute bottom-0 inset-x-0 bg-indigo-600 bg-opacity-80 px-2 py-1 text-center pointer-events-none';
            badge.innerHTML = '<span class="text-xs text-white font-medium">New</span>';
            wrapper.appendChild(badge);
        };
        reader.readAsDataURL(file);
        container.appendChild(wrapper);
    });
}

// ── Real-Time Username Validator ─────────────────────────────────────
(function () {
    var input = document.getElementById('username');
    if (!input || input.disabled) return;

    var feedback      = document.getElementById('username-feedback');
    var originalValue = input.value.trim();
    var usernameValid = true;
    var debounceTimer = null;

    input.addEventListener('input', function () {
        var val    = input.value.trim();
        var localOk = validateLocal(val);
        if (localOk && val !== originalValue) {
            showLoading('Checking availability...');
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function () { checkServer(val); }, 400);
        }
    });

    var form = input.closest('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (!validateLocal(input.value.trim()) || !usernameValid) {
                e.preventDefault(); input.focus();
            }
        });
    }

    function validateLocal(val) {
        input.classList.remove('border-red-400','border-green-400','border-yellow-400','ring-1','ring-red-400','ring-green-400','ring-yellow-400');
        feedback.className = 'mt-1.5 text-xs font-medium flex items-center transition-all duration-200';
        if (val === originalValue) { feedback.innerHTML = ''; usernameValid = true; return true; }
        if (!val)                  { showError('Username cannot be empty.'); return false; }
        if (val.length < 3)        { showError('Too short — minimum 3 characters.'); return false; }
        if (/^[0-9]/.test(val))    { showError('Cannot start with a number.'); return false; }
        if (/\s/.test(val))        { showError('Spaces are not allowed.'); return false; }
        if (!/^[a-zA-Z][a-zA-Z0-9_-]{2,}$/.test(val)) { showError('Only letters, numbers, _ and - allowed.'); return false; }
        return true;
    }

    function checkServer(val) {
        fetch('<?= APP_URL ?>/profile/check-username?username=' + encodeURIComponent(val))
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (input.value.trim() !== val) return;
                input.classList.remove('border-yellow-400','ring-yellow-400');
                if (d.available) { showSuccess('Username is available!'); usernameValid = true; }
                else             { showError(d.message || 'This username is already taken.'); usernameValid = false; }
            })
            .catch(function () { showSuccess('Username looks good.'); usernameValid = true; });
    }

    function showError(msg) {
        usernameValid = false;
        input.classList.add('border-red-400','ring-1','ring-red-400');
        feedback.classList.add('text-red-500');
        feedback.innerHTML = '<svg class="h-3.5 w-3.5 mr-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>' + msg;
    }
    function showSuccess(msg) {
        input.classList.add('border-green-400','ring-1','ring-green-400');
        feedback.classList.add('text-green-600');
        feedback.innerHTML = '<svg class="h-3.5 w-3.5 mr-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' + msg;
    }
    function showLoading(msg) {
        usernameValid = false;
        input.classList.add('border-yellow-400','ring-1','ring-yellow-400');
        feedback.classList.add('text-yellow-600');
        feedback.innerHTML = '<svg class="animate-spin h-3.5 w-3.5 mr-1 flex-shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>' + msg;
    }
})();
</script>
