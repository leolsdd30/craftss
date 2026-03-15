<!-- Edit Profile Page -->
<?php
$existingImages = [];
if (!empty($craftsmanDetails['portfolio_images'])) {
    $existingImages = json_decode($craftsmanDetails['portfolio_images'], true) ?: [];
}
$wilayas = [
    "01 - Adrar","02 - Chlef","03 - Laghouat","04 - Oum El Bouaghi","05 - Batna","06 - Béjaïa","07 - Biskra","08 - Béchar","09 - Blida","10 - Bouira",
    "11 - Tamanrasset","12 - Tébessa","13 - Tlemcen","14 - Tiaret","15 - Tizi Ouzou","16 - Alger","17 - Djelfa","18 - Jijel","19 - Sétif","20 - Saïda",
    "21 - Skikda","22 - Sidi Bel Abbès","23 - Annaba","24 - Guelma","25 - Constantine","26 - Médéa","27 - Mostaganem","28 - M'Sila","29 - Mascara","30 - Ouargla",
    "31 - Oran","32 - El Bayadh","33 - Illizi","34 - Bordj Bou Arréridj","35 - Boumerdès","36 - El Tarf","37 - Tindouf","38 - Tissemsilt","39 - El Oued","40 - Khenchela",
    "41 - Souk Ahras","42 - Tipaza","43 - Mila","44 - Aïn Defla","45 - Naâma","46 - Aïn Témouchent","47 - Ghardaïa","48 - Relizane","49 - Timimoun","50 - Bordj Badji Mokhtar",
    "51 - Ouled Djellal","52 - Béni Abbès","53 - In Salah","54 - In Guezzam","55 - Touggourt","56 - Djanet","57 - El M'Ghair","58 - El Meniaa"
];

