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
        Middleware::requireEmailVerification();

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
        Middleware::requireEmailVerification();
        Middleware::verifyCsrfToken();

        if (isset($_SESSION['role']) && $_SESSION['role'] !== 'homeowner') {
            header("Location: " . APP_URL . "/jobs");
            exit;
        }

        $validator = new \App\Services\Validator();
        $isValid = $validator->validate($_POST, [
            'title'       => 'required|min:5|max:100',
            'category'    => 'required',
            'description' => 'required|min:15',
            'address'     => 'required'
        ]);

        if (!$isValid) {
            $this->view('layouts/app', [
                'pageTitle'   => 'Post a Job - Crafts',
                'contentView' => 'jobboard/create',
                'error'       => $validator->getFirstError()
            ]);
            return;
        }
        
        $title       = trim($_POST['title']);
        $category    = trim($_POST['category']);
        $description = trim($_POST['description']);
        $address     = trim($_POST['address']);
        $budget      = isset($_POST['budget']) ? trim($_POST['budget']) : null;

        $jobModel = new JobPosting();
        $jobId = $jobModel->create([
            'posted_by_user_id' => $_SESSION['user_id'],
            'service_category'  => $category,
            'title'             => $title,
            'description'       => $description,
            'address'           => $address,
            'budget_range'      => $budget
        ]);

        if ($jobId) {
            // Handle image uploads
            if (!empty($_FILES['images']['name'][0])) {
                $this->processJobImages($jobId, $jobModel);
            }

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
     * Show the form to edit an existing job.
     */
    public function edit($id)
    {
        Middleware::requireLogin();
        Middleware::requireRole('homeowner');

        $jobModel = new JobPosting();
        $job = $jobModel->findById($id);

        if (!$job || $job['posted_by_user_id'] != $_SESSION['user_id']) {
            throw new \App\Exceptions\NotFoundException();
        }

        if ($job['status'] !== 'open') {
            $_SESSION['error'] = 'You can only edit jobs that are currently open.';
            header("Location: " . APP_URL . "/homeowner/dashboard");
            exit;
        }

        $this->view('layouts/app', [
            'pageTitle'   => 'Edit Job - Crafts',
            'contentView' => 'jobboard/edit',
            'job'         => $job
        ]);
    }

    /**
     * Process the job edit submission.
     */
    public function update($id)
    {
        Middleware::requireLogin();
        Middleware::verifyCsrfToken();
        Middleware::requireRole('homeowner');

        $jobModel = new JobPosting();
        $job = $jobModel->findById($id);

        if (!$job || $job['posted_by_user_id'] != $_SESSION['user_id']) {
            throw new \App\Exceptions\NotFoundException();
        }
        if ($job['status'] !== 'open') {
            $_SESSION['error'] = 'You can only edit jobs that are currently open.';
            header("Location: " . APP_URL . "/homeowner/dashboard");
            exit;
        }

        $validator = new \App\Services\Validator();
        $isValid = $validator->validate($_POST, [
            'title'       => 'required|min:5|max:100',
            'category'    => 'required',
            'description' => 'required|min:15',
            'address'     => 'required'
        ]);

        if (!$isValid) {
            $this->view('layouts/app', [
                'pageTitle'   => 'Edit Job - Crafts',
                'contentView' => 'jobboard/edit',
                'job'         => $job,
                'error'       => $validator->getFirstError()
            ]);
            return;
        }
        
        $title       = trim($_POST['title']);
        $category    = trim($_POST['category']);
        $description = trim($_POST['description']);
        $address     = trim($_POST['address']);
        $budget      = isset($_POST['budget']) ? trim($_POST['budget']) : null;
        
        // Handle images logic
        $currentImages = [];
        if (!empty($job['images'])) {
            $decoded = is_string($job['images']) ? json_decode($job['images'], true) : $job['images'];
            if (is_array($decoded)) $currentImages = $decoded;
        }

        $imagesToDelete = $_POST['delete_images'] ?? [];
        $remainingImages = [];
        $publicDir = dirname($_SERVER['SCRIPT_FILENAME']);

        foreach ($currentImages as $img) {
            if (in_array($img, $imagesToDelete)) {
                // Delete file from disk
                $filePath = $publicDir . '/' . ltrim($img, '/');
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            } else {
                $remainingImages[] = $img;
            }
        }

        // Apply textual updates
        $updateSuccess = $jobModel->updateJob($id, $_SESSION['user_id'], [
            'title'             => $title,
            'description'       => $description,
            'service_category'  => $category,
            'address'           => $address,
            'budget_range'      => $budget
        ]);

        // Process images (merges remaining with new uploads and saves to DB)
        $this->processJobImages($id, $jobModel, $remainingImages);

        $source = $_POST['source'] ?? 'dashboard';
        if ($source === 'job_view') {
            header("Location: " . APP_URL . "/jobs/" . $id . "?success=job_updated");
        } else {
            header("Location: " . APP_URL . "/homeowner/dashboard?success=job_updated#jobs");
        }
        exit;
    }

    /**
     * Process and save uploaded job images into per-job folder.
     */
    private function processJobImages($jobId, JobPosting $jobModel, $existingImages = [])
    {
        $maxImages    = JobPosting::MAX_JOB_IMAGES;
        $maxSize      = 2 * 1024 * 1024; // 2 MB
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $allowedExts  = ['jpg', 'jpeg', 'png', 'webp'];

        $publicDir = dirname($_SERVER['SCRIPT_FILENAME']); // points to /project/public
        $uploadDir = $publicDir . '/uploads/jobs/' . $jobId . '/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $savedPaths = $existingImages; // Start with the ones we kept
        
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $files = $_FILES['images'];
            $count = count($files['name']);
            
            for ($i = 0; $i < $count; $i++) {
                if (count($savedPaths) >= $maxImages) break; // Don't exceed max config limit
                if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
                if ($files['size'][$i] > $maxSize) continue;

                $mime = mime_content_type($files['tmp_name'][$i]);
                if (!in_array($mime, $allowedTypes)) continue;

                $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowedExts)) continue;

                $filename = 'img_' . ($i + 1) . '_' . time() . '.' . $ext;
                $destPath = $uploadDir . $filename;

                if (move_uploaded_file($files['tmp_name'][$i], $destPath)) {
                    $savedPaths[] = 'uploads/jobs/' . $jobId . '/' . $filename;
                }
            }
        }

        // We update images if there were ANY changes (even just deletions)
        // If we only deleted, savedPaths is just existingImages, so it overwrites with the new state.
        $jobModel->updateImages($jobId, $savedPaths);
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
            throw new \App\Exceptions\NotFoundException();
        }

        $jobModel = new JobPosting();
        $job      = $jobModel->findById($id);

        if (!$job) {
            throw new \App\Exceptions\NotFoundException();
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

        $ogTitle    = htmlspecialchars($job['title']) . ' - Crafts Job Board';
        
        $safeTitle  = htmlspecialchars($job['title']);
        $safeCat    = htmlspecialchars($job['service_category']);
        $safeBudget = htmlspecialchars((string)($job['budget_range'] ?? ''));
        $safeLoc    = htmlspecialchars($job['address']);

        $metaDesc = "View the job '{$safeTitle}' in the {$safeCat} category. " .
                    ($safeBudget ? "Budget: {$safeBudget} DZD. " : "") .
                    "Location: {$safeLoc}. Apply now on Crafts.";

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
        Middleware::requireEmailVerification();
        Middleware::verifyCsrfToken();

        $validator = new \App\Services\Validator();
        if (!$validator->validate($_POST, [
            'job_posting_id' => 'required',
            'quoted_price'   => 'required|numeric',
            'cover_message'  => 'max:2000'
        ])) {
            $jobId = $_POST['job_posting_id'] ?? '';
            $errorMsg = urlencode($validator->getFirstError());
            header("Location: " . APP_URL . "/jobs/" . $jobId . "?error=" . $errorMsg);
            exit;
        }

        $jobId   = $_POST['job_posting_id'];
        $price   = $_POST['quoted_price'];
        $message = trim($_POST['cover_message']);

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
        Middleware::requireEmailVerification();
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
            header("Location: " . APP_URL . "/homeowner/dashboard?error=access_denied#quotes");
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
        Middleware::requireEmailVerification();
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
            header("Location: " . APP_URL . "/homeowner/dashboard?error=access_denied#quotes");
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

    /**
     * Homeowner cancels/removes their own job posting.
     */
    public function deleteJob()
    {
        Middleware::requireLogin();
        Middleware::requireEmailVerification();
        Middleware::verifyCsrfToken();

        $jobId = $_POST['job_id'] ?? null;
        if (!$jobId) {
            header("Location: " . APP_URL . "/homeowner/dashboard#jobs");
            exit;
        }

        $jobModel = new JobPosting();
        $success = $jobModel->cancelJob($jobId, $_SESSION['user_id']);

        if ($success) {
            header("Location: " . APP_URL . "/homeowner/dashboard?success=job_cancelled#jobs");
        } else {
            header("Location: " . APP_URL . "/homeowner/dashboard?error=cancel_failed#jobs");
        }
        exit;
    }
}
