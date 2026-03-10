<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page Not Found | CraftConnect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen px-4">
    <div class="text-center max-w-lg">
        <!-- Large 404 Number -->
        <div class="relative inline-block mb-6">
            <span class="text-[10rem] font-extrabold leading-none text-indigo-100 select-none">404</span>
            <div class="absolute inset-0 flex items-center justify-center">
                <svg class="w-24 h-24 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-3">Page Not Found</h1>
        <p class="text-gray-500 mb-8 text-lg">Sorry, the page you're looking for doesn't exist or has been moved.</p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="javascript:history.back()" class="inline-flex items-center px-5 py-2.5 text-sm font-medium rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Go Back
            </a>
            <a href="/" class="inline-flex items-center px-5 py-2.5 text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/></svg>
                Back to Home
            </a>
        </div>

        <p class="mt-10 text-xs text-gray-400">&copy; <?= date('Y') ?> CraftConnect. All rights reserved.</p>
    </div>
</body>
</html>
