<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\JobPosting;
use App\Models\JobQuote;
use App\Models\Message;
use App\Models\Notification;
use App\Auth\Middleware;

class JobBoardController extends Controller
{
    /**
     * Show the public job board (list of all open jobs) with pagination.
     */
    public function index()
    {
        $jobModel = new JobPosting();
 
        $filters = [
            'category' => $_GET['category'] ?? null,
            'search'   => $_GET['q']        ?? null,
            'wilaya'   => $_GET['wilaya']   ?? null,
            'sort'     => $_GET['sort']     ?? null
        ];
 
        $perPage    = 12;
        $page       = max(1, (int)($_GET['page'] ?? 1));
        $offset     = ($page - 1) * $perPage;
        $totalJobs  = $jobModel->countOpenJobs($filters);
        $totalPages = (int)ceil($totalJobs / $perPage);
        $jobs       = $jobModel->getOpenJobs($filters, $perPage, $offset);
 
        $this->view('layouts/app', [
            'pageTitle'       => 'Browse Jobs - Crafts',
            'contentView'     => 'jobboard/index',
            'jobs'            => $jobs,
            'filters'         => $filters,
            'page'            => $page,
            'totalPages'      => $totalPages,
            'totalJobs'       => $totalJobs,
            'metaDescription' => 'Browse available jobs and projects posted by homeowners on Crafts. Find your next gig today.',
            'ogTitle'         => 'Browse Jobs on Crafts',
            'ogDescription'   => 'Browse available jobs and projects posted by homeowners on Crafts. Find your next gig today.'
        ]);
    }
 

    /**
     * Show the form to post a new job.
     */
    public function create()
    {
        Middleware::requireLogin();

        if (isset($_SESSION['role']) && $_SESSION['role'] !== 'homeowner') {
            header("Location: " . APP_URL . "/jobs");
            exit;
        }

        $this->view('layouts/app', [
            'pageTitle'   => 'Post a Job - Crafts',
            'contentView' => 'jobboard/create'
        ]);
    }

    /**
     * Process the new job submission.
     */
    public function store()
    {
        Middleware::requireLogin();
        Middleware::verifyCsrfToken();

        if (isset($_SESSION['role']) && $_SESSION['role'] !== 'homeowner') {
            header("Location: " . APP_URL . "/jobs");
            exit;
        }

        $title       = $_POST['title']       ?? '';
        $category    = $_POST['category']    ?? '';
        $description = $_POST['description'] ?? '';
        $address     = $_POST['address']     ?? '';
        $budget      = $_POST['budget']      ?? null;

        if (empty($title) || empty($category) || empty($description) || empty($address)) {
            $this->view('layouts/app', [
                'pageTitle'   => 'Post a Job - Crafts',
                'contentView' => 'jobboard/create',
                'error'       => 'Please fill in all required fields.'
            ]);
            return;
        }

        $jobModel = new JobPosting();
        $success = $jobModel->create([
            'posted_by_user_id' => $_SESSION['user_id'],
            'service_category'  => $category,
            'title'             => $title,
            'description'       => $description,
            'address'           => $address,
            'budget_range'      => $budget
        ]);

        if ($success) {
            $dashboard = $_SESSION['role'] === 'craftsman' ? '/craftsman/dashboard' : '/homeowner/dashboard';
            header("Location: " . APP_URL . $dashboard . "?success=job_posted");
            exit;
        } else {
            $this->view('layouts/app', [
                'pageTitle'   => 'Post a Job - Crafts',
                'contentView' => 'jobboard/create',
                'error'       => 'Failed to post the job. Please try again.'
            ]);
        }
    }

    /**
     * Show a single job's details.
     */
    public function show($id = null)
    {
        // Legacy redirect support for old /jobs/show?id=X URLs
        if ($id === 'show' && isset($_GET['id'])) {
            $legacyId    = $_GET['id'];
            $query       = $_GET;
            unset($query['id'], $query['route']);
            $queryString = !empty($query) ? '?' . http_build_query($query) : '';
            header("Location: " . APP_URL . "/jobs/" . $legacyId . $queryString, true, 301);
            exit;
        }

        if (!$id || $id === 'show') {
            echo "Job not found.";
            exit;
        }

        $jobModel = new JobPosting();
        $job      = $jobModel->findById($id);

        if (!$job) {
            echo "Job not found.";
            exit;
        }

        $quoteModel = new JobQuote();
        $quotes     = $quoteModel->getQuotesByJob($id);

        // Check if the current craftsman has already quoted and get their status
        $alreadyQuoted  = false;
        $myQuoteStatus  = null;

        if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'craftsman') {
            $alreadyQuoted = $quoteModel->hasAlreadyQuoted($id, $_SESSION['user_id']);
            if ($alreadyQuoted) {
                $myQuote       = $quoteModel->getCraftsmanQuoteForJob($id, $_SESSION['user_id']);
                $myQuoteStatus = $myQuote['status'] ?? null;
            }
        }

        $ogTitle  = htmlspecialchars($job['title']) . ' - Crafts Job Board';
        $metaDesc = "View the job '{$job['title']}' in the {$job['service_category']} category. " .
                    ($job['budget_range'] ? "Budget: {$job['budget_range']} DZD. " : "") .
                    "Location: {$job['address']}. Apply now on Crafts.";

