<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Auth\Middleware;
use App\Models\Booking;
use App\Models\User;
use App\Models\Message;
use App\Models\Notification;

class BookingController extends Controller
{
    /**
     * Show the booking request form (from a craftsman's profile).
     */
    public function create()
    {
        Middleware::requireLogin();

        $craftsmanId = $_GET['craftsman_id'] ?? null;

        if (!$craftsmanId) {
            header("Location: " . APP_URL . "/search");
            exit;
        }

        // Cannot book yourself
        if ($craftsmanId == $_SESSION['user_id']) {
            header("Location: " . APP_URL . "/profile?id=" . $craftsmanId);
            exit;
        }

        $userModel = new User();
        $craftsman = $userModel->findById($craftsmanId);

        if (!$craftsman || $craftsman['role'] !== 'craftsman') {
            echo "Craftsman not found.";
            exit;
        }

        $this->view('layouts/app', [
            'pageTitle' => 'Request Booking - CraftConnect',
            'contentView' => 'bookings/create',
            'craftsman' => $craftsman
        ]);
    }

    /**
     * Process the booking request form submission.
     */
    public function store()
    {
        Middleware::requireLogin();
        Middleware::verifyCsrfToken();

        $craftsmanId = $_POST['craftsman_id'] ?? null;
        $description = trim($_POST['description'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $scheduledDate = $_POST['scheduled_date'] ?? '';

        // Basic validation
        if (empty($craftsmanId) || empty($description) || empty($address) || empty($scheduledDate)) {
            $userModel = new User();
            $craftsman = $userModel->findById($craftsmanId);

            $this->view('layouts/app', [
                'pageTitle' => 'Request Booking - CraftConnect',
                'contentView' => 'bookings/create',
                'craftsman' => $craftsman,
                'error' => 'Please fill in all required fields.'
            ]);
            return;
        }

        $bookingModel = new Booking();
        $success = $bookingModel->create([
            'homeowner_id' => $_SESSION['user_id'],
            'craftsman_id' => $craftsmanId,
            'description' => $description,
            'address' => $address,
            'scheduled_date' => $scheduledDate
        ]);

        if ($success) {
            // Auto-promote any pending message requests between these users
            $msgModel = new Message();
            $msgModel->autoPromoteOnBooking($_SESSION['user_id'], $craftsmanId);

            // Notify the craftsman
            $notif = new Notification();
            $notif->send($craftsmanId, 'booking_new', 'New Booking Request', 
                $_SESSION['name'] . ' has requested a booking with you.', 
                APP_URL . '/craftsman/dashboard');

            $dashboard = $_SESSION['role'] === 'craftsman' ? '/craftsman/dashboard' : '/homeowner/dashboard';
            header("Location: " . APP_URL . $dashboard . "?success=booking_requested");
            exit;
        } else {
            $userModel = new User();
            $craftsman = $userModel->findById($craftsmanId);

            $this->view('layouts/app', [
                'pageTitle' => 'Request Booking - CraftConnect',
                'contentView' => 'bookings/create',
                'craftsman' => $craftsman,
                'error' => 'Failed to submit booking request. Please try again.'
            ]);
        }
    }

    /**
     * Craftsman accepts a booking request — goes directly to in_progress.
     */
    public function accept()
    {
        Middleware::requireLogin();
        Middleware::verifyCsrfToken();

        $bookingId = $_POST['booking_id'] ?? null;

        if (!$bookingId) {
            header("Location: " . APP_URL . "/craftsman/dashboard");
            exit;
        }

        $bookingModel = new Booking();
        $booking = $bookingModel->findById($bookingId);

        if (!$booking || $booking['craftsman_id'] != $_SESSION['user_id']) {
            echo "Access Denied.";
            exit;
        }

        $bookingModel->updateStatus($bookingId, 'in_progress');

        // Auto-promote any pending message requests between these users
        $msgModel = new Message();
        $msgModel->autoPromoteOnBooking($booking['homeowner_id'], $booking['craftsman_id']);

        // Notify homeowner
        $notif = new Notification();
        $notif->send($booking['homeowner_id'], 'booking_accepted', 'Booking Accepted!', 
            'Your booking request has been accepted. The job is now in progress.', 
            APP_URL . '/homeowner/dashboard');

        header("Location: " . APP_URL . "/craftsman/dashboard?success=booking_accepted");
        exit;
    }

    /**
     * Craftsman sends a counter-offer with edited details.
     */
    public function counterOffer()
    {
        Middleware::requireLogin();
        Middleware::verifyCsrfToken();

        $bookingId = $_POST['booking_id'] ?? null;
        $counterDescription = trim($_POST['counter_description'] ?? '');
        $counterPrice = $_POST['counter_price'] ?? null;
        $counterDate = $_POST['counter_date'] ?? '';
        $counterNote = trim($_POST['counter_note'] ?? '');

        if (!$bookingId || empty($counterDescription) || empty($counterPrice) || empty($counterDate)) {
            header("Location: " . APP_URL . "/craftsman/dashboard?error=missing_fields");
            exit;
        }

        $bookingModel = new Booking();
        $booking = $bookingModel->findById($bookingId);

        if (!$booking || $booking['craftsman_id'] != $_SESSION['user_id'] || $booking['status'] !== 'requested') {
            echo "Access Denied.";
            exit;
        }

        $bookingModel->counterOffer($bookingId, [
            'counter_description' => $counterDescription,
            'counter_price' => $counterPrice,
            'counter_date' => $counterDate,
            'counter_note' => $counterNote
        ]);

        // Auto-promote messaging 
        $msgModel = new Message();
        $msgModel->autoPromoteOnBooking($booking['homeowner_id'], $booking['craftsman_id']);

        // Notify homeowner
        $notif = new Notification();
        $notif->send($booking['homeowner_id'], 'booking_counter', 'Counter-Offer Received', 
            $_SESSION['first_name'] . ' has sent a counter-offer for your booking. Please review the changes.', 
            APP_URL . '/homeowner/dashboard');

        header("Location: " . APP_URL . "/craftsman/dashboard?success=counter_sent");
        exit;
    }

    /**
     * Homeowner accepts the craftsman's counter-offer.
     */
    public function acceptCounter()
    {
        Middleware::requireLogin();
        Middleware::verifyCsrfToken();

        $bookingId = $_POST['booking_id'] ?? null;

        if (!$bookingId) {
            header("Location: " . APP_URL . "/homeowner/dashboard");
            exit;
        }

        $bookingModel = new Booking();
        $booking = $bookingModel->findById($bookingId);

        if (!$booking || $booking['homeowner_id'] != $_SESSION['user_id'] || $booking['status'] !== 'counter_offered') {
            echo "Access Denied.";
            exit;
        }

        $bookingModel->acceptCounterOffer($bookingId);

        // Notify craftsman
        $notif = new Notification();
        $notif->send($booking['craftsman_id'], 'counter_accepted', 'Counter-Offer Accepted!', 
            $_SESSION['first_name'] . ' accepted your counter-offer. The job is now in progress!', 
            APP_URL . '/craftsman/dashboard');

        header("Location: " . APP_URL . "/homeowner/dashboard?success=counter_accepted");
        exit;
    }

    /**
     * Homeowner rejects the counter-offer (cancels booking).
     */
    public function cancelCounter()
    {
        Middleware::requireLogin();
        Middleware::verifyCsrfToken();

        $bookingId = $_POST['booking_id'] ?? null;

        if (!$bookingId) {
            header("Location: " . APP_URL . "/homeowner/dashboard");
            exit;
        }

        $bookingModel = new Booking();
        $booking = $bookingModel->findById($bookingId);

        if (!$booking || $booking['homeowner_id'] != $_SESSION['user_id'] || $booking['status'] !== 'counter_offered') {
            echo "Access Denied.";
            exit;
        }

        $bookingModel->updateStatus($bookingId, 'cancelled');

        // Notify craftsman
        $notif = new Notification();
        $notif->send($booking['craftsman_id'], 'counter_rejected', 'Counter-Offer Declined', 
            $_SESSION['first_name'] . ' has declined your counter-offer. The booking has been cancelled.', 
            APP_URL . '/craftsman/dashboard');

        header("Location: " . APP_URL . "/homeowner/dashboard?success=counter_cancelled");
        exit;
    }

    /**
     * Craftsman declines a booking request.
     */
    public function decline()
    {
        Middleware::requireLogin();
        Middleware::verifyCsrfToken();

        $bookingId = $_POST['booking_id'] ?? null;

        if (!$bookingId) {
            header("Location: " . APP_URL . "/craftsman/dashboard");
            exit;
        }

        $bookingModel = new Booking();
        $booking = $bookingModel->findById($bookingId);

        if (!$booking || $booking['craftsman_id'] != $_SESSION['user_id']) {
            echo "Access Denied.";
            exit;
        }

        $bookingModel->updateStatus($bookingId, 'cancelled');

        // Notify homeowner
        $notif = new Notification();
        $notif->send($booking['homeowner_id'], 'booking_declined', 'Booking Declined', 
            'Unfortunately, your booking request was declined by the craftsman.', 
            APP_URL . '/homeowner/dashboard');

        header("Location: " . APP_URL . "/craftsman/dashboard?success=booking_declined");
        exit;
    }

    /**
     * Craftsman marks a booking as pending completion (waiting for homeowner).
     */
    public function complete()
    {
        Middleware::requireLogin();
        Middleware::verifyCsrfToken();

        $bookingId = $_POST['booking_id'] ?? null;

        if (!$bookingId) {
            header("Location: " . APP_URL . "/craftsman/dashboard");
            exit;
        }

        $bookingModel = new Booking();
        $booking = $bookingModel->findById($bookingId);

        if (!$booking || $booking['craftsman_id'] != $_SESSION['user_id']) {
            echo "Access Denied.";
            exit;
        }

        if ($booking['status'] === 'in_progress') {
            $bookingModel->markPendingCompletion($bookingId);

            // Notify homeowner
            $notif = new Notification();
            $notif->send($booking['homeowner_id'], 'booking_pending', 'Job Pending Confirmation', 
                'The craftsman has marked the job as complete. Please confirm the work is done.', 
                APP_URL . '/homeowner/dashboard');
        }

        header("Location: " . APP_URL . "/craftsman/dashboard?success=completion_pending");
        exit;
    }

    /**
     * Homeowner confirms the job is truly completed.
     */
    public function confirmCompletion()
    {
        Middleware::requireLogin();
        Middleware::verifyCsrfToken();

        $bookingId = $_POST['booking_id'] ?? null;

        if (!$bookingId) {
            header("Location: " . APP_URL . "/homeowner/dashboard");
            exit;
        }

        $bookingModel = new Booking();
        $booking = $bookingModel->findById($bookingId);

        if (!$booking || $booking['homeowner_id'] != $_SESSION['user_id'] || $booking['status'] !== 'pending_completion') {
            echo "Access Denied.";
            exit;
        }

        $bookingModel->confirmCompletion($bookingId);

        // Notify craftsman
        $notif = new Notification();
        $notif->send($booking['craftsman_id'], 'booking_completed', 'Job Confirmed Complete!', 
            $_SESSION['first_name'] . ' has confirmed the work is done. Great job!', 
            APP_URL . '/craftsman/dashboard');

        header("Location: " . APP_URL . "/homeowner/dashboard?success=job_completed");
        exit;
    }
}
