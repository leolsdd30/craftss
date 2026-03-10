<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Review extends Model
{
    /**
     * Create a new review.
     */
    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO reviews (booking_id, homeowner_id, craftsman_id, star_rating, comment) 
             VALUES (:booking_id, :homeowner_id, :craftsman_id, :star_rating, :comment)"
        );

        return $stmt->execute([
            'booking_id' => $data['booking_id'] ?? null,
            'homeowner_id' => $data['homeowner_id'],
            'craftsman_id' => $data['craftsman_id'],
            'star_rating' => $data['star_rating'],
            'comment' => $data['comment'] ?? null
        ]);
    }

    /**
     * Get all reviews for a specific craftsman (with homeowner info).
     */
    public function getReviewsForCraftsman($craftsmanId)
    {
        $stmt = $this->db->prepare(
            "SELECT r.*, u.first_name, u.last_name, u.profile_picture
             FROM reviews r
             JOIN users u ON r.homeowner_id = u.id
             WHERE r.craftsman_id = :craftsman_id
             ORDER BY r.created_at DESC"
        );
        $stmt->execute(['craftsman_id' => $craftsmanId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get a craftsman's average rating and total review count.
     */
    public function getCraftsmanRating($craftsmanId)
    {
        $stmt = $this->db->prepare(
            "SELECT AVG(star_rating) AS avg_rating, COUNT(*) AS total_reviews 
             FROM reviews 
             WHERE craftsman_id = :craftsman_id"
        );
        $stmt->execute(['craftsman_id' => $craftsmanId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return [
            'avg_rating' => $result['avg_rating'] ? round($result['avg_rating'], 1) : 0,
            'total_reviews' => (int) $result['total_reviews']
        ];
    }

    /**
     * Check if a homeowner has already reviewed a specific craftsman for a specific booking.
     */
    public function hasReviewed($homeownerId, $craftsmanId, $bookingId = null)
    {
        if ($bookingId) {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM reviews WHERE homeowner_id = :hid AND craftsman_id = :cid AND booking_id = :bid"
            );
            $stmt->execute(['hid' => $homeownerId, 'cid' => $craftsmanId, 'bid' => $bookingId]);
        } else {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM reviews WHERE homeowner_id = :hid AND craftsman_id = :cid"
            );
            $stmt->execute(['hid' => $homeownerId, 'cid' => $craftsmanId]);
        }
        return $stmt->fetchColumn() > 0;
    }
}
