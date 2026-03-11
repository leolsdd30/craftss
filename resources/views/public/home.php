<?php
// Array of 10 verified high-quality professional trade photos from Unsplash
$heroPhotos = [
    ['id' => '1620653713380-7a34b773fef8', 'alt' => 'Professional plumber fixing a pipe with a wrench'], // The plumber photo
    ['id' => '1504307651254-35680f356dfd', 'alt' => 'Handyman working on a home project'],
    ['id' => '1749532125405-70950966b0e5', 'alt' => 'Electrician working on electrical panel'],
    ['id' => '1513694203232-719a280e022f', 'alt' => 'Professional painter painting a wall'],
    ['id' => '1621905251918-48416bd8575a', 'alt' => 'Carpenter measuring wood for a project'],
    ['id' => '1558618666-fcd25c85cd64', 'alt' => 'Mason laying bricks for a wall'],
    ['id' => '1574359411659-15573a27fd0c', 'alt' => 'General contractor reviewing building plans'],
    //['id' => '', 'alt' => 'Professional welder working on metal'],
    ['id' => '1589939705384-5185137a7f0f', 'alt' => 'Handyman using a power drill'],
    //['id' => '1534398079543-7ae6d016b86c', 'alt' => 'Carpenter framing a wooden wall']
];
$randomPhoto = $heroPhotos[array_rand($heroPhotos)];
$photoUrl = "https://images.unsplash.com/photo-{$randomPhoto['id']}?q=80&w=1545&auto=format&fit=crop";
?>

<!-- Hero Section -->
<div class="relative bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
            <!-- Diagonal shape for desktop -->
            <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                <polygon points="50,0 100,0 50,100 0,100" />
            </svg>

            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">Find the perfect professional for your</span>
                        <span class="block text-indigo-600 xl:inline">home projects</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        Connect with top-rated, verified craftsmen in your area. From plumbing to carpentry, get your home improvement done right with Crafts.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-md shadow">
                            <a href="<?= APP_URL ?>/register" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10 transition duration-150 ease-in-out">
                                Get Started
                            </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="<?= APP_URL ?>/search" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-lg md:px-10 transition duration-150 ease-in-out">
                                Browse Craftsmen
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- Hero Image -->
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
        <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="<?= $photoUrl ?>" alt="<?= $randomPhoto['alt'] ?>">
    </div>
</div>

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

<!-- How It Works Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center mb-12">
            <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">How It Works</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                Get started in three simple steps
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="text-center px-4">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 text-indigo-600 text-2xl font-bold mb-5">
                    1
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Describe Your Project</h3>
                <p class="text-gray-500">Tell us what you need — a quick repair, renovation, or a full build. It takes less than a minute.</p>
            </div>

            <!-- Step 2 -->
            <div class="text-center px-4">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 text-indigo-600 text-2xl font-bold mb-5">
                    2
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Get Matched</h3>
                <p class="text-gray-500">Browse verified professionals in your area, compare reviews, and pick the right person for the job.</p>
            </div>

            <!-- Step 3 -->
            <div class="text-center px-4">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-100 text-indigo-600 text-2xl font-bold mb-5">
                    3
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Get It Done</h3>
                <p class="text-gray-500">Hire your chosen craftsman, track the work, and pay securely once you're satisfied with the result.</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-indigo-600">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
        <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
            <span class="block">Ready to get started?</span>
            <span class="block text-indigo-200 text-lg font-medium mt-1">Join hundreds of homeowners finding trusted professionals every day.</span>
        </h2>
        <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0 gap-4">
            <a href="<?= APP_URL ?>/register" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 transition duration-150 ease-in-out">
                Sign Up Free
            </a>
            <a href="<?= APP_URL ?>/search" class="inline-flex items-center justify-center px-6 py-3 border border-indigo-300 text-base font-medium rounded-md text-white hover:bg-indigo-500 transition duration-150 ease-in-out">
                Browse Craftsmen
            </a>
        </div>
    </div>
</div>
