<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Auth\Middleware;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    /**
     * Toggle a craftsman's favorite status.
     * Any logged-in user (homeowner or craftsman) can save a craftsman.
     */
    public function toggle()
    {
        Middleware::requireLogin();

        // Get inputs (either from POST body json or standard POST)
        $inputRaw = file_get_contents('php://input');
        $input = $inputRaw ? json_decode($inputRaw, true) : null;

        // Proper CSRF protection for AJAX
        $token = $input['csrf_token'] ?? $_POST['csrf_token'] ?? '';
        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            $this->json(['success' => false, 'message' => 'CSRF Token Validation Failed.'], 403);
            return;
        }

        $craftsmanId = (int) ($input['craftsman_id'] ?? $_POST['craftsman_id'] ?? 0);

        if (!$craftsmanId) {
            $this->json(['success' => false, 'message' => 'Invalid craftsman ID.'], 400);
            return;
        }

        // Cannot favorite yourself
        if ($craftsmanId === (int)$_SESSION['user_id']) {
            $this->json(['success' => false, 'message' => 'You cannot save yourself.'], 400);
            return;
        }

        $favoriteModel = new Favorite();
        $isFavorite = $favoriteModel->isFavorite($_SESSION['user_id'], $craftsmanId);

        if ($isFavorite) {
            $success = $favoriteModel->removeFavorite($_SESSION['user_id'], $craftsmanId);
            $action   = 'removed';
            $newState = false;
        } else {
            $success = $favoriteModel->addFavorite($_SESSION['user_id'], $craftsmanId);
            $action   = 'added';
            $newState = true;
        }

        if ($success) {
            $this->json(['success' => true, 'action' => $action, 'is_favorite' => $newState]);
        } else {
            $this->json(['success' => false, 'message' => 'Database error occurred.'], 500);
        }
    }
}