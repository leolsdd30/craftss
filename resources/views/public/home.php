<?php
/*
|=============================================================================
| HERO IMAGE MODE — READ THIS BEFORE CHANGING ANYTHING
|=============================================================================
|
| Currently ACTIVE: SLIDESHOW MODE
|   - Images auto-advance every 4 seconds with a smooth fade
|   - Dot indicators at the bottom let the user click to jump to any photo
|   - Arrow buttons (< >) let the user go forward or back manually
|   - Slideshow pauses when the user hovers over the image
|   - All controlled by the <script> block at the bottom of this file
|
| To switch back to STATIC MODE (one fixed photo, no JS, simpler):
|   1. Delete or comment out the entire "SLIDESHOW MODE ACTIVE" PHP block below
|   2. Uncomment the "STATIC MODE" PHP block below it
|   3. In the Hero Image section (search for "Hero Image"), replace the
|      slideshow <div id="hero-slideshow"> block with the single <img> tag:
|         <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full"
|              src="<?= $staticPhotoUrl ?>"
|              alt="Professional craftsman at work">
|   4. Delete the <script> block at the very bottom of this file
|
|=============================================================================
*/

// =============================================================================
// SLIDESHOW MODE ACTIVE — comment out this entire block to switch to static
// =============================================================================
$heroPhotos = [
    ['id' => '1620653713380-7a34b773fef8', 'alt' => 'Professional plumber fixing a pipe with a wrench'],
    ['id' => '1504307651254-35680f356dfd', 'alt' => 'Handyman working on a home project'],
    ['id' => '1749532125405-70950966b0e5', 'alt' => 'Electrician working on electrical panel'],
    ['id' => '1513694203232-719a280e022f', 'alt' => 'Professional painter painting a wall'],
    ['id' => '1621905251918-48416bd8575a', 'alt' => 'Carpenter measuring wood for a project'],
    ['id' => '1558618666-fcd25c85cd64', 'alt' => 'Mason laying bricks for a wall'],
    ['id' => '1574359411659-15573a27fd0c', 'alt' => 'General contractor reviewing building plans'],
    ['id' => '1589939705384-5185137a7f0f', 'alt' => 'Handyman using a power drill'],
];
// Build the full URLs array to pass into JavaScript
$slideshowUrls = array_map(fn($p) => [
    'url' => "https://images.unsplash.com/photo-{$p['id']}?q=80&w=1545&auto=format&fit=crop",
    'alt' => $p['alt']
], $heroPhotos);
// =============================================================================
// END SLIDESHOW MODE BLOCK
// =============================================================================


// =============================================================================
// STATIC MODE — uncomment this block (and follow the 4 steps above) to use
// a single fixed photo instead of the slideshow
// =============================================================================
// $staticPhotoUrl = "https://images.unsplash.com/photo-1620653713380-7a34b773fef8?q=80&w=1545&auto=format&fit=crop";
// ^ This is the plumber photo — change the ID to use a different one.
// All available IDs are listed in the $heroPhotos array in the slideshow block above.
// =============================================================================
// END STATIC MODE BLOCK
// =============================================================================
?>

