<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\CraftsmanProfile;
use App\Models\Favorite;

class SearchController extends Controller
{
    /**
     * Display a public-facing grid of craftsmen with optional search and filters.
     */
    public function index()
    {
        $craftsmanModel = new CraftsmanProfile();

        $filters = [
            'category' => $_GET['category'] ?? null,
            'search' => $_GET['q'] ?? null,
            'wilaya' => $_GET['wilaya'] ?? null,
            'sort' => $_GET['sort'] ?? null
        ];

        $craftsmen = $craftsmanModel->getAllCraftsmen($filters);

        // Map favorites if user is logged in as homeowner
        if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'homeowner') {
            $favoriteModel = new Favorite();
            $myFavorites = $favoriteModel->getFavoritesForHomeowner($_SESSION['user_id']);
            $favoriteIds = array_column($myFavorites, 'id'); // user.id of favorite craftsmen
            
            foreach ($craftsmen as &$craftsman) {
                $craftsman['is_favorite'] = in_array($craftsman['user_id'], $favoriteIds);
            }
        } else {
            foreach ($craftsmen as &$craftsman) {
                $craftsman['is_favorite'] = false;
            }
        }

        $this->view('layouts/app', [
            'pageTitle' => 'Find Skilled Professionals - CraftConnect',
            'contentView' => 'search/index',
            'craftsmen' => $craftsmen,
            'filters' => $filters,
            'metaDescription' => 'Find and hire skilled professionals and craftsmen in Algeria. Read reviews, view portfolios, and request bookings directly on CraftConnect.',
            'ogTitle' => 'Find Skilled Professionals on CraftConnect',
            'ogDescription' => 'Find and hire skilled professionals and craftsmen in Algeria. Read reviews, view portfolios, and request bookings directly on CraftConnect.'
        ]);
    }

}
