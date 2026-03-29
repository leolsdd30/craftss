<?php
/*
|=============================================================================
| HOME PAGE — resources/views/public/home.php
| Merged & refined version.
| Local images from /assets/img/
| Session key: $_SESSION['name'] (set by AuthController).
| CTA inactive tab uses inline style instead of text-white/70 class.
|=============================================================================
*/
?>

<!-- HERO SECTION — Split layout -->
<div class="relative bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative lg:grid lg:grid-cols-2 min-h-[600px] lg:min-h-[680px]">

            <!-- LEFT — Text -->
            <div class="relative z-10 flex flex-col justify-center px-6 sm:px-10 lg:px-12 py-16 lg:py-20 bg-white">
                <div class="flex items-center gap-2.5 mb-7">
                    <span class="flex items-center gap-2 bg-indigo-50 text-indigo-700 text-xs font-bold tracking-widest uppercase px-4 py-2 rounded-full">
                        <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-pulse"></span>
                        Algeria's Craftsman Platform
                    </span>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-[1.05] tracking-tight mb-6">
                    Find the right professional for your
                    <span class="text-indigo-600 italic font-light"> home projects.</span>
                </h1>
                <p class="text-gray-500 text-lg leading-relaxed mb-10 max-w-lg">
                    Connect with top-rated, verified craftsmen across Algeria. From plumbing to carpentry — get it done right, on time, every time.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 mb-12">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <a href="<?= APP_URL ?>/register" class="inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 text-base">
                            Get Started Free <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="<?= APP_URL ?>/search" class="inline-flex items-center justify-center px-7 py-3.5 bg-indigo-50 text-indigo-700 font-bold rounded-xl hover:bg-indigo-100 transition text-base">Browse Craftsmen</a>
                    <?php elseif ($_SESSION['role'] === 'homeowner'): ?>
                        <a href="<?= APP_URL ?>/search" class="inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg text-base">Find a Craftsman <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
                        <a href="<?= APP_URL ?>/homeowner/dashboard" class="inline-flex items-center justify-center px-7 py-3.5 bg-indigo-50 text-indigo-700 font-bold rounded-xl hover:bg-indigo-100 transition text-base">My Dashboard</a>
                    <?php elseif ($_SESSION['role'] === 'craftsman'): ?>
                        <a href="<?= APP_URL ?>/jobs" class="inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg text-base">Browse Jobs <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
                        <a href="<?= APP_URL ?>/craftsman/dashboard" class="inline-flex items-center justify-center px-7 py-3.5 bg-indigo-50 text-indigo-700 font-bold rounded-xl hover:bg-indigo-100 transition text-base">My Dashboard</a>
                    <?php elseif ($_SESSION['role'] === 'admin'): ?>
                        <a href="<?= APP_URL ?>/admin/dashboard" class="inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg text-base">Admin Dashboard</a>
                    <?php endif; ?>
                </div>

                <!-- Trust badges strip -->
                <div class="flex flex-wrap items-center gap-5 pt-6 border-t border-gray-100">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-600">Verified Professionals</span>
                    </div>
                    <div class="w-px h-5 bg-gray-200 hidden sm:block"></div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-600">Rated <?= ($stats['avg_rating'] ?? 0) > 0 ? number_format($stats['avg_rating'],1) : '4.8' ?>/5 by Clients</span>
                    </div>
                    <div class="w-px h-5 bg-gray-200 hidden sm:block"></div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span class="text-xs font-semibold text-gray-600"><?= ($stats['wilayas'] ?? 0) > 0 ? $stats['wilayas'] : '20+' ?> Wilayas Covered</span>
                    </div>
                </div>
            </div>

            <!-- RIGHT — Craftsman photo (local) -->
            <div class="hidden lg:block relative">
                <div class="absolute inset-0 overflow-hidden" style="clip-path: polygon(10% 0, 100% 0, 100% 100%, 0% 100%)">
                    <img src="<?= APP_URL ?>/assets/img/hero.webp"
                         alt="Professional craftsman working on a home renovation project"
                         class="w-full h-full object-cover object-center">
                    <div class="absolute inset-0" style="background: linear-gradient(to right, white 0%, transparent 18%)"></div>
                    <div class="absolute bottom-0 left-0 right-0 h-16" style="background: linear-gradient(to top, white 0%, transparent 100%)"></div>
                </div>
                <div class="absolute bottom-10 left-4 bg-white rounded-2xl shadow-xl px-5 py-4 flex items-center gap-3 border border-indigo-50 z-10">
                    <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-medium">Every Professional</p>
                        <p class="text-sm font-extrabold text-gray-900">Verified & Background Checked</p>
                    </div>
                </div>
                <div class="absolute top-10 right-6 bg-white rounded-2xl shadow-xl px-4 py-3.5 z-10 border border-indigo-50">
                    <div class="flex items-center gap-0.5 mb-1">
                        <?php for ($i = 0; $i < 5; $i++): ?><svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><?php endfor; ?>
                    </div>
                    <p class="text-xs font-extrabold text-gray-900"><?= ($stats['avg_rating'] ?? 0) > 0 ? number_format($stats['avg_rating'],1) : '4.8' ?> / 5.0</p>
                    <p class="text-xs text-gray-400 font-medium"><?= ($stats['completed_bookings'] ?? 0) > 0 ? number_format($stats['completed_bookings']).'+ jobs done' : 'Client satisfaction' ?></p>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- SPECIALIST DIRECTORY -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-10">
            <div>
                <p class="text-indigo-600 text-xs font-bold tracking-widest uppercase mb-2">Browse by Trade</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
                    The Specialist <span class="text-indigo-600 italic font-light">Directory</span>
                </h2>
                <p class="mt-2 text-gray-500 text-sm max-w-md">A curated selection of the region's finest professionals, categorised by their domain of excellence.</p>
            </div>
            <a href="<?= APP_URL ?>/search" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-800 whitespace-nowrap self-start sm:self-auto border-b-2 border-indigo-100 pb-1 hover:border-indigo-400 transition-colors">
                View all professionals <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <?php
        $allDisciplines = [
            ['cat'=>'Plumbing',    'num'=>'01', 'img'=>'Plumbing.webp',    'grad'=>'rgba(30,58,138,0.85)'],
            ['cat'=>'Electrical',  'num'=>'02', 'img'=>'Electrical.webp',  'grad'=>'rgba(113,63,18,0.85)'],
            ['cat'=>'Carpentry',   'num'=>'03', 'img'=>'Carpentry.webp',   'grad'=>'rgba(124,45,18,0.85)'],
            ['cat'=>'Painting',    'num'=>'04', 'img'=>'Painting.webp',    'grad'=>'rgba(131,24,67,0.85)'],
            ['cat'=>'Roofing',     'num'=>'05', 'img'=>'Roofing.webp',     'grad'=>'rgba(68,64,60,0.85)'],
            ['cat'=>'Landscaping', 'num'=>'06', 'img'=>'Landscaping.webp', 'grad'=>'rgba(20,83,45,0.85)'],
            ['cat'=>'HVAC',        'num'=>'07', 'img'=>'HVAC.webp',        'grad'=>'rgba(21,94,117,0.85)'],
            ['cat'=>'Tiling',      'num'=>'08', 'img'=>'Tiling.webp',      'grad'=>'rgba(17,94,89,0.85)'],
        ];
        ?>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-5">
            <?php foreach (array_slice($allDisciplines, 0, 4) as $d): ?>
            <a href="<?= APP_URL ?>/search?category=<?= urlencode($d['cat']) ?>" class="group relative overflow-hidden rounded-2xl block" style="aspect-ratio:3/4">
                <img src="<?= APP_URL ?>/assets/img/<?= $d['img'] ?>" alt="<?= $d['cat'] ?>" loading="lazy" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                <div class="absolute inset-0 transition-opacity duration-300 opacity-90 group-hover:opacity-100" style="background:linear-gradient(to top, <?= $d['grad'] ?> 0%, rgba(0,0,0,0.1) 50%, transparent 100%)"></div>
                <div class="absolute bottom-0 left-0 p-5 sm:p-6"><span class="block text-[9px] tracking-[0.25em] uppercase font-bold mb-1" style="color:rgba(255,255,255,0.5)">Discipline <?= $d['num'] ?></span><h3 class="text-white text-xl sm:text-2xl font-extrabold tracking-tight"><?= $d['cat'] ?></h3></div>
                <div class="absolute top-3.5 right-3.5 w-8 h-8 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300" style="background:rgba(255,255,255,0.2)"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></div>
            </a>
            <?php endforeach; ?>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-5 mt-4 md:mt-5">
            <?php foreach (array_slice($allDisciplines, 4, 4) as $d): ?>
            <a href="<?= APP_URL ?>/search?category=<?= urlencode($d['cat']) ?>" class="group relative overflow-hidden rounded-2xl block" style="aspect-ratio:3/4">
                <img src="<?= APP_URL ?>/assets/img/<?= $d['img'] ?>" alt="<?= $d['cat'] ?>" loading="lazy" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                <div class="absolute inset-0 transition-opacity duration-300 opacity-90 group-hover:opacity-100" style="background:linear-gradient(to top, <?= $d['grad'] ?> 0%, rgba(0,0,0,0.1) 50%, transparent 100%)"></div>
                <div class="absolute bottom-0 left-0 p-5 sm:p-6"><span class="block text-[9px] tracking-[0.25em] uppercase font-bold mb-1" style="color:rgba(255,255,255,0.5)">Discipline <?= $d['num'] ?></span><h3 class="text-white text-xl sm:text-2xl font-extrabold tracking-tight"><?= $d['cat'] ?></h3></div>
                <div class="absolute top-3.5 right-3.5 w-8 h-8 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300" style="background:rgba(255,255,255,0.2)"><svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></div>
            </a>
            <?php endforeach; ?>
        </div>

    </div>
