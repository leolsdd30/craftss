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
            'pageTitle' => 'Sign In - CraftConnect',
            'contentView' => 'auth/login',
            'error' => $error
        ]);
    }

    /**
     * Process the login request.
     */
    public function processLogin()
    {
        Middleware::verifyCsrfToken();

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->showLoginForm("Email and password are required.");
            return;
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && $userModel->verifyPassword($password, $user['password_hash'])) {
            // Success! Session is already started globally in app.php
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['first_name'];

            // Redirect to dashboard (or home for now)
            header("Location: " . APP_URL . "/");
            exit;
        }
        else {
            $this->showLoginForm("Invalid credentials. Please try again.");
        }
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm($error = null)
    {
        $this->view('layouts/app', [
            'pageTitle' => 'Create Account - CraftConnect',
            'contentView' => 'auth/register',
            'error' => $error
        ]);
    }

    /**
     * Process the registration request.
     */
    public function processRegister()
    {
        Middleware::verifyCsrfToken();

        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'homeowner';

        // Allowlist: only permit valid non-admin roles
        $allowedRoles = ['homeowner', 'craftsman'];
        if (!in_array($role, $allowedRoles, true)) {
            $role = 'homeowner';
        }

        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            $this->showRegisterForm("All fields are required.");
            return;
        }

        $userModel = new User();

        // Check if email already exists
        if ($userModel->findByEmail($email)) {
            $this->showRegisterForm("Email already in use. Please sign in instead.");
            return;
        }

        // Create the user
        $success = $userModel->create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => $password,
            'role' => $role
        ]);

        if ($success) {
            // Automatically log them in after registration
            $user = $userModel->findByEmail($email);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['first_name'];

            header("Location: " . APP_URL . "/");
            exit;
        }
        else {
            $this->showRegisterForm("Could not create user. Please try again.");
        }
    }

    /**
     * Log the user out and destroy the session.
     */
    public function logout()
    {
        Middleware::verifyCsrfToken();
        session_unset();
        session_destroy();

        header("Location: " . APP_URL . "/");
        exit;
    }
}
