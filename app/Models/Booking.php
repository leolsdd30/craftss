<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Booking extends Model
{
    /**
     * Create a new booking request.
     */
    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO requests_bookings (homeowner_id, craftsman_id, description, address, scheduled_date) 
             VALUES (:homeowner_id, :craftsman_id, :description, :address, :scheduled_date)"
        );

        return $stmt->execute([
            'homeowner_id' => $data['homeowner_id'],
            'craftsman_id' => $data['craftsman_id'],
            'description' => $data['description'],
            'address' => $data['address'],
            'scheduled_date' => $data['scheduled_date']
        ]);
    }

    /**
     * Get all booking requests sent TO a specific craftsman.
     */
    public function getBookingsForCraftsman($craftsmanId)
    {
        $stmt = $this->db->prepare(
            "SELECT rb.*, u.first_name, u.last_name, u.profile_picture, u.username, u.email, u.username
             FROM requests_bookings rb
             JOIN users u ON rb.homeowner_id = u.id
             WHERE rb.craftsman_id = :craftsman_id
             ORDER BY rb.created_at DESC"
        );
        $stmt->execute(['craftsman_id' => $craftsmanId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all booking requests sent BY a specific homeowner.
     */
    public function getBookingsForHomeowner($homeownerId)
    {
        $stmt = $this->db->prepare(
            "SELECT rb.*, u.first_name, u.last_name, u.profile_picture, u.username
             FROM requests_bookings rb
             JOIN users u ON rb.craftsman_id = u.id
             WHERE rb.homeowner_id = :homeowner_id
             ORDER BY rb.created_at DESC"
        );
        $stmt->execute(['homeowner_id' => $homeownerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find a single booking by ID.
     */
    public function findById($id)
    {
        $stmt = $this->db->prepare(
            "SELECT rb.*, 
                    h.first_name AS homeowner_first, h.last_name AS homeowner_last,
                    c.first_name AS craftsman_first, c.last_name AS craftsman_last
             FROM requests_bookings rb
             JOIN users h ON rb.homeowner_id = h.id
             JOIN users c ON rb.craftsman_id = c.id
             WHERE rb.id = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update booking status.
     */
    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare(
            "UPDATE requests_bookings SET status = :status WHERE id = :id"
        );
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    /**
     * Craftsman sets a quoted price on a booking request.
     */
    public function setQuotedPrice($id, $price)
    {
        $stmt = $this->db->prepare(
            "UPDATE requests_bookings SET quoted_price = :price, status = 'quoted' WHERE id = :id"
        );
        return $stmt->execute(['price' => $price, 'id' => $id]);
    }

    /**
     * Craftsman sends a counter-offer with edited details.
     */
    public function counterOffer($id, $data)
    {
        $stmt = $this->db->prepare(
            "UPDATE requests_bookings 
             SET status = 'counter_offered', 
                 counter_description = :counter_description,
                 counter_price = :counter_price,
                 counter_date = :counter_date,
                 counter_note = :counter_note
             WHERE id = :id"
        );
        return $stmt->execute([
            'counter_description' => $data['counter_description'],
            'counter_price' => $data['counter_price'],
            'counter_date' => $data['counter_date'],
            'counter_note' => $data['counter_note'] ?? null,
            'id' => $id
        ]);
    }

    /**
     * Homeowner accepts the craftsman's counter-offer. Move to in_progress.
     */
    public function acceptCounterOffer($id)
    {
        $stmt = $this->db->prepare(
            "UPDATE requests_bookings 
             SET status = 'in_progress',
                 description = COALESCE(counter_description, description),
                 quoted_price = COALESCE(counter_price, quoted_price),
                 scheduled_date = COALESCE(counter_date, scheduled_date)
             WHERE id = :id AND status = 'counter_offered'"
        );
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Craftsman marks the job as pending completion (waiting for homeowner confirmation).
     */
    public function markPendingCompletion($id)
    {
        $stmt = $this->db->prepare(
            "UPDATE requests_bookings SET status = 'pending_completion' WHERE id = :id AND status = 'in_progress'"
        );
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Homeowner confirms the job is complete.
     */
    public function confirmCompletion($id)
    {
        $stmt = $this->db->prepare(
            "UPDATE requests_bookings SET status = 'completed', completion_date = NOW() WHERE id = :id AND status = 'pending_completion'"
        );
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Count pending booking requests for a craftsman.
     */
    public function countPendingForCraftsman($craftsmanId)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM requests_bookings WHERE craftsman_id = :id AND status = 'requested'"
        );
        $stmt->execute(['id' => $craftsmanId]);
        return $stmt->fetchColumn();
    }

    /**
     * Count pending booking requests for a homeowner.
     */
    public function countPendingForHomeowner($homeownerId)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM requests_bookings WHERE homeowner_id = :id AND status IN ('requested', 'quoted', 'counter_offered')"
        );
        $stmt->execute(['id' => $homeownerId]);
        return $stmt->fetchColumn();
    }
}
