<?php
$homeUrl = '/';
if (defined('APP_URL')) {
    $homeUrl = APP_URL . '/';
} else {
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    if (($pos = strpos($script, '/resources/')) !== false) {
        $homeUrl = substr($script, 0, $pos) . '/public/';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Access Denied | Crafts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen px-4">
    <div class="text-center max-w-lg">
        <div class="relative inline-block mb-6">
            <span class="text-[10rem] font-extrabold leading-none text-red-100 select-none">403</span>
            <div class="absolute inset-0 flex items-center justify-center">
                <svg class="w-24 h-24 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-3">Access Denied</h1>
        <p class="text-gray-500 mb-8 text-lg">Sorry, you do not have permission to access this page.</p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="<?= htmlspecialchars($homeUrl) ?>" class="inline-flex items-center px-5 py-2.5 text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                Back to Home
            </a>
        </div>

        <p class="mt-10 text-xs text-gray-400">&copy; <?= date('Y') ?> Crafts. All rights reserved.</p>
    </div>
</body>
</html>
