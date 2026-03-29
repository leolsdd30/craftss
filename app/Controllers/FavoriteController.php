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
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'CSRF Token Validation Failed.']);
            exit;
        }

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

        // Cannot favorite yourself
        if ((int)$craftsmanId === (int)$_SESSION['user_id']) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'You cannot save yourself.']);
            exit;
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

        header('Content-Type: application/json');
        if ($success) {
            echo json_encode(['success' => true, 'action' => $action, 'is_favorite' => $newState]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error occurred.']);
        }
        exit;
    }
}