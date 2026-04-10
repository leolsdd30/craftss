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
            if (isset($n['message'])) {
                $msg = htmlspecialchars((string)$n['message'], ENT_QUOTES, 'UTF-8');
                
                // Bold the user's name if the message starts with it
                $userNamesSuffixes = [
                    ' sent you a booking request.',
                    ' has sent a counter-offer for your booking. Please review the changes.',
                    ' accepted your counter-offer. The job is now in progress!',
                    ' has declined your counter-offer. The booking has been cancelled.',
                    ' has confirmed the work is done. Great job!',
                    ' submitted a quote of ',
                    ' left you a ',
                    ' sent you a new message.',
                    ' wants to send you a message.'
                ];
                foreach ($userNamesSuffixes as $suffix) {
                    if (($pos = strpos($msg, $suffix)) !== false && $pos > 0 && $pos < 30) {
                        $name = substr($msg, 0, $pos);
                        $rest = substr($msg, $pos);
                        $msg = '<span class="font-bold text-gray-900 dark:text-white">' . $name . '</span>' . $rest;
                        break;
                    }
                }

                if (function_exists('__') && __('lang') === 'ar') {
                    $arMsg = [
                        ' sent you a booking request.' => ' أرسل لك طلب حجز.',
                        'Your booking request has been accepted. The job is now in progress.' => 'تم قبول طلب الحجز الخاص بك. العمل قيد التنفيذ الآن.',
                        ' has sent a counter-offer for your booking. Please review the changes.' => ' أرسل عرضًا مضادًا لحجزك. يرجى مراجعة التغييرات.',
                        ' accepted your counter-offer. The job is now in progress!' => ' قبل العرض المضاد. العمل قيد التنفيذ الآن!',
                        ' has declined your counter-offer. The booking has been cancelled.' => ' رفض العرض المضاد. تم إلغاء الحجز.',
                        'Unfortunately, your booking request was declined by the craftsman.' => 'للأسف، رفض الحرفي طلب الحجز الخاص بك.',
                        'The craftsman has marked the job as complete. Please confirm the work is done.' => 'حدد الحرفي الوظيفة كمكتملة. يرجى تأكيد إنجاز العمل.',
                        ' has confirmed the work is done. Great job!' => ' أكد أن العمل قد اكتمل. عمل رائع!',
                        ' submitted a quote of ' => ' قدم عرض سعر بقيمة ',
                        ' DZD on your job: ' => ' دينار جزائري على وظيفتك: ',
                        'Your quote on &quot;' => 'تم قبول عرض السعر الخاص بك على &quot;',
                        '&quot; has been accepted!' => '&quot;!',
                        '&quot; was not accepted.' => '&quot; لم يتم قبوله.',
                        ' left you a ' => ' ترك لك تقييم ',
                        '-star review!' => ' نجوم!',
                        ' sent you a new message.' => ' أرسل لك رسالة جديدة.',
                        ' wants to send you a message.' => ' يريد إرسال رسالة إليك.'
                    ];
                    $msg = str_replace(array_keys($arMsg), array_values($arMsg), $msg);
                }
                $n['message'] = $msg;
            }
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
        
        if (function_exists('__')) {
            if      ($diff < 60)     return __('time.just_now');
            elseif  ($diff < 3600)   return str_replace(':m', floor($diff / 60), __('time.m_ago'));
            elseif  ($diff < 86400)  return str_replace(':h', floor($diff / 3600), __('time.h_ago'));
            elseif  ($diff < 604800) return str_replace(':d', floor($diff / 86400), __('time.d_ago'));
            else                     return date('Y/m/d', strtotime($datetime));
        }

        if ($diff < 60)      return 'Just now';
        if ($diff < 3600)    return floor($diff / 60) . 'm ago';
        if ($diff < 86400)   return floor($diff / 3600) . 'h ago';
        if ($diff < 604800)  return floor($diff / 86400) . 'd ago';
        return date('M d', strtotime($datetime));
    }
}
