<?php $hideFooter = true; ?><!-- Forgot Password Page -->
<div class="min-h-[calc(100dvh-4rem)] bg-gray-50 flex">

    <!-- Left Panel — Branding -->
    <div class="hidden lg:flex lg:w-1/2 bg-indigo-700 flex-col justify-between p-12 relative overflow-hidden lg:h-[calc(100dvh-4rem)] lg:sticky lg:top-16">
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-white leading-snug mb-4">
                Forgot your<br>password?
            </h2>
            <p class="text-indigo-200 text-base leading-relaxed">
                No worries — it happens. Enter your email and we'll send you a secure link to reset it right away.
            </p>
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
    <div class="w-full lg:w-1/2 min-h-[calc(100dvh-4rem)] flex items-center justify-center px-6 py-12 sm:px-12">
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

            <?php if (!empty($submitted)): ?>
            <!-- ── SUCCESS STATE ────────────────────────────────────── -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
                    <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900 mb-2">Check your email</h1>
                <p class="text-sm text-gray-500 mb-8">
                    If that email address is registered with Crafts, a password reset link has been sent to it.
                </p>

                <?php if (!empty($mockResetUrl)): ?>
                <!-- MOCK BLOCK — remove once Resend is wired up -->
                <div class="mb-6 bg-amber-50 border border-amber-200 rounded-xl p-5 text-left">
                    <div class="flex items-center space-x-2 mb-3">
                        <svg class="h-5 w-5 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs font-bold text-amber-700 uppercase tracking-wide">Development Mode</p>
                    </div>
                    <p class="text-xs text-amber-700 mb-3">No email was sent. In production this link would arrive in the user's inbox. Click it to test the reset flow:</p>
                    <a href="<?= e($mockResetUrl) ?>"
                       class="block w-full text-center px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl transition duration-150">
                        Open Reset Link →
                    </a>
                </div>
                <?php endif; ?>

                <a href="<?= APP_URL ?>/login"
                   class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 transition">
                    <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Sign In
                </a>
            </div>

            <?php else: ?>
            <!-- ── FORM STATE ───────────────────────────────────────── -->
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900">Forgot password?</h1>
                <p class="mt-2 text-sm text-gray-500">
                    Enter your email and we'll send you a reset link.
                </p>
            </div>

            <?php if (!empty($error)): ?>
            <div class="mb-6 flex items-start space-x-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <svg class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span><?= e($error) ?></span>
            </div>
            <?php endif; ?>

            <form action="<?= APP_URL ?>/forgot-password" method="POST" class="space-y-5">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm text-gray-900 placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                        placeholder="you@example.com"
                        value="<?= e($_POST['email'] ?? '') ?>">
                </div>

                <button type="submit"
                    class="w-full flex justify-center items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Send Reset Link
                    <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-gray-500">
                Remembered it?
                <a href="<?= APP_URL ?>/login" class="font-semibold text-indigo-600 hover:text-indigo-500 transition">
                    Back to Sign In
                </a>
            </p>
            <?php endif; ?>

        </div>
    </div>
</div>