<!-- Hero Section -->
<div class="relative bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
            <!-- Diagonal shape for desktop — creates the angled cutout between text and image -->
            <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2"
                 fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                <polygon points="50,0 100,0 50,100 0,100" />
            </svg>

            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">Find the perfect professional for your</span>
                        <span class="block text-indigo-600 xl:inline">home projects</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        Connect with top-rated, verified craftsmen in your area. From plumbing to carpentry,
                        get your home improvement done right with Crafts.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-md shadow">
                            <a href="<?= APP_URL ?>/register"
                               class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10 transition duration-150 ease-in-out">
                                Get Started
                            </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="<?= APP_URL ?>/search"
                               class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-lg md:px-10 transition duration-150 ease-in-out">
                                Browse Craftsmen
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- =========================================================
         HERO IMAGE — SLIDESHOW MODE
         To switch to static: replace this entire block with:
             <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
                 <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full"
                      src="<?= $staticPhotoUrl ?>" alt="Professional craftsman at work">
             </div>
         And delete the <script> block at the bottom of this file.
    ========================================================= -->
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 relative" id="hero-slideshow"
         onmouseenter="pauseSlideshow()" onmouseleave="resumeSlideshow()">

        <!-- The images — all stacked, only the active one is visible -->
        <?php foreach ($slideshowUrls as $i => $photo): ?>
        <img id="slide-<?= $i ?>"
             class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full absolute inset-0 transition-opacity duration-700 ease-in-out"
             style="opacity: <?= $i === 0 ? '1' : '0' ?>; z-index: <?= $i === 0 ? '1' : '0' ?>;"
             src="<?= $photo['url'] ?>"
             alt="<?= htmlspecialchars($photo['alt']) ?>"
             loading="<?= $i === 0 ? 'eager' : 'lazy' ?>">
        <?php endforeach; ?>

        <!-- Bottom control bar — arrows + dots all in one row, always inside the image -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-10 flex items-center space-x-3">

            <!-- Left arrow -->
            <button onclick="moveSlide(-1)"
                    class="bg-black bg-opacity-30 hover:bg-opacity-50 text-white rounded-full w-8 h-8 flex items-center justify-center transition-all duration-200 focus:outline-none flex-shrink-0"
                    aria-label="Previous photo">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <!-- Dot indicators — one per photo, active dot is full white -->
            <?php foreach ($slideshowUrls as $i => $photo): ?>
            <button onclick="goToSlide(<?= $i ?>)"
                    id="dot-<?= $i ?>"
                    class="w-2 h-2 rounded-full transition-all duration-300 focus:outline-none <?= $i === 0 ? 'bg-white scale-125' : 'bg-white bg-opacity-50' ?>"
                    aria-label="Go to photo <?= $i + 1 ?>">
            </button>
            <?php endforeach; ?>

            <!-- Right arrow -->
            <button onclick="moveSlide(1)"
                    class="bg-black bg-opacity-30 hover:bg-opacity-50 text-white rounded-full w-8 h-8 flex items-center justify-center transition-all duration-200 focus:outline-none flex-shrink-0"
                    aria-label="Next photo">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

        </div>
    </div>
    <!-- END HERO IMAGE SLIDESHOW BLOCK -->

</div>


<!-- ===================================================================
     SERVICE CATEGORIES GRID
     Each card links to /search?category=... using the exact same category
     values stored in craftsmen_profiles.service_category
     To add/remove a category: edit the $categories array in this section.
     Make sure any new category name matches exactly what's in the DB.
