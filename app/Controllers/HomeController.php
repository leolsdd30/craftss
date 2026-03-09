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
            'pageTitle' => 'Welcome to CraftConnect',
            'contentView' => 'public/home',
            'metaDescription' => 'CraftConnect is the easiest way to find and hire reliable freelance craftsmen and service professionals in Algeria.',
            'ogTitle' => 'CraftConnect - Hire Skilled Professionals',
            'ogDescription' => 'CraftConnect is the easiest way to find and hire reliable freelance craftsmen and service professionals in Algeria.'
        ]);
    }

    /**
     * Show the about us page.
     */
    public function about()
    {
        $this->view('layouts/app', [
            'pageTitle' => 'About Us - CraftConnect',
            'contentView' => 'public/about',
            'metaDescription' => 'Learn more about CraftConnect and our mission to empower Algerian craftsmen and connect them with homeowners.',
            'ogTitle' => 'About CraftConnect',
            'ogDescription' => 'Learn more about CraftConnect and our mission to empower Algerian craftsmen and connect them with homeowners.'
        ]);
    }
}
