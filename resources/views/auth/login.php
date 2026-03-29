<!-- Login Page -->
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

        <!-- Center quote -->
        <div class="relative z-10">
            <blockquote class="text-white">
                <p class="text-3xl font-bold leading-snug mb-6">
                    "Connecting skilled hands<br>with homes that need them."
                </p>
                <p class="text-indigo-200 text-base">
                    Thousands of verified craftsmen ready to help with your next project — plumbing, electrical, carpentry, and more.
                </p>
            </blockquote>
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

            <?php if (($_GET['success'] ?? '') === 'password_reset'): ?>
<div class="mb-6 flex items-start space-x-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
    <svg class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
    </svg>
    <span>Password updated successfully. You can now sign in with your new password.</span>
</div>
<?php endif; ?>

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
                <h1 class="text-3xl font-extrabold text-gray-900">Welcome back</h1>
                <p class="mt-2 text-sm text-gray-500">
                    Don't have an account?
                    <a href="<?= APP_URL ?>/register" class="font-semibold text-indigo-600 hover:text-indigo-500 transition">
                        Sign up for free
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

            <form action="<?= APP_URL ?>/login" method="POST" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                        placeholder="you@example.com">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="block w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition pr-11"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword('password', this)"
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                            <svg class="h-4 w-4 eye-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Forgot Password Link -->
<div class="flex justify-end -mt-1">
    <a href="<?= APP_URL ?>/forgot-password"
       class="text-xs font-medium text-indigo-600 hover:text-indigo-500 transition">
        Forgot your password?
    </a>
</div>
                <!-- Submit -->
                <button type="submit"
                    class="w-full flex justify-center items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Sign in
                    <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </form>

            <p class="mt-8 text-center text-xs text-gray-400">
                By signing in you agree to our
                <a href="#" class="underline hover:text-gray-600">Terms of Service</a>
                and
                <a href="#" class="underline hover:text-gray-600">Privacy Policy</a>.
            </p>
        </div>
    </div>
</div>

<script>
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
</script>