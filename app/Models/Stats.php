<?php
namespace App\Models;

use App\Core\Model;

class Stats extends Model
{
    /**
     * Get platform dashboard metrics.
     *
     * @return array
     */
    public function getDashboardMetrics(): array
    {
        $stats = [];
        $stats['total_users'] = (int) $this->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $stats['homeowners'] = (int) $this->query("SELECT COUNT(*) FROM users WHERE role = 'homeowner'")->fetchColumn();
        $stats['craftsmen'] = (int) $this->query("SELECT COUNT(*) FROM users WHERE role = 'craftsman'")->fetchColumn();
        $stats['admins'] = (int) $this->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
        $stats['total_bookings'] = (int) $this->query("SELECT COUNT(*) FROM requests_bookings")->fetchColumn();
        $stats['active_bookings'] = (int) $this->query("SELECT COUNT(*) FROM requests_bookings WHERE status = 'hired'")->fetchColumn();
        $stats['completed_bookings'] = (int) $this->query("SELECT COUNT(*) FROM requests_bookings WHERE status = 'completed'")->fetchColumn();
        $stats['total_jobs'] = (int) $this->query("SELECT COUNT(*) FROM job_postings")->fetchColumn();
        $stats['open_jobs'] = (int) $this->query("SELECT COUNT(*) FROM job_postings WHERE status = 'open'")->fetchColumn();
        $stats['total_reviews'] = (int) $this->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
        $stats['avg_rating'] = round((float) $this->query("SELECT IFNULL(AVG(star_rating), 0) FROM reviews")->fetchColumn(), 1);
        $stats['verified_craftsmen'] = (int) $this->query("SELECT COUNT(*) FROM craftsmen_profiles cp JOIN users u ON cp.user_id = u.id WHERE cp.is_verified = TRUE AND u.role = 'craftsman'")->fetchColumn();
        $stats['pending_verification'] = (int) $this->query("SELECT COUNT(*) FROM craftsmen_profiles cp JOIN users u ON cp.user_id = u.id WHERE cp.is_verified = FALSE AND u.role = 'craftsman'")->fetchColumn();
        $stats['total_messages'] = (int) $this->query("SELECT COUNT(*) FROM messages")->fetchColumn();

        return $stats;
    }
}
