<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class CraftsmanProfile extends Model
{
    /**
     * Get all published craftsmen with user details + avg rating from reviews.
     * The LEFT JOIN on reviews means craftsmen with no reviews get avg_rating=0, total_reviews=0.
     */
    public function getAllCraftsmen($filters = [], $limit = 0, $offset = 0)
    {
        $sql = "SELECT cp.*,
                       u.first_name, u.last_name, u.email,
                       u.profile_picture, u.wilaya, u.username,
                       ROUND(IFNULL(AVG(r.star_rating), 0), 1) AS avg_rating,
                       COUNT(r.id)                              AS total_reviews
                FROM craftsmen_profiles cp
                JOIN  users   u ON cp.user_id = u.id
                LEFT JOIN reviews r ON r.craftsman_id = u.id
                WHERE u.is_active = TRUE
                  AND u.role = 'craftsman'
                  AND cp.is_published = TRUE";

        $params = [];

        if (!empty($filters['category'])) {
            $sql .= " AND cp.service_category = :category";
            $params['category'] = $filters['category'];
        }

        if (!empty($filters['wilaya'])) {
            $sql .= " AND u.wilaya = :wilaya";
            $params['wilaya'] = $filters['wilaya'];
        }

        $searchQuery = $filters['search'] ?? '';
        if (!empty($searchQuery)) {
            $searchQuery = substr(trim($searchQuery), 0, 100);
            $searchQuery = str_replace(['%', '_', '\\'], '', $searchQuery);
        }

        if (!empty($searchQuery)) {
            $sql .= " AND (u.first_name LIKE :search1
                           OR u.last_name  LIKE :search2
                           OR cp.bio       LIKE :search3)";
            $params['search1'] = '%' . $searchQuery . '%';
            $params['search2'] = '%' . $searchQuery . '%';
            $params['search3'] = '%' . $searchQuery . '%';
        }

        // GROUP BY required because of the aggregate (AVG / COUNT)
        $sql .= " GROUP BY cp.id, u.id";

        $sort = $filters['sort'] ?? '';
        if ($sort === 'rate_low') {
            $sql .= " ORDER BY cp.is_verified DESC, cp.hourly_rate ASC,  cp.id DESC";
        } elseif ($sort === 'rate_high') {
            $sql .= " ORDER BY cp.is_verified DESC, cp.hourly_rate DESC, cp.id DESC";
        } elseif ($sort === 'top_rated') {
            $sql .= " ORDER BY avg_rating DESC, total_reviews DESC, cp.id DESC";
        } else {
            // Default: verified first, then newest
            $sql .= " ORDER BY cp.is_verified DESC, cp.id DESC";
        }

        if ($limit > 0) {
            $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get the total count of published craftsmen matching the given filters (for pagination).
     */
    public function countAllCraftsmen($filters = [])
    {
        $sql = "SELECT COUNT(DISTINCT cp.id) as total
                FROM craftsmen_profiles cp
                JOIN users u ON cp.user_id = u.id
                WHERE u.is_active = TRUE
                  AND u.role = 'craftsman'
                  AND cp.is_published = TRUE";

        $params = [];

        if (!empty($filters['category'])) {
            $sql .= " AND cp.service_category = :category";
            $params['category'] = $filters['category'];
        }

        if (!empty($filters['wilaya'])) {
            $sql .= " AND u.wilaya = :wilaya";
            $params['wilaya'] = $filters['wilaya'];
        }

        $searchQuery = $filters['search'] ?? '';
        if (!empty($searchQuery)) {
            $searchQuery = substr(trim($searchQuery), 0, 100);
            $searchQuery = str_replace(['%', '_', '\\'], '', $searchQuery);
        }

        if (!empty($searchQuery)) {
            $sql .= " AND (u.first_name LIKE :search1
                           OR u.last_name  LIKE :search2
                           OR cp.bio       LIKE :search3)";
            $params['search1'] = '%' . $searchQuery . '%';
            $params['search2'] = '%' . $searchQuery . '%';
            $params['search3'] = '%' . $searchQuery . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? (int) $result['total'] : 0;
    }

    /**
     * Publish or unpublish a craftsman's profile card.
     */
    public function setPublishStatus($userId, $status)
    {
        $stmt = $this->db->prepare(
            "UPDATE craftsmen_profiles SET is_published = :status WHERE user_id = :user_id"
        );
        return $stmt->execute(['status' => (int) $status, 'user_id' => $userId]);
    }

    /**
     * Find a single craftsman profile by their user ID.
     */
    public function findByUserId($userId)
    {
        $stmt = $this->db->prepare(
            "SELECT cp.*, u.first_name, u.last_name, u.email,
                    u.profile_picture, u.phone_number, u.wilaya, u.username
             FROM craftsmen_profiles cp
             JOIN users u ON cp.user_id = u.id
             WHERE cp.user_id = :user_id"
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create or update a craftsman profile record.
     */
    public function updateOrCreate($userId, $data)
    {
        $existing = $this->findByUserId($userId);

        if ($existing) {
            $sql = "UPDATE craftsmen_profiles
                    SET service_category = :category,
                        hourly_rate      = :rate,
                        bio              = :bio,
                        portfolio_images = :images
                    WHERE user_id = :user_id";
        } else {
            $sql = "INSERT INTO craftsmen_profiles
                        (user_id, service_category, hourly_rate, bio, portfolio_images)
                    VALUES (:user_id, :category, :rate, :bio, :images)";
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id'  => $userId,
            'category' => $data['service_category'],
            'rate'     => $data['hourly_rate'],
            'bio'      => $data['bio'] ?? null,
            'images'   => isset($data['portfolio_images'])
                            ? json_encode($data['portfolio_images'])
                            : null,
        ]);
    }
}