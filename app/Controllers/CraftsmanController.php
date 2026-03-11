<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Auth\Middleware;
use App\Models\JobQuote;
use App\Models\Booking;
use App\Models\Review;

class CraftsmanController extends Controller
{
    /**
     * Show the Craftsman Dashboard
     */
    public function dashboard()
    {
        // Enforce role restriction
        Middleware::requireRole('craftsman');

        $quoteModel = new JobQuote();
        $myQuotes = $quoteModel->getQuotesByCraftsman($_SESSION['user_id']);

        $activeBookings = 0;
        $pendingBids = 0;
        $submittedBids = count($myQuotes);
        $totalEarnings = 0; // Placeholder for future invoice/transaction feature

        foreach ($myQuotes as $quote) {
            if ($quote['status'] === 'accepted') {
                $activeBookings++;
                $totalEarnings += $quote['quoted_price'];
            } elseif ($quote['status'] === 'pending') {
                $pendingBids++;
            }
        }

        // Load booking requests for this craftsman
        $bookingModel = new Booking();
        $myBookings = $bookingModel->getBookingsForCraftsman($_SESSION['user_id']);
        $pendingBookings = 0;
        foreach ($myBookings as $b) {
            if ($b['status'] === 'requested') {
                $pendingBookings++;
            }
        }

        // Load reviews
        $reviewModel = new Review();
        $myReviews = $reviewModel->getReviewsForCraftsman($_SESSION['user_id']);
        $myRating = $reviewModel->getCraftsmanRating($_SESSION['user_id']);

        $this->view('layouts/app', [
            'pageTitle' => 'Craftsman Dashboard - Crafts',
            'contentView' => 'craftsman/dashboard',
            'quotes' => $myQuotes,
            'activeBookings' => $activeBookings,
            'submittedBids' => $submittedBids,
            'pendingBids' => $pendingBids,
            'totalEarnings' => $totalEarnings,
            'bookings' => $myBookings,
            'pendingBookings' => $pendingBookings,
            'reviews' => $myReviews,
            'rating' => $myRating
        ]);
    }
}
