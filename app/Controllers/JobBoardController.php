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
     * Show the public job board (list of all open jobs).
     */
    public function index()
    {
        $jobModel = new JobPosting();

        $filters = [
            'category' => $_GET['category'] ?? null,
            'search' => $_GET['q'] ?? null,
            'wilaya' => $_GET['wilaya'] ?? null,
            'sort' => $_GET['sort'] ?? null
        ];

        $jobs = $jobModel->getOpenJobs($filters);

        $this->view('layouts/app', [
            'pageTitle' => 'Browse Jobs - Crafts',
            'contentView' => 'jobboard/index',
            'jobs' => $jobs,
            'filters' => $filters,
            'metaDescription' => 'Browse available jobs and projects posted by homeowners on Crafts. Find your next gig today.',
            'ogTitle' => 'Browse Jobs on Crafts',
            'ogDescription' => 'Browse available jobs and projects posted by homeowners on Crafts. Find your next gig today.'
        ]);
    }

    /**
     * Show the form to post a new job.
     */
    public function create()
    {
        // Require the user to be logged in to post a job
        Middleware::requireLogin();

        $this->view('layouts/app', [
            'pageTitle' => 'Post a Job - Crafts',
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

        $title = $_POST['title'] ?? '';
        $category = $_POST['category'] ?? '';
        $description = $_POST['description'] ?? '';
        $address = $_POST['address'] ?? '';
        $budget = $_POST['budget'] ?? null;

        // Basic validation
        if (empty($title) || empty($category) || empty($description) || empty($address)) {
            // Re-render the form with an error (in a real app, you'd flash session data)
            $this->view('layouts/app', [
                'pageTitle' => 'Post a Job - Crafts',
                'contentView' => 'jobboard/create',
                'error' => 'Please fill in all required fields.'
            ]);
            return;
        }

        $jobModel = new JobPosting();
        $success = $jobModel->create([
            'posted_by_user_id' => $_SESSION['user_id'],
            'service_category' => $category,
            'title' => $title,
            'description' => $description,
            'address' => $address,
            'budget_range' => $budget
        ]);

        if ($success) {
            // Redirect to the user's dashboard after successful posting
            $dashboard = $_SESSION['role'] === 'craftsman' ? '/craftsman/dashboard' : '/homeowner/dashboard';
            header("Location: " . APP_URL . $dashboard . "?success=job_posted");
            exit;
        }
        else {
            $this->view('layouts/app', [
                'pageTitle' => 'Post a Job - Crafts',
                'contentView' => 'jobboard/create',
                'error' => 'Failed to post the job. Please try again.'
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
            $legacyId = $_GET['id'];
            // Rebuild query string excluding 'id' and 'route'
            $query = $_GET;
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
        $job = $jobModel->findById($id);

        if (!$job) {
            echo "Job not found.";
            exit;
        }

        // Load quotes for this job (visible to the job owner)
        $quoteModel = new JobQuote();
        $quotes = $quoteModel->getQuotesByJob($id);

        // Check if the current craftsman has already quoted
        $alreadyQuoted = false;
        if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'craftsman') {
            $alreadyQuoted = $quoteModel->hasAlreadyQuoted($id, $_SESSION['user_id']);
        }

        $ogTitle = htmlspecialchars($job['title']) . ' - Crafts Job Board';
        $metaDesc = "View the job '{$job['title']}' in the {$job['service_category']} category. " . 
                    ($job['budget_range'] ? "Budget: {$job['budget_range']} DZD. " : "") . 
                    "Location: {$job['address']}. Apply now on Crafts.";

        $this->view('layouts/app', [
            'pageTitle' => $job['title'] . ' - Crafts',
            'contentView' => 'jobboard/show',
            'job' => $job,
            'quotes' => $quotes,
            'alreadyQuoted' => $alreadyQuoted,
            'metaDescription' => $metaDesc,
            'ogTitle' => $ogTitle,
            'ogDescription' => $metaDesc
        ]);
    }

    /**
     * Process a craftsman's quote submission on a job.
     */
    public function submitQuote()
    {
        Middleware::requireRole('craftsman');
        Middleware::verifyCsrfToken();

        $jobId = $_POST['job_posting_id'] ?? null;
        $price = $_POST['quoted_price'] ?? '';
        $message = $_POST['cover_message'] ?? '';

        if (!$jobId || empty($price)) {
            header("Location: " . APP_URL . "/jobs/" . $jobId . "?error=price_required");
            exit;
        }

        // Validate: craftsman cannot bid on their own job
        $jobModel = new JobPosting();
        $job = $jobModel->findById($jobId);

        if (!$job || $job['status'] !== 'open') {
            header("Location: " . APP_URL . "/jobs");
            exit;
        }

        if ($job['posted_by_user_id'] == $_SESSION['user_id']) {
            header("Location: " . APP_URL . "/jobs/" . $jobId . "?error=own_job");
            exit;
        }

        // Validate: prevent duplicate quotes
        $quoteModel = new JobQuote();
        if ($quoteModel->hasAlreadyQuoted($jobId, $_SESSION['user_id'])) {
            header("Location: " . APP_URL . "/jobs/" . $jobId . "?error=already_quoted");
            exit;
        }

        $success = $quoteModel->create([
            'job_posting_id' => $jobId,
            'craftsman_id' => $_SESSION['user_id'],
            'quoted_price' => $price,
            'cover_message' => $message
        ]);

        if ($success) {
        // Notify the job poster about the new quote
        $notif = new Notification();
        $notif->send($job['posted_by_user_id'], 'quote_new', 'New Quote Received', 
            $_SESSION['name'] . ' submitted a quote of $' . number_format($price, 2) . ' on your job: ' . $job['title'], 
            APP_URL . '/homeowner/dashboard#quotes');

        header("Location: " . APP_URL . "/jobs/" . $jobId . "?success=quote_submitted");
        }
        else {
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
        $quote = $quoteModel->findById($quoteId);

        if (!$quote) {
            header("Location: " . APP_URL . "/jobs");
            exit;
        }

        // Verify the current user owns the job this quote belongs to
        $jobModel = new JobPosting();
        $job = $jobModel->findById($quote['job_posting_id']);

        if (!$job || $job['posted_by_user_id'] != $_SESSION['user_id']) {
            echo "Access Denied: You can only accept quotes on your own jobs.";
            exit;
        }

        $success = $quoteModel->acceptQuote($quoteId);

        if ($success) {
        // Auto-promote any pending message requests between homeowner and craftsman
        $msgModel = new Message();
        $msgModel->autoPromoteOnBooking($_SESSION['user_id'], $quote['craftsman_id']);

        // Notify the craftsman their quote was accepted
        $notif = new Notification();
        $notif->send($quote['craftsman_id'], 'quote_accepted', 'Quote Accepted!', 
            'Your quote on "' . $job['title'] . '" has been accepted!', 
            APP_URL . '/craftsman/dashboard#active');

        header("Location: " . APP_URL . "/jobs/" . $quote['job_posting_id'] . "?success=quote_accepted");
        }
        else {
            header("Location: " . APP_URL . "/jobs/" . $quote['job_posting_id'] . "?error=accept_failed");
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
        $quote = $quoteModel->findById($quoteId);

        if (!$quote) {
            header("Location: " . APP_URL . "/jobs");
            exit;
        }

        // Verify the current user owns the job this quote belongs to
        $jobModel = new JobPosting();
        $job = $jobModel->findById($quote['job_posting_id']);

        if (!$job || $job['posted_by_user_id'] != $_SESSION['user_id']) {
            echo "Access Denied: You can only reject quotes on your own jobs.";
            exit;
        }

        $quoteModel->rejectQuote($quoteId);

    // Notify the craftsman their quote was rejected
    $notif = new Notification();
    $notif->send($quote['craftsman_id'], 'quote_rejected', 'Quote Declined', 
        'Your quote on "' . $job['title'] . '" was not accepted.', 
        APP_URL . '/craftsman/dashboard#quotes');

    header("Location: " . APP_URL . "/jobs/" . $quote['job_posting_id'] . "?success=quote_rejected");
        exit;
    }
}
