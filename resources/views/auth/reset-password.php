<!-- Reset Password Page -->
<div class="min-h-[calc(100vh-4rem)] bg-gray-50 flex">

    <!-- Left Panel — Branding -->
    <div class="hidden lg:flex lg:w-1/2 bg-indigo-700 flex-col justify-between p-12 relative overflow-hidden lg:h-[calc(100vh-4rem)] lg:sticky lg:top-16">
        <!-- Background decoration -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-600 rounded-full opacity-50"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 bg-indigo-800 rounded-full opacity-60 translate-x-1/3 translate-y-1/3"></div>
            <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-indigo-500 rounded-full opacity-20 -translate-x-1/2 -translate-y-1/2"></div>
        </div>

        <!-- Logo -->
        <div class="relative z-10">
            <a href="<?= APP_URL ?>/" class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center shadow-md">
                    <svg class="h-6 w-6 text-indigo-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                    </svg>
                </div>
                <span class="text-2xl font-extrabold text-white tracking-tight">Crafts</span>
            </a>
        </div>

        <!-- Center content -->
        <div class="relative z-10">
            <div class="h-16 w-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center mb-6">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-white leading-snug mb-4">
                Create a new<br>password
            </h2>
            <p class="text-indigo-200 text-base leading-relaxed">
                Choose something strong and memorable. Your new password must be at least 8 characters.
            </p>

            <!-- Tips -->
            <ul class="mt-6 space-y-2">
                <li class="flex items-center space-x-2 text-indigo-200 text-sm">
                    <svg class="h-4 w-4 text-indigo-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>At least 8 characters</span>
                </li>
                <li class="flex items-center space-x-2 text-indigo-200 text-sm">
                    <svg class="h-4 w-4 text-indigo-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Mix letters and numbers</span>
                </li>
                <li class="flex items-center space-x-2 text-indigo-200 text-sm">
                    <svg class="h-4 w-4 text-indigo-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Don't reuse old passwords</span>
                </li>
            </ul>
        </div>

        <!-- Stat badges -->
        <div class="relative z-10 flex items-center space-x-6">
            <div class="text-center">
                <p class="text-2xl font-extrabold text-white">500+</p>
                <p class="text-xs text-indigo-200 mt-0.5">Craftsmen</p>
            </div>
            <div class="w-px h-10 bg-indigo-500"></div>
            <div class="text-center">
                <p class="text-2xl font-extrabold text-white">48</p>
                <p class="text-xs text-indigo-200 mt-0.5">Wilayas</p>
            </div>
            <div class="w-px h-10 bg-indigo-500"></div>
            <div class="text-center">
                <p class="text-2xl font-extrabold text-white">1,200+</p>
                <p class="text-xs text-indigo-200 mt-0.5">Jobs Done</p>
            </div>
        </div>
    </div>

    <!-- Right Panel — Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12 sm:px-12">
        <div class="w-full max-w-md">

            <!-- Mobile logo -->
            <div class="flex justify-center mb-8 lg:hidden">
                <a href="<?= APP_URL ?>/" class="flex items-center space-x-2">
                    <div class="h-9 w-9 bg-indigo-600 rounded-xl flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                        </svg>
                    </div>
                    <span class="text-xl font-extrabold text-gray-900">Crafts</span>
                </a>
            </div>

            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900">Set new password</h1>
                <p class="mt-2 text-sm text-gray-500">Must be at least 8 characters.</p>
            </div>

            <?php if (!empty($error)): ?>
            <div class="mb-6 flex items-start space-x-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <svg class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span><?= e($error) ?></span>
            </div>
            <?php endif; ?>

            <form action="<?= APP_URL ?>/reset-password" method="POST" class="space-y-5" id="reset-form">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                <input type="hidden" name="token" value="<?= e($token ?? '') ?>">

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New password</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition pr-11"
                            placeholder="At least 8 characters">
                        <button type="button" onclick="togglePassword('password', this)"
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                            <svg class="h-4 w-4 eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    <!-- Live length feedback -->
                    <p id="pw-length-msg" class="mt-1 text-xs hidden"></p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-1">Confirm new password</label>
                    <div class="relative">
                        <input id="password_confirm" name="password_confirm" type="password" required autocomplete="new-password"
                            class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition pr-11"
                            placeholder="Repeat your password">
                        <button type="button" onclick="togglePassword('password_confirm', this)"
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                            <svg class="h-4 w-4 eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    <!-- Live match feedback -->
                    <p id="pw-match-msg" class="mt-1 text-xs hidden"></p>
                </div>

                <button type="submit" id="submit-btn"
                    class="w-full flex justify-center items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Reset Password
                    <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-gray-500">
                Remember it now?
                <a href="<?= APP_URL ?>/login" class="font-semibold text-indigo-600 hover:text-indigo-500 transition">
                    Back to Sign In
                </a>
            </p>

        </div>
    </div>
