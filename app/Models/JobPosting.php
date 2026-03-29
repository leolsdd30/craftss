<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class JobPosting extends Model
{
    /**
     * Create a new job posting.
     */
    /**
     * Maximum images allowed per job (easy to change).
     */
    const MAX_JOB_IMAGES = 3;

    /**
     * Create a new job posting. Returns the new job ID or false.
     */
    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO job_postings (posted_by_user_id, service_category, title, description, address, budget_range) 
             VALUES (:posted_by, :category, :title, :description, :address, :budget)"
        );

        $success = $stmt->execute([
            'posted_by'   => $data['posted_by_user_id'],
            'category'    => $data['service_category'],
            'title'       => $data['title'],
            'description' => $data['description'],
            'address'     => $data['address'],
            'budget'      => $data['budget_range'] ?? null
        ]);

        return $success ? (int) $this->db->lastInsertId() : false;
    }

    /**
     * Update the images JSON column for a given job.
     */
    public function updateImages($jobId, array $paths)
    {
        $stmt = $this->db->prepare(
            "UPDATE job_postings SET images = :images WHERE id = :id"
        );
        return $stmt->execute([
            'images' => json_encode(array_values($paths)),
            'id'     => $jobId
        ]);
    }

    /**
     * Build the shared WHERE clause + params for filters.
     */
    private function buildFilterSql($filters)
    {
        $sql    = " WHERE j.status = 'open'";
        $params = [];

        if (!empty($filters['category'])) {
            $sql .= " AND j.service_category = :category";
            $params['category'] = $filters['category'];
        }

        if (!empty($filters['wilaya'])) {
            $sql .= " AND j.address = :wilaya";
            $params['wilaya'] = $filters['wilaya'];
        }

        if (!empty($filters['search'])) {
            $searchQuery = substr(trim($filters['search']), 0, 100);
            $searchQuery = str_replace(['%', '_', '\\'], '', $searchQuery);
            
            if (!empty($searchQuery)) {
                $sql .= " AND (j.title LIKE :search1 OR j.description LIKE :search2)";
                $params['search1'] = '%' . $searchQuery . '%';
                $params['search2'] = '%' . $searchQuery . '%';
            }
        }

        return [$sql, $params];
    }

    /**
     * Count total open jobs matching filters — for pagination.
     */
    public function countOpenJobs($filters = [])
    {
        [$where, $params] = $this->buildFilterSql($filters);
        $sql  = "SELECT COUNT(*) FROM job_postings j JOIN users u ON j.posted_by_user_id = u.id" . $where;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Get open jobs with optional filters, pagination.
     */
    public function getOpenJobs($filters = [], $limit = 0, $offset = 0)
    {
        [$where, $params] = $this->buildFilterSql($filters);

        $sql = "SELECT j.*, u.first_name, u.last_name, u.username AS poster_username
                FROM job_postings j
                JOIN users u ON j.posted_by_user_id = u.id"
             . $where;

        $sort = $filters['sort'] ?? '';
        $sql .= $sort === 'oldest' ? " ORDER BY j.created_at ASC" : " ORDER BY j.created_at DESC";

        if ($limit > 0) {
            $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get a specific job by its ID.
     */
    public function findById($id)
    {
        $stmt = $this->db->prepare(
            "SELECT j.*, u.first_name, u.last_name, u.username AS poster_username
             FROM job_postings j
             JOIN users u ON j.posted_by_user_id = u.id
             WHERE j.id = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all jobs posted by a specific user.
     */
    public function getJobsByUser($userId)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM job_postings 
             WHERE posted_by_user_id = :user_id 
             ORDER BY created_at DESC"
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update an existing job posting.
     */
    public function updateJob($jobId, $userId, $data)
    {
        $sql = "UPDATE job_postings 
                SET title = :title, 
                    description = :description, 
                    service_category = :service_category, 
                    address = :address, 
                    budget_range = :budget_range";
        
        $params = [
            'id' => $jobId,
            'user_id' => $userId,
            'title' => $data['title'],
            'description' => $data['description'],
            'service_category' => $data['service_category'],
            'address' => $data['address'] ?? null,
            'budget_range' => $data['budget_range'] ?? null
        ];

        // Only update images if the array key exists in $data
        if (array_key_exists('images', $data)) {
            $sql .= ", images = :images";
            $params['images'] = is_array($data['images']) ? json_encode($data['images']) : $data['images'];
        }

        $sql .= " WHERE id = :id AND posted_by_user_id = :user_id AND status = 'open'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }

    /**
     * Cancel/delete a job posting (sets status to 'cancelled').
     */
    public function cancelJob($jobId, $userId)
    {
        $stmt = $this->db->prepare(
            "UPDATE job_postings SET status = 'cancelled' 
             WHERE id = :id AND posted_by_user_id = :user_id AND status = 'open'"
        );
        $stmt->execute(['id' => $jobId, 'user_id' => $userId]);
        return $stmt->rowCount() > 0;
    }
}