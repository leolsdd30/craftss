<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    /**
     * Show the home / landing page.
     * Fetches live platform stats for the stats bar section.
     */
    public function index()
    {
        $stats = $this->getPlatformStats();

        $this->view('layouts/app', [
            'pageTitle'       => 'Welcome to Crafts',
            'contentView'     => 'public/home',
            'stats'           => $stats,
            'metaDescription' => 'Crafts is the easiest way to find and hire reliable freelance craftsmen and service professionals in Algeria.',
            'ogTitle'         => 'Crafts - Hire Skilled Professionals',
            'ogDescription'   => 'Crafts is the easiest way to find and hire reliable freelance craftsmen and service professionals in Algeria.',
        ]);
    }

    /**
     * Show the about us page.
     * Passes live platform stats for the stats row.
     */
    public function about()
    {
        $stats = $this->getPlatformStats();

        $this->view('layouts/app', [
            'pageTitle'       => 'About Us - Crafts',
            'contentView'     => 'public/about',
            'stats'           => $stats,
            'metaDescription' => 'Learn more about Crafts and our mission to empower Algerian craftsmen and connect them with homeowners.',
            'ogTitle'         => 'About Crafts',
            'ogDescription'   => 'Learn more about Crafts and our mission to empower Algerian craftsmen and connect them with homeowners.',
        ]);
    }

    /**
     * Show the contact page.
     */
    public function contact()
    {
        $this->view('layouts/app', [
            'pageTitle'       => 'Contact Us - Crafts',
            'contentView'     => 'public/contact',
        ]);
    }

    /**
     * Show the privacy policy page.
     */
    public function privacy()
    {
        $this->view('layouts/app', [
            'pageTitle'       => 'Privacy Policy - Crafts',
            'contentView'     => 'public/privacy',
        ]);
    }

    /**
     * Shared helper to fetch live platform statistics (DRY).
     */
    private function getPlatformStats()
    {
        $db = \App\Database\Database::getInstance()->getConnection();

        $stats = [];
        $stats['craftsmen'] = (int) $db->query(
            "SELECT COUNT(*) FROM craftsmen_profiles cp
             JOIN users u ON cp.user_id = u.id
             WHERE u.is_active = TRUE AND u.role = 'craftsman' AND cp.is_published = TRUE"
        )->fetchColumn();

        $stats['wilayas'] = (int) $db->query(
            "SELECT COUNT(DISTINCT u.wilaya) FROM craftsmen_profiles cp
             JOIN users u ON cp.user_id = u.id
             WHERE u.is_active = TRUE AND u.role = 'craftsman'
             AND cp.is_published = TRUE AND u.wilaya IS NOT NULL AND u.wilaya != ''"
        )->fetchColumn();

        $stats['completed_bookings'] = (int) $db->query(
            "SELECT COUNT(*) FROM requests_bookings WHERE status = 'completed'"
        )->fetchColumn();

        $stats['avg_rating'] = round(
            (float) $db->query("SELECT IFNULL(AVG(star_rating), 0) FROM reviews")->fetchColumn(),
            1
        );

        return $stats;
    }
}