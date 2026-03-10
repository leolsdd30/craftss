<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class JobPosting extends Model
{
    /**
     * Create a new job posting.
     */
    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO job_postings (posted_by_user_id, service_category, title, description, address, budget_range) 
             VALUES (:posted_by, :category, :title, :description, :address, :budget)"
        );

        return $stmt->execute([
            'posted_by' => $data['posted_by_user_id'],
            'category' => $data['service_category'],
            'title' => $data['title'],
            'description' => $data['description'],
            'address' => $data['address'],
            'budget' => $data['budget_range'] ?? null
        ]);
    }

    /**
     * Get all currently open jobs with optional filters.
     */
    public function getOpenJobs($filters = [])
    {
        $sql = "SELECT j.*, u.first_name, u.last_name 
                FROM job_postings j
                JOIN users u ON j.posted_by_user_id = u.id
                WHERE j.status = 'open'";

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
            $sql .= " AND (j.title LIKE :search1 OR j.description LIKE :search2)";
            $params['search1'] = '%' . $filters['search'] . '%';
            $params['search2'] = '%' . $filters['search'] . '%';
        }

        $sort = $filters['sort'] ?? '';
        if ($sort === 'oldest') {
            $sql .= " ORDER BY j.created_at ASC";
        } else {
            $sql .= " ORDER BY j.created_at DESC";
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
            "SELECT j.*, u.first_name, u.last_name 
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
}
