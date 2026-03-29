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

        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 12; // 12 is optimal for grids of 3 or 4 columns
        $offset = ($page - 1) * $perPage;

        $total = $craftsmanModel->countAllCraftsmen($filters);
        $totalPages = ceil($total / $perPage);

        $craftsmen = $craftsmanModel->getAllCraftsmen($filters, $perPage, $offset);

        // Map favorites if user is logged in 
        if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') !== 'admin') {
            $favoriteModel = new Favorite();
            $myFavorites   = $favoriteModel->getFavoritesForHomeowner($_SESSION['user_id']);
            $favoriteIds   = array_column($myFavorites, 'id');
            foreach ($craftsmen as &$craftsman) {
                $craftsman['is_favorite'] = in_array($craftsman['user_id'], $favoriteIds);
            }
        } else {
            foreach ($craftsmen as &$craftsman) {
                $craftsman['is_favorite'] = false;
            }
        }

        $this->view('layouts/app', [
            'pageTitle' => 'Find Skilled Professionals - Crafts',
            'contentView' => 'search/index',
            'craftsmen' => $craftsmen,
            'filters' => $filters,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalResults' => $total,
            'metaDescription' => 'Find and hire skilled professionals and craftsmen in Algeria. Read reviews, view portfolios, and request bookings directly on Crafts.',
            'ogTitle' => 'Find Skilled Professionals on Crafts',
            'ogDescription' => 'Find and hire skilled professionals and craftsmen in Algeria. Read reviews, view portfolios, and request bookings directly on Crafts.'
        ]);
    }

}
