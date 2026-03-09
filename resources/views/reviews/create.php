<!-- Write a Review -->
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <a href="<?= APP_URL ?>/homeowner/dashboard" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors duration-200">&larr; Back to Dashboard</a>
            <h1 class="mt-2 text-3xl font-extrabold text-gray-900">Write a Review</h1>
            <p class="mt-1 text-sm text-gray-500">Share your experience with this craftsman to help other homeowners.</p>
        </div>

        <!-- Craftsman Preview Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 mb-6 flex items-center space-x-4">
            <img class="h-14 w-14 rounded-full object-cover border-2 border-indigo-200" 
                 src="<?= get_profile_picture_url($craftsman['profile_picture'] ?? 'default.png', $craftsman['first_name'], $craftsman['last_name']) ?>" 
                 alt="<?= htmlspecialchars($craftsman['first_name']) ?>">
            <div>
                <h2 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($craftsman['first_name'] . ' ' . $craftsman['last_name']) ?></h2>
                <?php if (!empty($craftsman['wilaya'])): ?>
                <p class="text-sm text-gray-500"><?= htmlspecialchars($craftsman['wilaya']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($error)): ?>
        <div class="rounded-md bg-red-50 p-4 mb-6">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <p class="ml-3 text-sm font-medium text-red-800"><?= htmlspecialchars($error) ?></p>
            </div>
        </div>
        <?php endif; ?>

        <form action="<?= APP_URL ?>/reviews/create" method="POST" class="bg-white shadow rounded-lg">
            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="booking_id" value="<?= $bookingId ?>">
            <input type="hidden" name="star_rating" id="star_rating_input" value="0">

            <div class="px-6 py-6 space-y-6">

                <!-- Star Rating -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Rating <span class="text-red-500">*</span></label>
                    <div class="flex items-center space-x-1" id="star-container">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <button type="button" onclick="setRating(<?= $i ?>)" data-star="<?= $i ?>"
                            class="star-btn p-1 rounded-full hover:scale-110 transition-transform duration-150 focus:outline-none">
                            <svg class="h-10 w-10 text-gray-300 transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </button>
                        <?php endfor; ?>
                        <span id="rating-text" class="ml-3 text-sm font-medium text-gray-500">Click to rate</span>
                    </div>
                </div>

                <!-- Comment -->
                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-700">Your Review <span class="text-gray-400">(optional)</span></label>
                    <textarea name="comment" id="comment" rows="4" placeholder="Tell others about your experience working with this craftsman..."
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm px-4 py-2 border"></textarea>
                    <p class="mt-1 text-xs text-gray-400">Helpful reviews mention quality of work, professionalism, and communication.</p>
                </div>

            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex items-center justify-end space-x-3">
                <a href="<?= APP_URL ?>/homeowner/dashboard" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500 transition-colors duration-200">Cancel</a>
                <button type="submit" id="submit-btn" disabled
                    class="inline-flex justify-center items-center px-6 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-400 cursor-not-allowed transition duration-150">
                    <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    Submit Review
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Star Rating Script -->
<script>
var ratingLabels = ['', 'Terrible', 'Poor', 'Average', 'Good', 'Excellent'];

function setRating(stars) {
    document.getElementById('star_rating_input').value = stars;
    document.getElementById('rating-text').textContent = ratingLabels[stars] + ' (' + stars + '/5)';

    // Update star colors
    document.querySelectorAll('.star-btn svg').forEach(function(svg, index) {
        if (index < stars) {
            svg.classList.remove('text-gray-300');
            svg.classList.add('text-yellow-400');
        } else {
            svg.classList.remove('text-yellow-400');
            svg.classList.add('text-gray-300');
        }
    });

    // Enable submit button
    var btn = document.getElementById('submit-btn');
    btn.disabled = false;
    btn.classList.remove('bg-indigo-400', 'cursor-not-allowed');
    btn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
}
</script>
