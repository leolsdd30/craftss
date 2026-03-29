<!-- Register Page -->
<div class="min-h-[calc(100vh-4rem)] bg-gray-50 flex">

    <!-- Left Panel — Branding -->
    <div class="hidden lg:flex lg:w-1/2 bg-indigo-700 flex-col justify-between p-12 relative overflow-hidden lg:h-[calc(100vh-1rem)] lg:sticky ">
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

        <!-- Role cards -->
        <div class="relative z-10 space-y-4">
            <p class="text-indigo-200 text-sm font-semibold uppercase tracking-widest mb-4">Who is Crafts for?</p>

            <div class="bg-white bg-opacity-10 border border-white border-opacity-20 rounded-xl p-5 backdrop-blur-sm">
                <div class="flex items-start space-x-4">
                    <div class="h-10 w-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-bold">Homeowners</p>
                        <p class="text-indigo-200 text-sm mt-0.5">Post jobs, browse craftsmen, and get your home projects done by verified professionals.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white bg-opacity-10 border border-white border-opacity-20 rounded-xl p-5 backdrop-blur-sm">
                <div class="flex items-start space-x-4">
                    <div class="h-10 w-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-bold">Craftsmen</p>
                        <p class="text-indigo-200 text-sm mt-0.5">Build your profile, receive booking requests, and grow your client base across Algeria.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom note -->
        <div class="relative z-10">
            <p class="text-indigo-300 text-sm">Free to join. No subscription fees. Pay only when you get the job done.</p>
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
                <h1 class="text-3xl font-extrabold text-gray-900">Create your account</h1>
                <p class="mt-2 text-sm text-gray-500">
                    Already have an account?
                    <a href="<?= APP_URL ?>/login" class="font-semibold text-indigo-600 hover:text-indigo-500 transition">
                        Sign in
                    </a>
                </p>
            </div>

            <?php if (!empty($error)): ?>
            <div class="mb-6 flex items-start space-x-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <svg class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
            <?php endif; ?>

            <form action="<?= APP_URL ?>/register" method="POST" class="space-y-5" id="register-form">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">

                <!-- First & Last Name -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First name</label>
                        <input id="first_name" name="first_name" type="text" required autocomplete="given-name"
                            class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                            placeholder="Ahmed">
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last name</label>
                        <input id="last_name" name="last_name" type="text" required autocomplete="family-name"
                            class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                            placeholder="Benali">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                    <input id="email" name="email" type="email" required autocomplete="email"
                        class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                        placeholder="you@example.com">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
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
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-1">Confirm password</label>
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
                    <p id="pw-match-msg" class="mt-1 text-xs hidden"></p>
                </div>

                <!-- Account Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">I am a...</label>
                    <div class="grid grid-cols-2 gap-3">
                        <!-- Homeowner option -->
                        <label class="role-option relative cursor-pointer">
                            <input type="radio" name="role" value="homeowner" class="sr-only" checked>
                            <div class="role-card flex flex-col items-center p-4 rounded-xl border-2 border-indigo-500 bg-indigo-50 transition-all">
                                <svg class="h-7 w-7 text-indigo-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-900">Homeowner</span>
                                <span class="text-xs text-gray-500 mt-0.5 text-center">I need work done</span>
                            </div>
                        </label>
                        <!-- Craftsman option -->
                        <label class="role-option relative cursor-pointer">
                            <input type="radio" name="role" value="craftsman" class="sr-only">
                            <div class="role-card flex flex-col items-center p-4 rounded-xl border-2 border-gray-200 bg-white hover:border-indigo-300 transition-all">
                                <svg class="h-7 w-7 text-gray-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-900">Craftsman</span>
                                <span class="text-xs text-gray-500 mt-0.5 text-center">I want to find work</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" id="submit-btn"
                    class="w-full flex justify-center items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Create account
                    <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </form>

            <p class="mt-8 text-center text-xs text-gray-400">
                By creating an account you agree to our
                <a href="#" class="underline hover:text-gray-600">Terms of Service</a>
                and
                <a href="#" class="underline hover:text-gray-600">Privacy Policy</a>.
            </p>
        </div>
    </div>
</div>

<script>
// Show/hide password toggle
function togglePassword(fieldId, btn) {
    var input = document.getElementById(fieldId);
    var icon = btn.querySelector('.eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"/>';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
    }
}

// Role card toggle styling
document.querySelectorAll('.role-option input[type="radio"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.role-option .role-card').forEach(function(card) {
            card.classList.remove('border-indigo-500', 'bg-indigo-50');
            card.classList.add('border-gray-200', 'bg-white');
            card.querySelector('svg').classList.remove('text-indigo-600');
            card.querySelector('svg').classList.add('text-gray-500');
        });
        var selected = this.closest('.role-option').querySelector('.role-card');
        selected.classList.add('border-indigo-500', 'bg-indigo-50');
        selected.classList.remove('border-gray-200', 'bg-white');
        selected.querySelector('svg').classList.add('text-indigo-600');
        selected.querySelector('svg').classList.remove('text-gray-500');
    });
});

// Password match validation
var pwField = document.getElementById('password');
var pwConfirm = document.getElementById('password_confirm');
var pwMsg = document.getElementById('pw-match-msg');
var submitBtn = document.getElementById('submit-btn');

function checkPasswordMatch() {
    if (pwConfirm.value === '') {
        pwMsg.classList.add('hidden');
        return;
    }
    pwMsg.classList.remove('hidden');
    if (pwField.value === pwConfirm.value) {
        pwMsg.textContent = '✓ Passwords match';
        pwMsg.className = 'mt-1 text-xs text-green-600';
        pwConfirm.classList.remove('border-red-400');
        pwConfirm.classList.add('border-green-400');
    } else {
        pwMsg.textContent = '✗ Passwords do not match';
        pwMsg.className = 'mt-1 text-xs text-red-500';
        pwConfirm.classList.remove('border-green-400');
        pwConfirm.classList.add('border-red-400');
    }
}

pwConfirm.addEventListener('input', checkPasswordMatch);
pwField.addEventListener('input', checkPasswordMatch);

// Prevent submit if passwords don't match
document.getElementById('register-form').addEventListener('submit', function(e) {
    if (pwField.value !== pwConfirm.value) {
        e.preventDefault();
        checkPasswordMatch();
        pwConfirm.focus();
    }
});
</script>