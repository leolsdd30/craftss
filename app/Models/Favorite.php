<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Favorite extends Model
{
    /**
     * Add a craftsman to the homeowner's favorites.
     */
    public function addFavorite($homeownerId, $craftsmanId)
    {
        // Check if already favorited
        if ($this->isFavorite($homeownerId, $craftsmanId)) {
            return true; // Already a favorite
        }

        $stmt = $this->db->prepare(
            "INSERT INTO favorites (homeowner_id, craftsman_id) 
             VALUES (:homeowner_id, :craftsman_id)"
        );

        return $stmt->execute([
            'homeowner_id' => $homeownerId,
            'craftsman_id' => $craftsmanId
        ]);
    }

    /**
     * Remove a craftsman from the homeowner's favorites.
     */
    public function removeFavorite($homeownerId, $craftsmanId)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM favorites 
             WHERE homeowner_id = :homeowner_id AND craftsman_id = :craftsman_id"
        );

        return $stmt->execute([
            'homeowner_id' => $homeownerId,
            'craftsman_id' => $craftsmanId
        ]);
    }

    /**
     * Check if a craftsman is favorited by a homeowner.
     */
    public function isFavorite($homeownerId, $craftsmanId)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM favorites WHERE homeowner_id = :hid AND craftsman_id = :cid"
        );
        $stmt->execute(['hid' => $homeownerId, 'cid' => $craftsmanId]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Get all favorite craftsmen for a homeowner.
     */
    public function getFavoritesForHomeowner($homeownerId)
    {
        $stmt = $this->db->prepare(
            "SELECT f.id as favorite_id, f.created_at as favorited_at,
                    u.id, u.first_name, u.last_name, u.profile_picture, u.wilaya,
                    c.service_category, c.hourly_rate, 
                    COALESCE(AVG(r.star_rating), 0) as rating_score, 
                    COUNT(r.id) as reviews_count
             FROM favorites f
             JOIN users u ON f.craftsman_id = u.id
             LEFT JOIN craftsmen_profiles c ON u.id = c.user_id
             LEFT JOIN reviews r ON u.id = r.craftsman_id
             WHERE f.homeowner_id = :homeowner_id
             GROUP BY f.id, u.id, c.id
             ORDER BY f.created_at DESC"
        );
        $stmt->execute(['homeowner_id' => $homeownerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
