<?php
/**
 * FULL FOOTER PARTIAL
 * Extracted from layouts/app.php for maintainability.
 * All variables from app.php are available here via PHP's require scope.
 */
?>
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
                    <div class="col-span-2 md:col-span-1">
                        <a href="<?= APP_URL ?>/" class="flex items-center space-x-2.5 group w-fit">
                            <div class="h-8 w-8 bg-indigo-600 rounded-lg flex items-center justify-center shadow-sm group-hover:bg-indigo-700 transition-colors duration-200">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                                </svg>
                            </div>
                            <span class="text-xl font-extrabold text-gray-900 group-hover:text-indigo-600 transition-colors duration-200">Crafts</span>
                        </a>
                        <p class="mt-4 text-sm text-gray-500">Connecting skilled craftsmen with homeowners across Algeria.</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-3">Platform</h3>
                        <ul class="space-y-2">
                            <li><a href="<?= APP_URL ?>/search" class="text-sm text-gray-500 hover:text-gray-900">Find Craftsmen</a></li>
                            <li><a href="<?= APP_URL ?>/jobs" class="text-sm text-gray-500 hover:text-gray-900">Job Board</a></li>
                            <li><a href="<?= APP_URL ?>/register" class="text-sm text-gray-500 hover:text-gray-900">Sign Up</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 tracking-wider uppercase mb-3">Company</h3>
                        <ul class="space-y-2">
                            <li><a href="<?= APP_URL ?>/about" class="text-sm text-gray-500 hover:text-gray-900">About Us</a></li>
                            <li><a href="<?= APP_URL ?>/contact" class="text-sm text-gray-500 hover:text-gray-900">Contact</a></li>
                            <li><a href="<?= APP_URL ?>/privacy" class="text-sm text-gray-500 hover:text-gray-900">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-100 pt-6">
                    <p class="text-center text-sm text-gray-400">&copy; <?= date('Y') ?> Crafts. All rights reserved.</p>
                </div>
            </div>
        </footer>