=================================================================== -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center mb-10">
            <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Browse by Trade</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                What do you need done?
            </p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            <?php
            // Each entry: [label, search URL value, SVG path d="...", bg color classes]
            // URL value must match exactly what's stored in craftsmen_profiles.service_category
            $serviceCategories = [
                [
                    'label' => 'Plumbing',
                    'value' => 'Plumbing',
                    'bg'    => 'bg-blue-50 hover:bg-blue-100 border-blue-100',
                    'icon'  => 'bg-blue-100 text-blue-600',
                    'svg'   => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4',
                ],
                [
                    'label' => 'Electrical',
                    'value' => 'Electrical',
                    'bg'    => 'bg-yellow-50 hover:bg-yellow-100 border-yellow-100',
                    'icon'  => 'bg-yellow-100 text-yellow-600',
                    'svg'   => 'M13 10V3L4 14h7v7l9-11h-7z',
                ],
                [
                    'label' => 'Carpentry',
                    'value' => 'Carpentry',
                    'bg'    => 'bg-orange-50 hover:bg-orange-100 border-orange-100',
                    'icon'  => 'bg-orange-100 text-orange-600',
                    'svg'   => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z',
                ],
                [
                    'label' => 'Painting',
                    'value' => 'Painting',
                    'bg'    => 'bg-pink-50 hover:bg-pink-100 border-pink-100',
                    'icon'  => 'bg-pink-100 text-pink-600',
                    'svg'   => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
                ],
                [
                    'label' => 'Roofing',
                    'value' => 'Roofing',
                    'bg'    => 'bg-stone-50 hover:bg-stone-100 border-stone-100',
                    'icon'  => 'bg-stone-100 text-stone-600',
                    'svg'   => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                ],
                [
                    'label' => 'HVAC',
                    'value' => 'HVAC',
                    'bg'    => 'bg-cyan-50 hover:bg-cyan-100 border-cyan-100',
                    'icon'  => 'bg-cyan-100 text-cyan-600',
                    'svg'   => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2h-2',
                ],
                [
                    'label' => 'Tiling',
                    'value' => 'Tiling',
                    'bg'    => 'bg-teal-50 hover:bg-teal-100 border-teal-100',
                    'icon'  => 'bg-teal-100 text-teal-600',
                    'svg'   => 'M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z',
                ],
                [
                    'label' => 'Landscaping',
                    'value' => 'Landscaping',
                    'bg'    => 'bg-green-50 hover:bg-green-100 border-green-100',
                    'icon'  => 'bg-green-100 text-green-600',
                    'svg'   => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
                ],
                [
                    'label' => 'General Handyman',
                    'value' => 'General Handyman',
                    'bg'    => 'bg-indigo-50 hover:bg-indigo-100 border-indigo-100',
                    'icon'  => 'bg-indigo-100 text-indigo-600',
                    'svg'   => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                ],
            ];
            ?>
            <?php foreach ($serviceCategories as $cat): ?>
            <a href="<?= APP_URL ?>/search?category=<?= urlencode($cat['value']) ?>"
               class="flex flex-col items-center p-5 rounded-xl border transition-all duration-200 cursor-pointer group <?= $cat['bg'] ?>">
                <div class="flex items-center justify-center h-12 w-12 rounded-full mb-3 <?= $cat['icon'] ?>">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="<?= $cat['svg'] ?>"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-800 text-center group-hover:text-gray-900">
                    <?= $cat['label'] ?>
                </span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- END SERVICE CATEGORIES GRID -->


