<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Message;
use App\Models\Notification;
use App\Models\JobQuote;
use App\Models\Booking;
use App\Models\JobPosting;
use App\Models\Favorite;

class ApiController extends Controller
{
    /**
     * Centralized real-time polling endpoint
     * Returns exactly the data needed for badges and nav dropdowns
     * depending on the authenticated user's role.
     */
    public function poll()
    {
        header('Content-Type: application/json');

        if (empty($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Not authenticated']);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'] ?? 'homeowner';

        // 1. Global Navigation Data
        $msgModel = new Message();
        $unreadMessages = $msgModel->getUnreadConversationCount($userId);

        $notifModel = new Notification();
        $unreadNotifications = $notifModel->getUnreadCount($userId);
        
        // Fetch last 3 notifications for the dropdown
        $recentNotifs = $notifModel->getForUser($userId, 3);

        // Compute time_ago and sanitize link for each notification
        foreach ($recentNotifs as &$n) {
            $n['time_ago'] = $this->timeAgo($n['created_at'] ?? '');
            $n['link'] = $n['link'] ?? '/notifications';
        }
        unset($n);

        // Prepare the response
        $response = [
            'unread_messages' => $unreadMessages,
            'unread_notifications' => $unreadNotifications,
            'recent_notifications' => $recentNotifs,
            'role' => $role,
            'dashboard' => []
        ];

        // 2. Role-specific Dashboard Tabs Data
        if ($role === 'homeowner') {
            $quoteModel = new JobQuote();
            $allQuotes = $quoteModel->getQuotesForHomeowner($userId);
            
            $pendingQuotes = count(array_filter($allQuotes, fn($q) => $q['quote_status'] === 'pending'));

            $jobModel = new JobPosting();
            $myJobs = $jobModel->getJobsByUser($userId);
            
            $openJobs = count(array_filter($myJobs, fn($j) => $j['status'] === 'open'));

            $bookingModel = new Booking();
            $myBookings = $bookingModel->getBookingsForHomeowner($userId);
            
            $activeBookings = count(array_filter($myBookings, fn($b) => in_array($b['status'], ['requested', 'in_progress', 'counter_offered'])));

            $favModel = new Favorite();
            $favs = $favModel->getFavoritesForHomeowner($userId);

            $response['dashboard'] = [
                'pending_quotes' => $pendingQuotes,
                'open_jobs'      => $openJobs,
                'active_bookings'=> $activeBookings,
                'saved'          => count($favs)
            ];

        } elseif ($role === 'craftsman') {
            $quoteModel = new JobQuote();
            $myQuotes = $quoteModel->getQuotesByCraftsman($userId);

            $pendingBids = 0;
            $activeJobs  = 0;
            foreach ($myQuotes as $q) {
                if ($q['status'] === 'pending') $pendingBids++;
                if ($q['status'] === 'accepted') $activeJobs++;
            }

            $bookingModel = new Booking();
            $receivedBookings = $bookingModel->getBookingsForCraftsman($userId);
            $pendingBookings = count(array_filter($receivedBookings, fn($b) => $b['status'] === 'requested'));

            $sentBookings = $bookingModel->getBookingsForHomeowner($userId);

            $favModel = new Favorite();
            $favs = $favModel->getFavoritesForHomeowner($userId);

            $response['dashboard'] = [
                'pending_bids'     => $pendingBids,
                'active_jobs'      => $activeJobs,
                'pending_bookings' => $pendingBookings,
                'sent_bookings'    => count($sentBookings),
                'saved'            => count($favs)
            ];
        }

        echo json_encode($response);
        exit;
    }

    /**
     * Compute a human-readable relative time string.
     */
    private function timeAgo(string $datetime): string
    {
        if (empty($datetime)) return '';
        $diff = time() - strtotime($datetime);
        if ($diff < 60)      return 'Just now';
        if ($diff < 3600)    return floor($diff / 60) . 'm ago';
        if ($diff < 86400)   return floor($diff / 3600) . 'h ago';
        if ($diff < 604800)  return floor($diff / 86400) . 'd ago';
        return date('M d', strtotime($datetime));
    }
}
