<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Auth\Middleware;
use App\Models\Review;
use App\Models\User;
use App\Models\Notification;

class ReviewController extends Controller
{
    /**
     * Show the review form for a completed booking.
     */
    public function create($bookingId = null)
    {
        Middleware::requireLogin();
        
        // We only allow homeowners to write reviews now
        Middleware::requireRole('homeowner');

        if (!$bookingId) {
            header("Location: " . APP_URL . "/homeowner/dashboard");
            exit;
        }

        $bookingModel = new \App\Models\Booking();
        $booking = $bookingModel->findById($bookingId);

        // Can only review completed bookings belonging to this homeowner
        if (!$booking || (int)$booking['homeowner_id'] !== (int)$_SESSION['user_id'] || $booking['status'] !== 'completed') {
            header("Location: " . APP_URL . "/homeowner/dashboard");
            exit;
        }

        // Check if already reviewed for this specific booking
        $reviewModel = new Review();
        if ($reviewModel->hasReviewed($_SESSION['user_id'], $booking['craftsman_id'], $bookingId)) {
            header("Location: " . APP_URL . "/homeowner/dashboard?info=already_reviewed");
            exit;
        }

        // Load UI
        $this->renderReviewForm($booking['craftsman_id'], $bookingId);
    }

    /**
     * Store the review.
     */
    public function store()
    {
        Middleware::requireLogin();
        Middleware::requireRole('homeowner');
        Middleware::verifyCsrfToken();

        $bookingId = $_POST['booking_id'] ?? null;
        $starRating = (int) ($_POST['star_rating'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');

        if (!$bookingId) {
            header("Location: " . APP_URL . "/homeowner/dashboard");
            exit;
        }

        $bookingModel = new \App\Models\Booking();
        $booking = $bookingModel->findById($bookingId);

        if (!$booking || (int)$booking['homeowner_id'] !== (int)$_SESSION['user_id'] || $booking['status'] !== 'completed') {
            header("Location: " . APP_URL . "/homeowner/dashboard");
            exit;
        }

        $validator = new \App\Services\Validator();
        if (!$validator->validate($_POST, [
            'star_rating' => 'required',
            'comment'     => 'required|min:5|max:1500'
        ])) {
            $this->renderReviewForm($booking['craftsman_id'], $bookingId, $validator->getFirstError());
            return;
        }

        if ($starRating < 1 || $starRating > 5) {
            $this->renderReviewForm($booking['craftsman_id'], $bookingId, 'Please select a star rating (1-5).');
            return;
        }

        $reviewModel = new Review();

        // Prevent duplicate reviews for this specific booking
        if ($reviewModel->hasReviewed($_SESSION['user_id'], $booking['craftsman_id'], $bookingId)) {
            header("Location: " . APP_URL . "/homeowner/dashboard?info=already_reviewed");
            exit;
        }

        $success = $reviewModel->create([
            'booking_id' => $bookingId,
            'homeowner_id' => $_SESSION['user_id'],
            'craftsman_id' => $booking['craftsman_id'],
            'star_rating' => $starRating,
            'comment' => $comment
        ]);

        if ($success) {
            // Notify the craftsman about the new review
            $notif = new Notification();
            
            $notif->send($booking['craftsman_id'], 'review_new', 'New Review Received', 
                $_SESSION['name'] . ' left you a ' . $starRating . '-star review!', 
                APP_URL . '/craftsman/dashboard#reviews');

            header("Location: " . APP_URL . "/homeowner/dashboard?success=review_submitted#bookings");
        } else {
            header("Location: " . APP_URL . "/homeowner/dashboard?error=review_failed#bookings");
        }
        exit;
    }

    /**
     * Helper method to render the review form and avoid code duplication.
     */
    private function renderReviewForm($craftsmanId, $bookingId, $error = null)
    {
        $userModel = new \App\Models\User();
        $craftsman = $userModel->findById($craftsmanId);

        $this->view('layouts/app', [
            'pageTitle'   => 'Write a Review - Crafts',
            'contentView' => 'reviews/create',
            'craftsman'   => $craftsman,
            'bookingId'   => $bookingId,
            'error'       => $error
        ]);
    }
}