<!-- How It Works Section — tabbed: For Homeowners / For Craftsmen -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">How It Works</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                Get started in three simple steps
            </p>
            <p class="mt-3 text-gray-500 text-base max-w-xl mx-auto">
                Whether you need work done or are looking for new opportunities, we've made the process simple.
            </p>

            <!-- Tab Toggle -->
            <div class="mt-8 inline-flex items-center bg-gray-100 rounded-full p-1">
                <button id="tab-homeowner-btn"
                        onclick="switchHowTab('homeowner')"
                        class="hiw-tab flex items-center gap-2 px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200 bg-white text-gray-900 shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    For Homeowners
                </button>
                <button id="tab-craftsman-btn"
                        onclick="switchHowTab('craftsman')"
                        class="hiw-tab flex items-center gap-2 px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200 text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    For Craftsmen
                </button>
            </div>
        </div>

        <!-- Homeowners Tab Content -->
        <div id="hiw-homeowner" class="hiw-content">
            <div class="grid md:grid-cols-3 gap-8 relative">
                <!-- Connector line between steps (desktop only) -->
                <div class="hidden md:block absolute top-8 left-1/6 right-1/6 h-0.5 bg-indigo-100 z-0" style="left:16.67%; right:16.67%;"></div>

                <!-- Step 1 -->
                <div class="text-center px-4 relative z-10">
                    <div class="relative mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-600 text-white text-sm font-bold mb-5 shadow-lg">
                        <span class="absolute -top-1 -right-1 bg-white text-indigo-600 text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center border border-indigo-100 shadow-sm">01</span>
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Describe Your Project</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Tell us what you need — a quick repair, renovation, or a full build. Takes less than a minute.</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center px-4 relative z-10">
                    <div class="relative mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-600 text-white text-sm font-bold mb-5 shadow-lg">
                        <span class="absolute -top-1 -right-1 bg-white text-indigo-600 text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center border border-indigo-100 shadow-sm">02</span>
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Get Matched</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Browse verified professionals, compare reviews and rates, and pick the right person for your job.</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center px-4 relative z-10">
                    <div class="relative mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-600 text-white text-sm font-bold mb-5 shadow-lg">
                        <span class="absolute -top-1 -right-1 bg-white text-indigo-600 text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center border border-indigo-100 shadow-sm">03</span>
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Get It Done</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Hire your craftsman, track the work, and pay securely once you're fully satisfied with the result.</p>
                </div>
            </div>
        </div>

        <!-- Craftsmen Tab Content (hidden by default) -->
        <div id="hiw-craftsman" class="hiw-content hidden">
            <div class="grid md:grid-cols-3 gap-8 relative">
                <div class="hidden md:block absolute top-8 h-0.5 bg-indigo-100 z-0" style="left:16.67%; right:16.67%;"></div>

                <!-- Step 1 -->
                <div class="text-center px-4 relative z-10">
                    <div class="relative mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-600 text-white text-sm font-bold mb-5 shadow-lg">
                        <span class="absolute -top-1 -right-1 bg-white text-indigo-600 text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center border border-indigo-100 shadow-sm">01</span>
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Build Your Profile</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Showcase your skills, upload portfolio photos, set your hourly rate, and get verified to stand out.</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center px-4 relative z-10">
                    <div class="relative mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-600 text-white text-sm font-bold mb-5 shadow-lg">
                        <span class="absolute -top-1 -right-1 bg-white text-indigo-600 text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center border border-indigo-100 shadow-sm">02</span>
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Receive Leads</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Get booking requests and job leads directly from homeowners who need your exact skills. No cold outreach.</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center px-4 relative z-10">
                    <div class="relative mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-600 text-white text-sm font-bold mb-5 shadow-lg">
                        <span class="absolute -top-1 -right-1 bg-white text-indigo-600 text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center border border-indigo-100 shadow-sm">03</span>
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Build Your Reputation</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Complete jobs, collect 5-star reviews, and grow your client base — every review makes your profile stronger.</p>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- END HOW IT WORKS -->

<!-- Features Section -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
            <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Why Crafts?</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                A better way to hire professionals
            </p>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                We take the hassle out of finding reliable help for your home improvements.
            </p>
        </div>

        <div class="mt-10">
            <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                <!-- Feature 1 -->
                <div class="relative">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Verified Professionals</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-gray-500">
                        Every craftsman goes through a strict identity and background check before they can offer services on our platform.
                    </dd>
                </div>

                <!-- Feature 2 -->
                <div class="relative">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Secure Payments</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-gray-500">
                        Funds are held securely and only released when you are 100% satisfied with the completed work.
                    </dd>
                </div>

                <!-- Feature 3 -->
                <div class="relative">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                            </svg>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Direct Communication</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-gray-500">
                        Chat directly with professionals, share photos, and discuss project details all within our secure platform.
                    </dd>
                </div>

                <!-- Feature 4 -->
                <div class="relative">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Transparent Reviews</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-gray-500">
                        Read real, verified reviews from other homeowners to ensure you hire the best person for the job.
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>


<!-- ===================================================================
     STATS BAR — live numbers pulled from the DB via HomeController
     Data variables: $stats['craftsmen'], $stats['completed_bookings'],
                     $stats['wilayas'], $stats['avg_rating']
     If the DB returns 0 for everything, you have no data yet — that is normal
     on a fresh install. Add a few users and jobs to see real numbers.