</div>

<script>
// ── Show / Hide Password Toggle ──────────────────────────────────────────────
function togglePassword(fieldId, btn) {
    var input = document.getElementById(fieldId);
    var icon  = btn.querySelector('.eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"/>';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
    }
}

// ── Live Validation ──────────────────────────────────────────────────────────
var pwField   = document.getElementById('password');
var pwConfirm = document.getElementById('password_confirm');
var pwLenMsg  = document.getElementById('pw-length-msg');
var pwMatchMsg = document.getElementById('pw-match-msg');
var submitBtn = document.getElementById('submit-btn');

// -- Length check on the main password field --
pwField.addEventListener('input', function () {
    var val = pwField.value;

    // Show length feedback only once the user starts typing
    if (val.length === 0) {
        pwLenMsg.classList.add('hidden');
        pwField.classList.remove('border-green-400', 'border-red-400');
    } else if (val.length < 8) {
        pwLenMsg.classList.remove('hidden');
        pwLenMsg.textContent  = '✗ Too short — minimum 8 characters';
        pwLenMsg.className    = 'mt-1 text-xs text-red-500';
        pwField.classList.remove('border-green-400');
        pwField.classList.add('border-red-400');
    } else {
        pwLenMsg.classList.remove('hidden');
        pwLenMsg.textContent  = '✓ Good length';
        pwLenMsg.className    = 'mt-1 text-xs text-green-600';
        pwField.classList.remove('border-red-400');
        pwField.classList.add('border-green-400');
    }

    // Re-run match check whenever the main password changes (if confirm already has a value)
    if (pwConfirm.value.length > 0) {
        checkMatch();
    }
});

// -- Match check on the confirm field --
function checkMatch() {
    var val     = pwConfirm.value;
    var mainVal = pwField.value;

    if (val.length === 0) {
        pwMatchMsg.classList.add('hidden');
        pwConfirm.classList.remove('border-green-400', 'border-red-400');
        return;
    }

    pwMatchMsg.classList.remove('hidden');

    if (mainVal === val) {
        pwMatchMsg.textContent = '✓ Passwords match';
        pwMatchMsg.className   = 'mt-1 text-xs text-green-600';
        pwConfirm.classList.remove('border-red-400');
        pwConfirm.classList.add('border-green-400');
    } else {
        pwMatchMsg.textContent = '✗ Passwords do not match';
        pwMatchMsg.className   = 'mt-1 text-xs text-red-500';
        pwConfirm.classList.remove('border-green-400');
        pwConfirm.classList.add('border-red-400');
    }
}

pwConfirm.addEventListener('input', checkMatch);

// -- Prevent submit if validation fails --
document.getElementById('reset-form').addEventListener('submit', function (e) {
    var isLongEnough = pwField.value.length >= 8;
    var isMatching   = pwField.value === pwConfirm.value;

    if (!isLongEnough || !isMatching) {
        e.preventDefault();
        // Trigger visual feedback if not already shown
        if (!isLongEnough) {
            pwField.dispatchEvent(new Event('input'));
            pwField.focus();
        } else {
            checkMatch();
            pwConfirm.focus();
        }
    }
});
</script>
