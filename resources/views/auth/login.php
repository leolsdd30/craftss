<div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 mt-10">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-xl shadow-lg border border-gray-100">
        <div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-gray-900">
                Welcome back
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Or
                <a href="<?= APP_URL ?>/register" class="font-medium text-blue-600 hover:text-blue-500 transition">
                    create a new account
                </a>
            </p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-md text-sm border border-red-200 text-center">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form class="mt-8 space-y-6" action="<?= APP_URL ?>/login" method="POST">
            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="email-address" class="block text-sm font-medium text-gray-700">Email address</label>
                    <input id="email-address" name="email" type="email" autocomplete="email" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm mt-1" placeholder="you@example.com">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm mt-1" placeholder="••••••••">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                        Remember me
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                        Forgot your password?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition">
                    Sign in
                </button>
            </div>
        </form>
    </div>
</div>