=================================================================== -->
<div class="bg-gray-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 gap-6 md:grid-cols-4 text-center">

            <!-- Stat 1: Craftsmen -->
            <div>
                <p class="text-3xl font-extrabold text-indigo-600">
                    <?= ($stats['craftsmen'] ?? 0) > 0 ? number_format($stats['craftsmen']) . '+' : '—' ?>
                </p>
                <p class="mt-1 text-sm font-medium text-gray-500">Skilled Craftsmen</p>
            </div>

            <!-- Stat 2: Wilayas covered -->
            <div>
                <p class="text-3xl font-extrabold text-indigo-600">
                    <?= ($stats['wilayas'] ?? 0) > 0 ? $stats['wilayas'] : '—' ?>
                </p>
                <p class="mt-1 text-sm font-medium text-gray-500">Wilayas Covered</p>
            </div>

            <!-- Stat 3: Completed jobs -->
            <div>
                <p class="text-3xl font-extrabold text-indigo-600">
                    <?= ($stats['completed_bookings'] ?? 0) > 0 ? number_format($stats['completed_bookings']) . '+' : '—' ?>
                </p>
                <p class="mt-1 text-sm font-medium text-gray-500">Jobs Completed</p>
            </div>

            <!-- Stat 4: Average rating -->
            <div>
                <p class="text-3xl font-extrabold text-indigo-600">
                    <?= ($stats['avg_rating'] ?? 0) > 0 ? number_format($stats['avg_rating'], 1) . '★' : '—' ?>
                </p>
                <p class="mt-1 text-sm font-medium text-gray-500">Average Rating</p>
            </div>

        </div>
    </div>
</div>
<!-- END STATS BAR -->



<!-- ===================================================================
     SPLIT CTA — tabbed: For Homeowners / For Craftsmen
     Same tab pattern as the How It Works section above.
=================================================================== -->
<div class="bg-gray-900 py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto text-center">

        <h2 class="text-3xl font-extrabold text-white sm:text-4xl">Ready to get started?</h2>
        <p class="mt-3 text-gray-400 text-base">Join the platform connecting skilled craftsmen with homeowners across Algeria.</p>

        <!-- Tab Toggle -->
        <div class="mt-8 inline-flex items-center bg-gray-800 rounded-full p-1">
            <button id="cta-homeowner-btn"
                    onclick="switchCtaTab('homeowner')"
                    class="cta-tab flex items-center gap-2 px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200 bg-white text-gray-900 shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                For Homeowners
            </button>
            <button id="cta-craftsman-btn"
                    onclick="switchCtaTab('craftsman')"
                    class="cta-tab flex items-center gap-2 px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                For Craftsmen
            </button>
        </div>

        <!-- Homeowners Panel -->
        <div id="cta-homeowner" class="cta-content mt-10">
            <p class="text-gray-300 text-base mb-8 max-w-xl mx-auto">
                Post your project, receive quotes from verified craftsmen, and hire the best one — all in one place.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="<?= APP_URL ?>/register"
                   class="inline-flex items-center justify-center px-7 py-3 text-base font-semibold rounded-lg text-gray-900 bg-white hover:bg-gray-100 transition duration-150">
                    Sign Up Free
                </a>
                <a href="<?= APP_URL ?>/search"
                   class="inline-flex items-center justify-center px-7 py-3 text-base font-semibold rounded-lg text-white border border-gray-600 hover:bg-gray-800 transition duration-150">
                    Browse Craftsmen
                </a>
            </div>
        </div>

        <!-- Craftsmen Panel (hidden by default) -->
        <div id="cta-craftsman" class="cta-content hidden mt-10">
            <p class="text-gray-300 text-base mb-8 max-w-xl mx-auto">
                Create your profile, showcase your work, and get booking requests from homeowners who need your exact skills — for free.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="<?= APP_URL ?>/register"
                   class="inline-flex items-center justify-center px-7 py-3 text-base font-semibold rounded-lg text-indigo-900 bg-indigo-300 hover:bg-indigo-200 transition duration-150">
                    Join as a Craftsman
                </a>
                <a href="<?= APP_URL ?>/jobs"
                   class="inline-flex items-center justify-center px-7 py-3 text-base font-semibold rounded-lg text-white border border-indigo-600 hover:bg-indigo-900 transition duration-150">
                    Browse Job Board
                </a>
            </div>
        </div>

    </div>
</div>

