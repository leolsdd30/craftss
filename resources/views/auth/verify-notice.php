<?php $minimalFooter = true; ?>
<div class="max-w-md mx-auto mt-16 bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-sm text-center border border-gray-100 dark:border-gray-700">
    <div class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900/50 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
        </svg>
    </div>

    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Check Your Inbox</h2>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'resent'): ?>
        <div class="mb-6 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm text-center font-medium">
            ✅ A new verification link has been sent!
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="mb-6 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg text-sm text-center font-medium">
            <?php if ($_GET['error'] === 'wait'): ?>
                ⏳ Please wait 10 seconds before requesting another email.
            <?php else: ?>
                An error occurred. Please try again.
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <p class="text-gray-600 dark:text-gray-400 mb-8">
        You must verify your email address to access this feature. We sent a secure verification link to your email when you signed up.
    </p>

    <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="<?= APP_URL ?>/profile" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium transition-colors">
            Return to Profile
        </a>
        <form action="<?= APP_URL ?>/verify-resend" method="POST" class="inline m-0">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <button type="submit" class="px-5 py-2.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 rounded-lg font-medium transition-colors border border-indigo-100 dark:border-indigo-800/30">
                Send Again
            </button>
        </form>
    </div>
</div>