</div>


<!-- HOW IT WORKS -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">How It Works</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">Get started in three simple steps</p>
            <p class="mt-3 text-gray-500 text-base max-w-xl mx-auto">Whether you need work done or are looking for new opportunities, we've made the process simple.</p>
        </div>
        <div class="flex justify-center mb-10">
            <div class="inline-flex bg-gray-100 rounded-xl p-1 gap-1">
                <button id="tab-homeowner-btn" onclick="switchHowTab('homeowner')" class="px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200 bg-white text-gray-900 shadow">For Homeowners</button>
                <button id="tab-craftsman-btn" onclick="switchHowTab('craftsman')" class="px-5 py-2 rounded-lg text-sm font-semibold transition-all duration-200 text-gray-500">For Craftsmen</button>
            </div>
        </div>

        <?php
        $hiwSteps = [
            'homeowner' => [
                ['n'=>'01','icon'=>'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z','t'=>'Find a Craftsman','d'=>'Browse verified professionals by category, read reviews, view portfolios, and compare rates to find the right match.'],
                ['n'=>'02','icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z','t'=>'Book & Confirm','d'=>'Send a booking request with your project details. The craftsman confirms and you agree on price, date, and scope.'],
                ['n'=>'03','icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','t'=>'Get It Done','d'=>'Hire your craftsman, track the work, and pay securely once you\'re fully satisfied with the result.'],
            ],
            'craftsman' => [
                ['n'=>'01','icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z','t'=>'Build Your Profile','d'=>'Showcase your skills, upload portfolio photos, set your hourly rate, and get verified to stand out.'],
                ['n'=>'02','icon'=>'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9','t'=>'Receive Leads','d'=>'Get booking requests and job leads directly from homeowners who need your exact skills. No cold outreach.'],
                ['n'=>'03','icon'=>'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z','t'=>'Build Your Reputation','d'=>'Complete jobs, collect 5-star reviews, and grow your client base — every review makes your profile stronger.'],
            ],
        ];
        foreach ($hiwSteps as $tab => $steps): ?>
        <div id="hiw-<?= $tab ?>" class="hiw-content <?= $tab === 'craftsman' ? 'hidden' : '' ?>">
            <div class="grid md:grid-cols-3 gap-8 relative">
                <div class="hidden md:block absolute top-8 h-0.5 bg-indigo-100 z-0" style="left:16.67%; right:16.67%;"></div>
                <?php foreach ($steps as $s): ?>
                <div class="text-center px-4 relative z-10">
                    <div class="relative mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-600 text-white mb-5 shadow-lg">
                        <span class="absolute -top-1 -right-1 bg-white text-indigo-600 text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center border border-indigo-100 shadow-sm"><?= $s['n'] ?></span>
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="<?= $s['icon'] ?>"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2"><?= $s['t'] ?></h3>
                    <p class="text-gray-500 text-sm leading-relaxed"><?= $s['d'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>


<!-- WHY CRAFTS — Image mosaic + feature rows -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-14 items-center">
            <div class="relative hidden md:block">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-4 pt-10">
                        <div class="rounded-2xl overflow-hidden shadow-md" style="height:230px">
                            <img src="<?= APP_URL ?>/assets/img/why-5.webp" alt="Craftsman at work" loading="lazy" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="rounded-2xl overflow-hidden shadow-md" style="height:175px">
                            <img src="<?= APP_URL ?>/assets/img/why-6.webp" alt="Home renovation" loading="lazy" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="rounded-2xl overflow-hidden shadow-md" style="height:175px">
                            <img src="<?= APP_URL ?>/assets/img/why-1.webp" alt="Professional work" loading="lazy" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="rounded-2xl overflow-hidden shadow-md" style="height:230px">
                            <img src="<?= APP_URL ?>/assets/img/why-3.webp" alt="Quality craftsmanship" loading="lazy" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        </div>
                    </div>
                </div>
                <div class="absolute -bottom-8 -left-8 w-48 h-48 bg-indigo-50 rounded-full blur-3xl -z-10"></div>
                <div class="absolute -top-4 -right-4 w-32 h-32 bg-indigo-100 rounded-full blur-2xl -z-10 opacity-50"></div>
            </div>
            <div>
                <p class="text-indigo-600 text-xs font-bold tracking-widest uppercase mb-3">Why Crafts?</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight mb-4">A better way to hire <span class="text-indigo-600 italic font-light">professionals</span></h2>
                <p class="text-gray-500 text-base mb-10 max-w-lg">We take the hassle out of finding reliable help for your home improvements.</p>
                <div class="space-y-7">
                    <?php
                    $features = [
                        ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','t'=>'Verified Professionals','d'=>'Every craftsman on our platform goes through a verification process so you can hire with confidence.'],
                        ['icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z','t'=>'Transparent Pricing','d'=>'See hourly rates upfront, request quotes before committing, and only pay when you\'re satisfied.'],
                        ['icon'=>'M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z','t'=>'Direct Communication','d'=>'Chat directly with professionals, share photos, and discuss project details all within our secure platform.'],
                        ['icon'=>'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z','t'=>'Transparent Reviews','d'=>'Read real, verified reviews from other homeowners to ensure you hire the best person for the job.'],
                    ];
                    foreach ($features as $f): ?>
                    <div class="flex gap-5">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center"><svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $f['icon'] ?>"/></svg></div>
                        <div><h4 class="text-base font-bold text-gray-900 mb-1"><?= $f['t'] ?></h4><p class="text-gray-500 text-sm leading-relaxed"><?= $f['d'] ?></p></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- STATS BAR -->
<div class="pb-10 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-0">
                <?php
                $statItems = [
                    ['val' => ($stats['craftsmen'] ?? 0) > 0 ? number_format($stats['craftsmen']) . '+' : '—', 'label' => 'Skilled Craftsmen', 'bg' => 'bg-indigo-50 text-indigo-600', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'fill' => false, 'border' => 'border-b border-r border-gray-100 md:border-b-0'],
                    ['val' => ($stats['wilayas'] ?? 0) > 0 ? $stats['wilayas'] : '—', 'label' => 'Wilayas Covered', 'bg' => 'bg-green-50 text-green-600', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z', 'fill' => false, 'border' => 'border-b border-gray-100 md:border-b-0 md:border-r'],
                    ['val' => ($stats['completed_bookings'] ?? 0) > 0 ? number_format($stats['completed_bookings']) . '+' : '—', 'label' => 'Jobs Completed', 'bg' => 'bg-yellow-50 text-yellow-600', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'fill' => false, 'border' => 'border-r border-gray-100'],
                    ['val' => ($stats['avg_rating'] ?? 0) > 0 ? number_format($stats['avg_rating'], 1) . '★' : '—', 'label' => 'Average Rating', 'bg' => 'bg-orange-50 text-orange-500', 'icon' => 'M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z', 'fill' => true, 'border' => ''],
                ];
                foreach ($statItems as $si): ?>
                <div class="flex items-center justify-center gap-4 py-5 px-4 <?= $si['border'] ?>">
                    <div class="p-3 <?= $si['bg'] ?> rounded-xl hidden sm:block flex-shrink-0"><svg class="w-7 h-7" <?= $si['fill'] ? 'fill="currentColor"' : 'fill="none" stroke="currentColor"' ?> viewBox="0 0 <?= $si['fill'] ? '20 20' : '24 24' ?>"><?php if (!$si['fill']): ?><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $si['icon'] ?>"/><?php else: ?><path d="<?= $si['icon'] ?>"/><?php endif; ?></svg></div>
                    <div class="text-center sm:text-left">
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900"><?= $si['val'] ?></p>
                        <p class="mt-1 text-xs sm:text-sm font-medium text-gray-500"><?= $si['label'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>


<!-- CTA SECTION -->
<?php if (!isset($_SESSION['user_id'])): ?>
<div class="bg-gray-50 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="relative overflow-hidden rounded-3xl px-8 py-16 sm:px-16 sm:py-24 text-center" style="background: linear-gradient(135deg, #3730a3 0%, #4f46e5 50%, #6366f1 100%);">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full translate-y-1/2 -translate-x-1/2 blur-2xl pointer-events-none" style="background:rgba(129,140,248,0.15)"></div>
            <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 24px 24px;"></div>
            <div class="relative z-10 max-w-3xl mx-auto">
                <h2 class="text-3xl sm:text-5xl font-extrabold text-white mb-4 tracking-tight">Ready to get started?</h2>
                <p class="text-indigo-200 text-lg mb-10 mx-auto">Join the platform connecting skilled craftsmen with homeowners across Algeria.</p>
                <div style="display:flex; justify-content:center; margin-bottom:2.5rem;">
                    <div style="display:inline-flex; background:rgba(255,255,255,0.12); border-radius:9999px; padding:4px; gap:4px;">
                        <button id="cta-homeowner-btn" onclick="switchCtaTab('homeowner')"
                                style="display:flex; align-items:center; gap:6px; padding:10px 16px; border-radius:9999px; font-size:0.8125rem; font-weight:600; background:#fff; color:#111827; box-shadow:0 1px 3px rgba(0,0,0,0.15); border:none; cursor:pointer; transition:all 0.2s; white-space:nowrap;">
                            <svg style="width:14px;height:14px;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            For Homeowners
                        </button>
                        <button id="cta-craftsman-btn" onclick="switchCtaTab('craftsman')"
                                style="display:flex; align-items:center; gap:6px; padding:10px 16px; border-radius:9999px; font-size:0.8125rem; font-weight:600; background:transparent; color:rgba(199,210,254,0.9); border:none; cursor:pointer; transition:all 0.2s; white-space:nowrap;">
                            <svg style="width:14px;height:14px;flex-shrink:0;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            For Craftsmen
                        </button>
                    </div>
                </div>
                <div id="cta-homeowner" class="cta-content">
                    <p class="text-indigo-200 text-base mb-8 mx-auto">Post your project, receive quotes from verified craftsmen, and hire the best one — all in one place.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="<?= APP_URL ?>/register" class="inline-flex items-center justify-center px-8 py-3.5 bg-white text-indigo-700 font-bold rounded-xl hover:bg-indigo-50 transition shadow-lg text-base">Sign Up Free</a>
                        <a href="<?= APP_URL ?>/search" class="inline-flex items-center justify-center px-8 py-3.5 border text-white font-bold rounded-xl transition text-base" style="border-color:rgba(255,255,255,0.3)" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background=''">Browse Craftsmen</a>
                    </div>
                </div>
                <div id="cta-craftsman" class="cta-content hidden">
                    <p class="text-indigo-200 text-base mb-8 mx-auto">Create your profile, showcase your work, and get booking requests from homeowners — for free.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="<?= APP_URL ?>/register" class="inline-flex items-center justify-center px-8 py-3.5 bg-white text-indigo-700 font-bold rounded-xl hover:bg-indigo-50 transition shadow-lg text-base">Join as a Craftsman</a>
                        <a href="<?= APP_URL ?>/jobs" class="inline-flex items-center justify-center px-8 py-3.5 border text-white font-bold rounded-xl transition text-base" style="border-color:rgba(255,255,255,0.3)" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background=''">Browse Job Board</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<div class="bg-gray-50 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="relative overflow-hidden rounded-3xl px-8 py-16 sm:px-16 sm:py-24 text-center" style="background: linear-gradient(135deg, #3730a3 0%, #4f46e5 50%, #6366f1 100%);">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl pointer-events-none"></div>
            <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 24px 24px;"></div>
            <div class="relative z-10 max-w-3xl mx-auto">
                <h2 class="text-3xl sm:text-5xl font-extrabold text-white mb-4 tracking-tight">Welcome back, <?= htmlspecialchars($_SESSION['name'] ?? 'there') ?>!</h2>
                <?php if ($_SESSION['role'] === 'homeowner'): ?>
                    <p class="text-indigo-200 text-lg mb-10 max-w-xl mx-auto">Ready to tackle your next home project? Find the right professional or check your ongoing jobs.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="<?= APP_URL ?>/search" class="inline-flex items-center justify-center px-8 py-3.5 bg-white text-indigo-700 font-bold rounded-xl hover:bg-indigo-50 transition shadow-lg text-base">Find a Craftsman</a>
                        <a href="<?= APP_URL ?>/homeowner/dashboard" class="inline-flex items-center justify-center px-8 py-3.5 border text-white font-bold rounded-xl transition text-base" style="border-color:rgba(255,255,255,0.3)" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background=''">My Dashboard</a>
                    </div>
                <?php elseif ($_SESSION['role'] === 'craftsman'): ?>
                    <p class="text-indigo-200 text-lg mb-10 max-w-xl mx-auto">Check your latest booking requests, browse the job board, and keep growing your reputation.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="<?= APP_URL ?>/jobs" class="inline-flex items-center justify-center px-8 py-3.5 bg-white text-indigo-700 font-bold rounded-xl hover:bg-indigo-50 transition shadow-lg text-base">Browse Jobs</a>
                        <a href="<?= APP_URL ?>/craftsman/dashboard" class="inline-flex items-center justify-center px-8 py-3.5 border text-white font-bold rounded-xl transition text-base" style="border-color:rgba(255,255,255,0.3)" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background=''">My Dashboard</a>
                    </div>
                <?php elseif ($_SESSION['role'] === 'admin'): ?>
                    <p class="text-indigo-200 text-lg mb-10 max-w-xl mx-auto">Manage the platform, verify craftsmen, and keep everything running smoothly.</p>
                    <div class="flex justify-center">
                        <a href="<?= APP_URL ?>/admin/dashboard" class="inline-flex items-center justify-center px-8 py-3.5 bg-white text-indigo-700 font-bold rounded-xl hover:bg-indigo-50 transition shadow-lg text-base">Go to Admin Dashboard</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- JAVASCRIPT -->
<script>
function switchHowTab(tab) {
    document.querySelectorAll('.hiw-content').forEach(function(el) { el.classList.add('hidden'); });
    document.getElementById('hiw-' + tab).classList.remove('hidden');
    ['homeowner', 'craftsman'].forEach(function(t) {
        var btn = document.getElementById('tab-' + t + '-btn');
        btn.classList.remove('bg-white', 'text-gray-900', 'shadow');
        btn.classList.add('text-gray-500');
    });
    var activeBtn = document.getElementById('tab-' + tab + '-btn');
    activeBtn.classList.add('bg-white', 'text-gray-900', 'shadow');
    activeBtn.classList.remove('text-gray-500');
}

function switchCtaTab(tab) {
    document.querySelectorAll('.cta-content').forEach(function(el) { el.classList.add('hidden'); });
    document.getElementById('cta-' + tab).classList.remove('hidden');
    ['homeowner', 'craftsman'].forEach(function(t) {
        var btn = document.getElementById('cta-' + t + '-btn');
        btn.style.background = 'transparent';
        btn.style.color = 'rgba(199,210,254,0.9)';
        btn.style.boxShadow = 'none';
    });
    var activeBtn = document.getElementById('cta-' + tab + '-btn');
    activeBtn.style.background = '#fff';
    activeBtn.style.color = '#111827';
    activeBtn.style.boxShadow = '0 1px 3px rgba(0,0,0,0.15)';
}
</script>