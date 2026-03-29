<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class JobQuote extends Model
{
    /**
     * Submit a new quote/bid on a job posting.
     */
    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO job_quotes (job_posting_id, craftsman_id, quoted_price, cover_message) 
             VALUES (:job_id, :craftsman_id, :price, :message)"
        );

        return $stmt->execute([
            'job_id' => $data['job_posting_id'],
            'craftsman_id' => $data['craftsman_id'],
            'price' => $data['quoted_price'],
            'message' => $data['cover_message'] ?? null
        ]);
    }

    /**
     * Check if a craftsman has already submitted a quote for a specific job.
     * Prevents spam bidding.
     */
    public function hasAlreadyQuoted($jobId, $craftsmanId)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM job_quotes 
             WHERE job_posting_id = :job_id AND craftsman_id = :craftsman_id"
        );
        $stmt->execute(['job_id' => $jobId, 'craftsman_id' => $craftsmanId]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Get a specific craftsman's quote for a specific job.
     * Used to check the quote status when craftsman revisits the job page.
     */
    public function getCraftsmanQuoteForJob($jobId, $craftsmanId)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM job_quotes
             WHERE job_posting_id = :job_id AND craftsman_id = :craftsman_id
             LIMIT 1"
        );
        $stmt->execute(['job_id' => $jobId, 'craftsman_id' => $craftsmanId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Get all quotes for a specific job posting (used by the homeowner).
     */
    public function getQuotesByJob($jobId)
    {
        $stmt = $this->db->prepare(
            "SELECT q.*, u.first_name, u.last_name, u.email, u.username, cp.is_verified
             FROM job_quotes q
             JOIN users u ON q.craftsman_id = u.id
             LEFT JOIN craftsmen_profiles cp ON u.id = cp.user_id
             WHERE q.job_posting_id = :job_id
             ORDER BY q.created_at DESC"
        );
        $stmt->execute(['job_id' => $jobId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all quotes submitted by a specific craftsman.
     */
    public function getQuotesByCraftsman($craftsmanId)
    {
        $stmt = $this->db->prepare(
            "SELECT q.*, j.title, j.status AS job_status 
             FROM job_quotes q
             JOIN job_postings j ON q.job_posting_id = j.id
             WHERE q.craftsman_id = :craftsman_id
             ORDER BY q.created_at DESC"
        );
        $stmt->execute(['craftsman_id' => $craftsmanId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all quotes received on all jobs posted by a specific homeowner.
     * Used for the homeowner dashboard "Incoming Quotes" tab.
     */
    public function getQuotesForHomeowner($homeownerId)
    {
        $stmt = $this->db->prepare(
            "SELECT q.id AS quote_id, q.job_posting_id, q.craftsman_id, q.quoted_price, 
                    q.cover_message, q.status AS quote_status, q.created_at AS quote_created_at,
                    j.title AS job_title, j.service_category,
                    u.first_name AS craftsman_first_name, u.last_name AS craftsman_last_name, 
                    u.profile_picture AS craftsman_picture, u.username AS craftsman_username,
                    cp.is_verified AS craftsman_is_verified
             FROM job_quotes q
             JOIN job_postings j ON q.job_posting_id = j.id
             JOIN users u ON q.craftsman_id = u.id
             LEFT JOIN craftsmen_profiles cp ON u.id = cp.user_id
             WHERE j.posted_by_user_id = :homeowner_id
             ORDER BY q.created_at DESC"
        );
        $stmt->execute(['homeowner_id' => $homeownerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find a single quote by its ID.
     */
    public function findById($id)
    {
        $stmt = $this->db->prepare(
            "SELECT q.*, u.first_name, u.last_name 
             FROM job_quotes q
             JOIN users u ON q.craftsman_id = u.id
             WHERE q.id = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Accept a quote: set it to 'accepted' and reject all others for that job.
     */
    public function acceptQuote($quoteId)
    {
        $quote = $this->findById($quoteId);
        if (!$quote)
            return false;

        $jobId = $quote['job_posting_id'];

        // Start a transaction to ensure data integrity
        $this->db->beginTransaction();

        try {
            // 1. Set this quote to 'accepted'
            $stmt = $this->db->prepare(
                "UPDATE job_quotes SET status = 'accepted' WHERE id = :id"
            );
            $stmt->execute(['id' => $quoteId]);

            // 2. Reject all other quotes for this job
            $stmt = $this->db->prepare(
                "UPDATE job_quotes SET status = 'rejected' 
                 WHERE job_posting_id = :job_id AND id != :quote_id"
            );
            $stmt->execute(['job_id' => $jobId, 'quote_id' => $quoteId]);

            // 3. Update the job posting status to 'assigned'
            $stmt = $this->db->prepare(
                "UPDATE job_postings SET status = 'assigned' WHERE id = :job_id"
            );
            $stmt->execute(['job_id' => $jobId]);

            // 4. Create an entry in requests_bookings so it appears in the Bookings tab
            // Fetch job details first to populate the booking record
            $stmt = $this->db->prepare("SELECT * FROM job_postings WHERE id = :job_id");
            $stmt->execute(['job_id' => $jobId]);
            $jobDetails = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($jobDetails) {
                $stmt = $this->db->prepare(
                    "INSERT INTO requests_bookings (
                        homeowner_id, 
                        craftsman_id, 
                        job_posting_id, 
                        description, 
                        address, 
                        scheduled_date, 
                        quoted_price, 
                        status
                    ) VALUES (
                        :homeowner_id, 
                        :craftsman_id, 
                        :job_id, 
                        :description, 
                        :address, 
                        NOW(), 
                        :price, 
                        'in_progress'
                    )"
                );
                $stmt->execute([
                    'homeowner_id' => $jobDetails['posted_by_user_id'],
                    'craftsman_id' => $quote['craftsman_id'],
                    'job_id'       => $jobId,
                    'description'  => "Re: " . $jobDetails['title'] . " - " . ($quote['cover_message'] ?? 'Accepted quote'),
                    'address'      => $jobDetails['address'],
                    'price'        => $quote['quoted_price']
                ]);
            }

            $this->db->commit();
            return true;
        }
        catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    /**
     * Reject a single quote (without affecting the job or other quotes).
     */
    public function rejectQuote($quoteId)
    {
        $stmt = $this->db->prepare(
            "UPDATE job_quotes SET status = 'rejected' WHERE id = :id AND status = 'pending'"
        );
        return $stmt->execute(['id' => $quoteId]);
    }
}