        $this->view('layouts/app', [
            'pageTitle'       => $job['title'] . ' - Crafts',
            'contentView'     => 'jobboard/show',
            'job'             => $job,
            'quotes'          => $quotes,
            'alreadyQuoted'   => $alreadyQuoted,
            'myQuoteStatus'   => $myQuoteStatus,
            'metaDescription' => $metaDesc,
            'ogTitle'         => $ogTitle,
            'ogDescription'   => $metaDesc
        ]);
    }

    /**
     * Process a craftsman's quote submission on a job.
     */
    public function submitQuote()
    {
        Middleware::requireRole('craftsman');
        Middleware::verifyCsrfToken();

        $jobId   = $_POST['job_posting_id'] ?? null;
        $price   = $_POST['quoted_price']   ?? '';
        $message = $_POST['cover_message']  ?? '';

        if (!$jobId || empty($price)) {
            header("Location: " . APP_URL . "/jobs/" . $jobId . "?error=price_required");
            exit;
        }

        $jobModel = new JobPosting();
        $job      = $jobModel->findById($jobId);

        if (!$job || $job['status'] !== 'open') {
            header("Location: " . APP_URL . "/jobs");
            exit;
        }

        if ($job['posted_by_user_id'] == $_SESSION['user_id']) {
            header("Location: " . APP_URL . "/jobs/" . $jobId . "?error=own_job");
            exit;
        }

        $quoteModel = new JobQuote();
        if ($quoteModel->hasAlreadyQuoted($jobId, $_SESSION['user_id'])) {
            header("Location: " . APP_URL . "/jobs/" . $jobId . "?error=already_quoted");
            exit;
        }

        $success = $quoteModel->create([
            'job_posting_id' => $jobId,
            'craftsman_id'   => $_SESSION['user_id'],
            'quoted_price'   => $price,
            'cover_message'  => $message
        ]);

        if ($success) {
            $notif = new Notification();
            $notif->send(
                $job['posted_by_user_id'],
                'quote_new',
                'New Quote Received',
                $_SESSION['name'] . ' submitted a quote of ' . number_format($price, 0) . ' DZD on your job: ' . $job['title'],
                APP_URL . '/homeowner/dashboard#quotes'
            );
            header("Location: " . APP_URL . "/jobs/" . $jobId . "?success=quote_submitted");
        } else {
            header("Location: " . APP_URL . "/jobs/" . $jobId . "?error=submit_failed");
        }
        exit;
    }

    /**
     * Homeowner accepts a craftsman's quote.
     */
    public function acceptQuote()
    {
        Middleware::requireLogin();
        Middleware::verifyCsrfToken();

        $quoteId = $_POST['quote_id'] ?? null;
        if (!$quoteId) {
            header("Location: " . APP_URL . "/jobs");
            exit;
        }

        $quoteModel = new JobQuote();
        $quote      = $quoteModel->findById($quoteId);
        if (!$quote) {
            header("Location: " . APP_URL . "/jobs");
            exit;
        }

        $jobModel = new JobPosting();
        $job      = $jobModel->findById($quote['job_posting_id']);
        if (!$job || $job['posted_by_user_id'] != $_SESSION['user_id']) {
            echo "Access Denied: You can only accept quotes on your own jobs.";
            exit;
        }

        $success = $quoteModel->acceptQuote($quoteId);

        if ($success) {
            $msgModel = new Message();
            $msgModel->autoPromoteOnBooking($_SESSION['user_id'], $quote['craftsman_id']);

            $notif = new Notification();
            $notif->send(
                $quote['craftsman_id'],
                'quote_accepted',
                'Quote Accepted!',
                'Your quote on "' . $job['title'] . '" has been accepted!',
                APP_URL . '/craftsman/dashboard#active'
            );
            header("Location: " . APP_URL . "/homeowner/dashboard?success=quote_accepted#quotes");
        } else {
            header("Location: " . APP_URL . "/homeowner/dashboard?error=accept_failed#quotes");
        }
        exit;
    }

    /**
     * Homeowner rejects/declines a single craftsman's quote.
     */
    public function rejectQuote()
    {
        Middleware::requireLogin();
        Middleware::verifyCsrfToken();

        $quoteId = $_POST['quote_id'] ?? null;
        if (!$quoteId) {
            header("Location: " . APP_URL . "/jobs");
            exit;
        }

        $quoteModel = new JobQuote();
        $quote      = $quoteModel->findById($quoteId);
        if (!$quote) {
            header("Location: " . APP_URL . "/jobs");
            exit;
        }

        $jobModel = new JobPosting();
        $job      = $jobModel->findById($quote['job_posting_id']);
        if (!$job || $job['posted_by_user_id'] != $_SESSION['user_id']) {
            echo "Access Denied: You can only reject quotes on your own jobs.";
            exit;
        }

        $quoteModel->rejectQuote($quoteId);

        $notif = new Notification();
        $notif->send(
            $quote['craftsman_id'],
            'quote_rejected',
            'Quote Declined',
            'Your quote on "' . $job['title'] . '" was not accepted.',
            APP_URL . '/craftsman/dashboard#quotes'
        );

        header("Location: " . APP_URL . "/homeowner/dashboard?success=quote_rejected#quotes");
        exit;
    }
}
