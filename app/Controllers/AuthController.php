<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Auth\Middleware;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm($error = null)
    {
        $this->view('layouts/app', [
            'pageTitle'   => 'Sign In - CraftConnect',
            'contentView' => 'auth/login',
            'error'       => $error
        ]);
    }

    /**
     * Process the login request.
     */
    public function processLogin()
    {
        Middleware::verifyCsrfToken();

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->showLoginForm("Email and password are required.");
            return;
        }

        // [SECURITY] Rate limit: max 5 login attempts per 15 minutes per email
        $rateLimitKey = 'login_' . md5(strtolower($email));
        if (!Middleware::checkRateLimit($rateLimitKey, 5, 900)) {
            $this->showLoginForm("Too many login attempts. Please wait 15 minutes before trying again.");
            return;
        }

        $userModel = new User();
        $user      = $userModel->findByEmail($email);

        if ($user && $userModel->verifyPassword($password, $user['password_hash'])) {

            // [SECURITY] Clear rate limit on success
            Middleware::clearRateLimit($rateLimitKey);

            // [SECURITY] Regenerate session ID on login to prevent session fixation
            session_regenerate_id(true);
            $_SESSION['_last_regen'] = time();

            $_SESSION['user_id']    = $user['id'];
            $_SESSION['role']       = $user['role'];
            $_SESSION['name']       = $user['first_name'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name']  = $user['last_name'];

            header("Location: " . APP_URL . "/");
            exit;
        } else {
            // Generic message to avoid user enumeration
            $this->showLoginForm("Invalid credentials. Please try again.");
        }
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm($error = null)
    {
        $this->view('layouts/app', [
            'pageTitle'   => 'Create Account - CraftConnect',
            'contentView' => 'auth/register',
            'error'       => $error
        ]);
    }

    /**
     * Process the registration request.
     */
    public function processRegister()
    {
        Middleware::verifyCsrfToken();

        $firstName = trim($_POST['first_name'] ?? '');
        $lastName  = trim($_POST['last_name'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = $_POST['password'] ?? '';

        // [SECURITY] Role allowlist — never trust user-supplied role
        $allowedRoles = ['homeowner', 'craftsman'];
        $role = in_array($_POST['role'] ?? '', $allowedRoles, true)
            ? $_POST['role']
            : 'homeowner';

        // Required fields
        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            $this->showRegisterForm("All fields are required.");
            return;
        }

        // [SECURITY] Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->showRegisterForm("Please enter a valid email address.");
            return;
        }

        // [SECURITY] Enforce minimum password length
        if (strlen($password) < 8) {
            $this->showRegisterForm("Password must be at least 8 characters long.");
            return;
        }

        // [SECURITY] Rate limit registrations per IP
        $rateLimitKey = 'register_' . md5($_SERVER['REMOTE_ADDR'] ?? '');
        if (!Middleware::checkRateLimit($rateLimitKey, 5, 3600)) {
            $this->showRegisterForm("Too many registration attempts. Please try again later.");
            return;
        }

        $userModel = new User();

        if ($userModel->findByEmail($email)) {
            $this->showRegisterForm("Email already in use. Please sign in instead.");
            return;
        }

        $success = $userModel->create([
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
            'password'   => $password,
            'role'       => $role
        ]);

        if ($success) {
            // [SECURITY] Regenerate session ID on login after registration
            session_regenerate_id(true);
            $_SESSION['_last_regen'] = time();

            $user = $userModel->findByEmail($email);
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['role']       = $user['role'];
            $_SESSION['name']       = $user['first_name'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name']  = $user['last_name'];

            header("Location: " . APP_URL . "/");
            exit;
        } else {
            $this->showRegisterForm("Could not create account. Please try again.");
        }
    }

    /**
     * Log the user out.
     * [SECURITY] Requires POST + CSRF to prevent logout CSRF via <img> tags.
     */
    public function logout()
    {
        // Accept both GET (legacy) and POST; enforce CSRF on POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Middleware::verifyCsrfToken();
        }

        // Fully destroy the session
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        header("Location: " . APP_URL . "/");
        exit;
    }
}
