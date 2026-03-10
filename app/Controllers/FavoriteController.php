<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Auth\Middleware;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    /**
     * Toggle a craftsman's favorite status.
     * Expects POST request (for security) or we can use a GET for simplicity if we add CSRF protection
     * Let's use POST with JSON response so we can do it via AJAX easily.
     */
    public function toggle()
    {
        Middleware::requireLogin();
        
        // CSRF protection for AJAX: validate Origin or Referer header
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        $appHost = parse_url(APP_URL, PHP_URL_HOST);
        $originHost = !empty($origin) ? parse_url($origin, PHP_URL_HOST) : null;
        $refererHost = !empty($referer) ? parse_url($referer, PHP_URL_HOST) : null;
        
        if ($originHost !== $appHost && $refererHost !== $appHost) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid request origin.']);
            exit;
        }

        // Only homeowners can favorite
        if ($_SESSION['role'] !== 'homeowner') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Only homeowners can save favorites.']);
            exit;
        }

        // Get inputs (either from POST body json or standard POST)
        $inputRaw = file_get_contents('php://input');
        $input = $inputRaw ? json_decode($inputRaw, true) : null;
        
        $craftsmanId = null;
        if (is_array($input) && isset($input['craftsman_id'])) {
            $craftsmanId = $input['craftsman_id'];
        } else {
            $craftsmanId = $_POST['craftsman_id'] ?? null;
        }

        if (!$craftsmanId) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid craftsman ID.']);
            exit;
        }

        $favoriteModel = new Favorite();
        $isFavorite = $favoriteModel->isFavorite($_SESSION['user_id'], $craftsmanId);

        if ($isFavorite) {
            // Remove it
            $success = $favoriteModel->removeFavorite($_SESSION['user_id'], $craftsmanId);
            $action = 'removed';
            $newState = false;
        } else {
            // Add it
            $success = $favoriteModel->addFavorite($_SESSION['user_id'], $craftsmanId);
            $action = 'added';
            $newState = true;
        }

        header('Content-Type: application/json');
        if ($success) {
            echo json_encode(['success' => true, 'action' => $action, 'is_favorite' => $newState]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error occurred.']);
        }
        exit;
    }
}