// Username lock logic
$canEditUsername = true;
$daysRemaining = 0;
if (!empty($user['username_updated_at'])) {
    $lastUpdated = strtotime($user['username_updated_at']);
    $daysPassed = floor((time() - $lastUpdated) / (24 * 60 * 60));
    if ($daysPassed < 14) {
        $canEditUsername = false;
        $daysRemaining = 14 - $daysPassed;
    }
}
?>
<div class="bg-gray-50 min-h-screen pb-16">

    <!-- Cover Banner -->
    <div class="h-36 w-full bg-gradient-to-r from-indigo-700 to-indigo-500 relative overflow-hidden">
        <div class="absolute inset-0 opacity-20"
             style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
    </div>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-10">

        <!-- Back link -->
        <a href="<?= APP_URL ?>/profile/<?= htmlspecialchars($user['username']) ?>"
           class="inline-flex items-center text-sm font-medium text-white hover:text-indigo-100 transition mb-4 drop-shadow">
            <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Profile
        </a>

        <form action="<?= APP_URL ?>/profile/edit" method="POST" enctype="multipart/form-data" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <!-- ── Profile Picture ──────────────────────────────────────── -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Profile Picture</h2>
                <div class="flex items-center space-x-5">
                    <div class="relative w-20 h-20 rounded-full overflow-hidden bg-gray-100 border-4 border-white shadow-sm ring-1 ring-gray-200 flex-shrink-0">
                        <img id="profile-preview"
                             src="<?= get_profile_picture_url($user['profile_picture'] ?? 'default.png', $user['first_name'], $user['last_name']) ?>"
                             alt="Profile" class="object-cover w-full h-full">
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <input type="file" name="profile_picture" id="profile-upload" class="sr-only" accept="image/png, image/jpeg, image/gif, image/webp" onchange="previewImage(event)">
                            <button type="button" onclick="document.getElementById('profile-upload').click()"
                                class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 cursor-pointer transition">
                                <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Change Photo
                            </button>
                            <span id="file-name" class="text-sm text-gray-500">No file chosen</span>
                        </div>
                        <p class="mt-1.5 text-xs text-gray-400">JPG, PNG, GIF or WebP up to 5MB</p>
                        <?php if(!empty($user['profile_picture']) && $user['profile_picture'] !== 'default.png'): ?>
                        <!-- Hidden checkbox to track if we should delete the image -->
                        <input type="checkbox" name="remove_picture" id="remove_picture" value="1" class="hidden">
                        <button type="button" id="remove-btn" onclick="removePhoto()" class="mt-2 text-sm text-red-600 hover:text-red-800 font-medium transition duration-150">
                            Remove Photo
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ── Personal Information ──────────────────────────────────── -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Personal Information</h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" name="first_name" id="first_name" required
                               value="<?= htmlspecialchars($user['first_name']) ?>"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" name="last_name" id="last_name" required
                               value="<?= htmlspecialchars($user['last_name']) ?>"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" name="phone_number" id="phone_number"
                               value="<?= htmlspecialchars($user['phone_number'] ?? '') ?>"
                               placeholder="e.g. 0550 123 456"
                               class="block w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>

                    <div>
                        <label for="wilaya" class="block text-sm font-medium text-gray-700 mb-1">Location (Wilaya)</label>
                        <select name="wilaya" id="wilaya"
                                class="block w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="">-- Select a Wilaya --</option>
                            <?php foreach ($wilayas as $w): ?>
                            <option value="<?= $w ?>" <?= ($user['wilaya'] ?? '') === $w ? 'selected' : '' ?>><?= $w ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                            Username
                            <span class="text-xs text-gray-400 font-normal ml-1">(can be changed once every 14 days)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-sm pointer-events-none">@</span>
                            <input type="text" name="username" id="username"
                                   value="<?= htmlspecialchars($user['username'] ?? '') ?>"
                                   <?= !$canEditUsername ? 'readonly disabled' : '' ?>
                                   placeholder="your_username"
                                   pattern="^[a-zA-Z][a-zA-Z0-9_-]{2,}$"
                                   title="Must start with a letter, be at least 3 characters long, and contain no spaces."
                                   class="block w-full pl-7 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition <?= !$canEditUsername ? 'bg-gray-100 text-gray-500 cursor-not-allowed border-gray-200' : '' ?>">
                        </div>
                        <?php if (!$canEditUsername): ?>
                            <p class="mt-2 text-sm text-red-500 font-medium flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Locked. You can change your username in <?= $daysRemaining ?> days.
                            </p>
                        <?php else: ?>
                            <div id="username-feedback" class="mt-2 text-xs font-medium flex items-center transition-all duration-200"></div>
                            <p class="mt-1 text-xs text-gray-400">Must start with a letter and be at least 3 characters.</p>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

            <!-- ── Craftsman Section ─────────────────────────────────────── -->
            <?php if ($user['role'] === 'craftsman'): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Professional Details</h2>
                <div class="space-y-4">

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="service_category" class="block text-sm font-medium text-gray-700 mb-1">Service Category</label>
                            <select name="service_category" id="service_category"
                                    class="block w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <?php
                                    $selectedCat = $craftsmanDetails['service_category'] ?? 'General Handyman';
                                    $categories = ["Plumbing", "Electrical", "Carpentry", "Painting", "Roofing", "HVAC", "Landscaping", "Tiling", "General Handyman"];
                                    foreach($categories as $cat):
                                ?>
                                <option value="<?= $cat ?>" <?= $cat === $selectedCat ? 'selected' : '' ?>><?= $cat ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="hourly_rate" class="block text-sm font-medium text-gray-700 mb-1">Hourly Rate</label>
                            <div class="relative">
                                <input type="number" name="hourly_rate" id="hourly_rate"
                                       step="0.01" min="0"
                                       value="<?= htmlspecialchars($craftsmanDetails['hourly_rate'] ?? '0.00') ?>"
                                       class="block w-full px-4 py-2.5 pr-14 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-400 text-sm font-medium">DZD</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">
                            Professional Bio
                            <span class="text-gray-400 font-normal ml-1 text-xs">(Tell homeowners about your experience)</span>
                        </label>
                        <textarea id="bio" name="bio" rows="4"
                                  placeholder="Describe your experience, skills, and what makes you stand out..."
                                  class="block w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"><?= htmlspecialchars($craftsmanDetails['bio'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- ── Portfolio ─────────────────────────────────────────────── -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-1 flex items-center">
                    <svg class="mr-2 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Portfolio Images
                </h2>
                <p class="text-xs text-gray-400 mb-4">Up to 10 images. JPG, PNG, GIF or WebP up to 5MB each.</p>

                <!-- Existing images -->
                <?php if (!empty($existingImages)): ?>
                <div class="mb-4">
                    <p class="text-xs font-medium text-gray-500 mb-2">Your current portfolio (<span id="portfolio-count"><?= count($existingImages) ?></span> images). Click the × to remove.</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4" id="existing-portfolio">
                        <?php foreach ($existingImages as $index => $img): ?>
                        <div class="relative group rounded-xl overflow-hidden border border-gray-200 shadow-sm aspect-square" id="portfolio-item-<?= $index ?>">
                            <img src="<?= APP_URL ?>/uploads/portfolio/<?= htmlspecialchars($img) ?>" 
                                 alt="Portfolio piece" 
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200"></div>
                            <button type="button" 
                                    onclick="removePortfolioImage(<?= $index ?>, '<?= htmlspecialchars($img) ?>')" 
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full h-7 w-7 flex items-center justify-center text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-red-600 shadow-lg">
                                ×
                            </button>
                            <input type="hidden" name="existing_images[]" value="<?= htmlspecialchars($img) ?>" id="input-portfolio-<?= $index ?>">
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Upload new -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Add New Images</label>
                    <!-- Hidden file input that gets synced before submit -->
                    <input type="file" name="portfolio_images[]" id="portfolio-upload" multiple 
                           accept="image/png,image/jpeg,image/gif,image/webp" 
                           class="hidden">
                    <!-- Visible picker (separate, so we can accumulate) -->
                    <input type="file" id="portfolio-picker" multiple 
                           accept="image/png,image/jpeg,image/gif,image/webp" 
                           class="hidden" 
                           onchange="addNewPortfolioFiles(this)">
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-indigo-300 transition-colors duration-200 cursor-pointer relative" 
                         id="portfolio-dropzone"
                         onclick="document.getElementById('portfolio-picker').click()">
                        <svg class="mx-auto h-10 w-10 text-gray-300 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm text-gray-500 font-medium" id="dropzone-text">Click to upload images</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, GIF or WebP · Max 5MB each · Up to 10 images total</p>
                    </div>
                    <!-- New Image Previews -->
                    <div id="portfolio-new-previews" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mt-4 hidden"></div>
                </div>
            </div>
            <?php endif; ?>

            <!-- ── Save Button ───────────────────────────────────────────── -->
            <div class="flex items-center justify-end space-x-3 pb-4">
                <a href="<?= APP_URL ?>/profile/<?= htmlspecialchars($user['username']) ?>"
                   class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:text-gray-900 transition">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    Save Changes
                    <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    // ── Profile Picture Preview ──────────────────────────────────
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('profile-preview');
            output.src = reader.result;
            output.style.opacity = '1';
        }
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
            document.getElementById('file-name').textContent = event.target.files[0].name;
            document.getElementById('file-name').classList.remove('text-gray-500');
            document.getElementById('file-name').classList.add('text-indigo-600', 'font-medium');
            
            // Uncheck the remove box if they uploaded something new
            var removeBox = document.getElementById('remove_picture');
            if(removeBox) removeBox.checked = false;
        }
    }

    function removePhoto() {
        document.getElementById('remove_picture').checked = true;
        document.getElementById('profile-upload').value = '';
        document.getElementById('file-name').textContent = 'No file chosen';
        document.getElementById('file-name').classList.add('text-gray-500');
        document.getElementById('file-name').classList.remove('text-indigo-600', 'font-medium');
        
        // Hide the current image visually to show it's "removed"
        document.getElementById('profile-preview').style.opacity = '0.3';
        document.getElementById('remove-btn').style.display = 'none';
    }

    // ── Portfolio Management ─────────────────────────────────────
    function removePortfolioImage(index, filename) {
        var item = document.getElementById('portfolio-item-' + index);
        var input = document.getElementById('input-portfolio-' + index);
        if (item) {
            item.style.opacity = '0';
            item.style.transform = 'scale(0.8)';
            item.style.transition = 'all 0.3s ease';
            setTimeout(function() { 
                item.remove(); 
                updatePortfolioCount();
            }, 300);
        }
        if (input) input.remove();
    }

    function updatePortfolioCount() {
        var countEl = document.getElementById('portfolio-count');
        if (countEl) {
            var remaining = document.querySelectorAll('#existing-portfolio [id^="portfolio-item-"]').length;
            countEl.textContent = remaining;
        }
    }

    // ── Accumulated Portfolio Upload ────────────────────────────────
    var pendingFiles = []; // Accumulates File objects across multiple picks

    function addNewPortfolioFiles(picker) {
        var files = Array.from(picker.files);
        if (files.length === 0) return;

        // Add each new file to the accumulated array
        files.forEach(function(file) {
            pendingFiles.push(file);
        });

        // Reset the picker so the same file can be re-selected if needed
        picker.value = '';

        // Sync the real form input + render previews
        syncPortfolioInput();
        renderNewPreviews();
    }

    function removeNewPortfolioFile(index) {
        pendingFiles.splice(index, 1);
        syncPortfolioInput();
        renderNewPreviews();
    }

    function syncPortfolioInput() {
        // Build a new DataTransfer and assign its files to the real input
        var dt = new DataTransfer();
        pendingFiles.forEach(function(file) {
            dt.items.add(file);
        });
        document.getElementById('portfolio-upload').files = dt.files;

        // Update dropzone text
        var textEl = document.getElementById('dropzone-text');
        if (pendingFiles.length > 0) {
            textEl.textContent = pendingFiles.length + ' new image' + (pendingFiles.length > 1 ? 's' : '') + ' ready to upload';
        } else {
            textEl.textContent = 'Click to upload images';
        }
    }

    function renderNewPreviews() {
        var container = document.getElementById('portfolio-new-previews');
        container.innerHTML = '';

        if (pendingFiles.length === 0) {
            container.classList.add('hidden');
            return;
        }

        container.classList.remove('hidden');

        pendingFiles.forEach(function(file, i) {
            var wrapper = document.createElement('div');
            wrapper.className = 'relative group rounded-xl overflow-hidden border-2 border-indigo-200 shadow-sm aspect-square';

            // Read and display the image
            var reader = new FileReader();
            reader.onload = function(e) {
                wrapper.innerHTML = 
                    '<img src="' + e.target.result + '" alt="Preview" class="w-full h-full object-cover">' +
                    '<div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200"></div>' +
                    '<button type="button" onclick="removeNewPortfolioFile(' + i + ')" ' +
                        'class="absolute top-2 right-2 bg-red-500 text-white rounded-full h-7 w-7 flex items-center justify-center text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-red-600 shadow-lg">' +
                        '×</button>' +
                    '<div class="absolute bottom-0 inset-x-0 bg-indigo-600 bg-opacity-80 px-2 py-1 text-center">' +
                    '<span class="text-xs text-white font-medium">New</span></div>';
            };
            reader.readAsDataURL(file);

            container.appendChild(wrapper);
        });
    }

    // ─── Real-Time Username Validator ──────────────────────────────
    (function() {
        var input = document.getElementById('username');
        if (!input || input.disabled) return;

        var feedback = document.getElementById('username-feedback');
        var originalValue = input.value.trim();
        var usernameValid = true;
        var debounceTimer = null;

        input.addEventListener('input', function() {
            var val = input.value.trim();
            // Run local checks immediately
            var localOk = validateLocal(val);
            
            // If local checks pass and value changed, check server
            if (localOk && val !== originalValue) {
                showLoading('Checking availability...');
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    checkServer(val);
                }, 400); // 400ms debounce
            }
        });

        // Prevent form submit if invalid
        var form = input.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                var val = input.value.trim();
                var localOk = validateLocal(val);
                if (!localOk || !usernameValid) {
                    e.preventDefault();
                    input.focus();
                }
            });
        }

        function validateLocal(val) {
            // Reset
            input.classList.remove('border-red-400', 'border-green-400', 'border-yellow-400', 'ring-1', 'ring-red-400', 'ring-green-400', 'ring-yellow-400');
            feedback.className = 'mt-2 text-xs font-medium flex items-center transition-all duration-200';

            // Same as original — no change needed, valid by default
            if (val === originalValue) {
                feedback.innerHTML = '';
                usernameValid = true;
                return true; 
            }

            if (val === '') {
                showError('Username cannot be empty.');
                return false;
            }
            if (val.length < 3) {
                showError('Too short. Minimum 3 characters.');
                return false;
            }
            if (/^[0-9]/.test(val)) {
                showError('Cannot start with a number.');
                return false;
            }
            if (/\s/.test(val)) {
                showError('Spaces are not allowed.');
                return false;
            }
            if (!/^[a-zA-Z][a-zA-Z0-9_-]{2,}$/.test(val)) {
                showError('Only letters, numbers, underscores and dashes allowed.');
                return false;
            }

            return true;
        }

        function checkServer(val) {
            fetch('<?= APP_URL ?>/profile/check-username?username=' + encodeURIComponent(val))
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    // Make sure the input hasn't changed while we were waiting
                    if (input.value.trim() !== val) return;
                    
                    input.classList.remove('border-yellow-400', 'ring-yellow-400');
                    if (data.available) {
                        showSuccess('Username is available!');
                        usernameValid = true;
                    } else {
                        showError(data.message || 'This username is already taken.');
                        usernameValid = false;
                    }
                })
                .catch(function() {
                    // Network error — allow submission, server will re-validate
                    showSuccess('Username looks good.');
                    usernameValid = true;
                });
        }

        function showError(msg) {
            usernameValid = false;
            input.classList.add('border-red-400', 'ring-1', 'ring-red-400');
            feedback.classList.add('text-red-500');
            feedback.innerHTML = '<svg class="h-3.5 w-3.5 mr-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>' + msg;
        }

        function showSuccess(msg) {
            input.classList.add('border-green-400', 'ring-1', 'ring-green-400');
            feedback.classList.add('text-green-600');
            feedback.innerHTML = '<svg class="h-3.5 w-3.5 mr-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' + msg;
        }

        function showLoading(msg) {
            usernameValid = false; // block submit while checking
            input.classList.add('border-yellow-400', 'ring-1', 'ring-yellow-400');
            feedback.classList.add('text-yellow-600');
            feedback.innerHTML = '<svg class="animate-spin h-3.5 w-3.5 mr-1 flex-shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>' + msg;
        }
    })();
</script>