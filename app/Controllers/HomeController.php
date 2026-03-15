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
        // Pull live stats from the DB for the stats bar on the home page.
        // Uses the singleton PDO connection — same pattern as AdminController.
        $db = \App\Database\Database::getInstance()->getConnection();

        $stats = [];

        // Total published craftsmen
        $stats['craftsmen'] = (int) $db->query(
            "SELECT COUNT(*) FROM craftsmen_profiles cp
             JOIN users u ON cp.user_id = u.id
             WHERE u.is_active = TRUE AND u.role = 'craftsman' AND cp.is_published = TRUE"
        )->fetchColumn();

        // Number of distinct wilayas that have at least one active published craftsman
        $stats['wilayas'] = (int) $db->query(
            "SELECT COUNT(DISTINCT u.wilaya) FROM craftsmen_profiles cp
             JOIN users u ON cp.user_id = u.id
             WHERE u.is_active = TRUE AND u.role = 'craftsman'
             AND cp.is_published = TRUE AND u.wilaya IS NOT NULL AND u.wilaya != ''"
        )->fetchColumn();

        // Total completed bookings
        $stats['completed_bookings'] = (int) $db->query(
            "SELECT COUNT(*) FROM requests_bookings WHERE status = 'completed'"
        )->fetchColumn();

        // Average star rating across all reviews (rounded to 1 decimal)
        $stats['avg_rating'] = round(
            (float) $db->query("SELECT IFNULL(AVG(star_rating), 0) FROM reviews")->fetchColumn(),
            1
        );

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
     */
    public function about()
    {
        $this->view('layouts/app', [
            'pageTitle'       => 'About Us - Crafts',
            'contentView'     => 'public/about',
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
}