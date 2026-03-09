<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class CraftsmanProfile extends Model
{
    /**
     * Get all craftsmen profiles with their user details.
     */
    public function getAllCraftsmen($filters = [])
    {
        $sql = "SELECT cp.*, u.first_name, u.last_name, u.email, u.profile_picture, u.wilaya, u.username 
                FROM craftsmen_profiles cp
                JOIN users u ON cp.user_id = u.id
                WHERE u.is_active = TRUE AND u.role = 'craftsman' AND cp.is_published = TRUE";

        $params = [];
        if (!empty($filters['category'])) {
            $sql .= " AND cp.service_category = :category";
            $params['category'] = $filters['category'];
        }

        if (!empty($filters['wilaya'])) {
            $sql .= " AND u.wilaya = :wilaya";
            $params['wilaya'] = $filters['wilaya'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (u.first_name LIKE :search1 OR u.last_name LIKE :search2 OR cp.bio LIKE :search3)";
            $params['search1'] = '%' . $filters['search'] . '%';
            $params['search2'] = '%' . $filters['search'] . '%';
            $params['search3'] = '%' . $filters['search'] . '%';
        }

        $sort = $filters['sort'] ?? '';
        if ($sort === 'rate_low') {
            $sql .= " ORDER BY cp.is_verified DESC, cp.hourly_rate ASC, cp.id DESC";
        } elseif ($sort === 'rate_high') {
            $sql .= " ORDER BY cp.is_verified DESC, cp.hourly_rate DESC, cp.id DESC";
        } else {
            $sql .= " ORDER BY cp.is_verified DESC, cp.id DESC";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Publish or unpublish a craftsman profile.
     */
    public function setPublishStatus($userId, $status)
    {
        $stmt = $this->db->prepare("UPDATE craftsmen_profiles SET is_published = :status WHERE user_id = :user_id");
        return $stmt->execute(['status' => (int)$status, 'user_id' => $userId]);
    }

    /**
     * Find a craftsman profile by user ID.
     */
    public function findByUserId($userId)
    {
        $stmt = $this->db->prepare(
            "SELECT cp.*, u.first_name, u.last_name, u.email, u.profile_picture, u.phone_number, u.wilaya, u.username 
             FROM craftsmen_profiles cp
             JOIN users u ON cp.user_id = u.id
             WHERE cp.user_id = :user_id"
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create or update a craftsman profile.
     */
    public function updateOrCreate($userId, $data)
    {
        $existing = $this->findByUserId($userId);

        if ($existing) {
            $sql = "UPDATE craftsmen_profiles SET 
                    service_category = :category, 
                    hourly_rate = :rate, 
                    bio = :bio, 
                    portfolio_images = :images 
                    WHERE user_id = :user_id";
        }
        else {
            $sql = "INSERT INTO craftsmen_profiles (user_id, service_category, hourly_rate, bio, portfolio_images) 
                    VALUES (:user_id, :category, :rate, :bio, :images)";
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $userId,
            'category' => $data['service_category'],
            'rate' => $data['hourly_rate'],
            'bio' => $data['bio'] ?? null,
            'images' => isset($data['portfolio_images']) ? json_encode($data['portfolio_images']) : null
        ]);
    }
}