<script>
function switchCtaTab(tab) {
    document.querySelectorAll('.cta-content').forEach(function(el) {
        el.classList.add('hidden');
    });
    document.getElementById('cta-' + tab).classList.remove('hidden');

    ['homeowner', 'craftsman'].forEach(function(t) {
        var btn = document.getElementById('cta-' + t + '-btn');
        btn.classList.remove('bg-white', 'text-gray-900', 'shadow');
        btn.classList.add('text-gray-400');
    });
    var activeBtn = document.getElementById('cta-' + tab + '-btn');
    activeBtn.classList.add('bg-white', 'text-gray-900', 'shadow');
    activeBtn.classList.remove('text-gray-400');
}
</script>
<!-- END SPLIT CTA -->

<!-- How It Works tab switcher script -->
<script>
function switchHowTab(tab) {
    // Hide all content panels
    document.querySelectorAll('.hiw-content').forEach(function(el) {
        el.classList.add('hidden');
    });
    // Show selected panel
    document.getElementById('hiw-' + tab).classList.remove('hidden');

    // Reset both buttons to inactive style
    ['homeowner', 'craftsman'].forEach(function(t) {
        var btn = document.getElementById('tab-' + t + '-btn');
        btn.classList.remove('bg-white', 'text-gray-900', 'shadow');
        btn.classList.add('text-gray-500');
    });
    // Set active button style
    var activeBtn = document.getElementById('tab-' + tab + '-btn');
    activeBtn.classList.add('bg-white', 'text-gray-900', 'shadow');
    activeBtn.classList.remove('text-gray-500');
}
</script>

<!-- =============================================================================
     SLIDESHOW SCRIPT
     Delete this entire <script> block if you switch back to static mode.
     It controls: auto-advance, arrow buttons, dot indicators, pause on hover.
============================================================================= -->
<script>
(function () {
    // Total number of slides — must match the count of photos in $heroPhotos above
    var total   = <?= count($slideshowUrls) ?>;
    var current = 0;          // index of the currently visible slide
    var timer   = null;       // holds the setInterval reference so we can pause it
    var DELAY   = 4000;       // milliseconds between auto-advances (4 seconds)

    // Show the slide at index `n`, hide all others, update dots
    function showSlide(n) {
        // Clamp n to valid range using modulo (wraps around at both ends)
        current = (n + total) % total;

        for (var i = 0; i < total; i++) {
            var img = document.getElementById('slide-' + i);
            var dot = document.getElementById('dot-'   + i);

            if (i === current) {
                // Active slide: visible on top
                img.style.opacity  = '1';
                img.style.zIndex   = '1';
                // Active dot: full white and slightly larger
                dot.className = dot.className
                    .replace('bg-opacity-50', '')
                    .replace('scale-125', '');
                dot.classList.add('bg-white', 'scale-125');
            } else {
                // Inactive slides: hidden behind
                img.style.opacity  = '0';
                img.style.zIndex   = '0';
                // Inactive dot: semi-transparent, normal size
                dot.className = dot.className
                    .replace('scale-125', '');
                dot.classList.add('bg-opacity-50');
                dot.classList.remove('scale-125');
            }
        }
    }

    // Move forward (+1) or backward (-1) by one slide
    window.moveSlide = function (direction) {
        showSlide(current + direction);
        resetTimer(); // restart the 4-second timer after a manual click
    };

    // Jump directly to a specific slide (used by dot buttons)
    window.goToSlide = function (index) {
        showSlide(index);
        resetTimer();
    };

    // Stop the auto-advance timer (called on mouseenter)
    window.pauseSlideshow = function () {
        clearInterval(timer);
    };

    // Restart the auto-advance timer (called on mouseleave)
    window.resumeSlideshow = function () {
        resetTimer();
    };

    // Clear existing timer and start a fresh one
    function resetTimer() {
        clearInterval(timer);
        timer = setInterval(function () {
            showSlide(current + 1);
        }, DELAY);
    }

    // Start the slideshow automatically when the page loads
    resetTimer();
})();
</script>
<!-- END SLIDESHOW SCRIPT -->