<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    /**
     * Show the application dashboard / home page.
     */
    public function index()
    {
        $this->view('layouts/app', [
            'pageTitle' => 'Welcome to Crafts',
            'contentView' => 'public/home',
            'metaDescription' => 'Crafts is the easiest way to find and hire reliable freelance craftsmen and service professionals in Algeria.',
            'ogTitle' => 'Crafts - Hire Skilled Professionals',
            'ogDescription' => 'Crafts is the easiest way to find and hire reliable freelance craftsmen and service professionals in Algeria.'
        ]);
    }

    /**
     * Show the about us page.
     */
    public function about()
    {
        $this->view('layouts/app', [
            'pageTitle' => 'About Us - Crafts',
            'contentView' => 'public/about',
            'metaDescription' => 'Learn more about Crafts and our mission to empower Algerian craftsmen and connect them with homeowners.',
            'ogTitle' => 'About Crafts',
            'ogDescription' => 'Learn more about Crafts and our mission to empower Algerian craftsmen and connect them with homeowners.'
        ]);
    }
}
