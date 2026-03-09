<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Auth\Middleware;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    /**
     * Toggle a craftsman's favorite status.
     * Accepts POST with JSON body. CSRF token must be sent via X-CSRF-Token header.
     */
    public function toggle()
    {
        Middleware::requireLogin();

        // [SECURITY] CSRF verification — supports both header token (AJAX) and POST body token
        Middleware::verifyCsrfToken();

        // Only homeowners can favorite
        if ($_SESSION['role'] !== 'homeowner') {
            header('Content-Type: application/json');
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Only homeowners can save favorites.']);
            exit;
        }

        // Get inputs from JSON body or standard POST
        $inputRaw    = file_get_contents('php://input');
        $input       = $inputRaw ? json_decode($inputRaw, true) : null;
        $craftsmanId = null;

        if (is_array($input) && isset($input['craftsman_id'])) {
            $craftsmanId = (int) $input['craftsman_id'];
        } else {
            $craftsmanId = isset($_POST['craftsman_id']) ? (int) $_POST['craftsman_id'] : null;
        }

        if (!$craftsmanId || $craftsmanId <= 0) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid craftsman ID.']);
            exit;
        }

        // [SECURITY] Prevent users from favoriting themselves
        if ($craftsmanId === (int) $_SESSION['user_id']) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'You cannot favorite yourself.']);
            exit;
        }

        $favoriteModel = new Favorite();
        $isFavorite    = $favoriteModel->isFavorite($_SESSION['user_id'], $craftsmanId);

        if ($isFavorite) {
            $success  = $favoriteModel->removeFavorite($_SESSION['user_id'], $craftsmanId);
            $action   = 'removed';
            $newState = false;
        } else {
            $success  = $favoriteModel->addFavorite($_SESSION['user_id'], $craftsmanId);
            $action   = 'added';
            $newState = true;
        }

        header('Content-Type: application/json');
        if ($success) {
            echo json_encode(['success' => true, 'action' => $action, 'is_favorite' => $newState]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error occurred.']);
        }
        exit;
    }
}
