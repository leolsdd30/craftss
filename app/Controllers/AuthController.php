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
        if (isset($_SESSION['user_id'])) {
            header("Location: " . APP_URL . "/profile");
            exit;
        }

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

        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $limitKey = 'login_attempts_' . md5($ip);
        if (!isset($_SESSION[$limitKey])) {
            $_SESSION[$limitKey] = ['count' => 0, 'time' => time()];
        }
        
        if (time() - $_SESSION[$limitKey]['time'] > 10) {
            $_SESSION[$limitKey] = ['count' => 0, 'time' => time()];
        }
        
        if ($_SESSION[$limitKey]['count'] >= 5) {
            $this->showLoginForm("Too many failed attempts. Please try again later.");
            return;
        }

        $validator = new \App\Services\Validator();
        if (!$validator->validate($_POST, [
            'email'    => 'required|email',
            'password' => 'required'
        ])) {
            $_SESSION[$limitKey]['count']++;
            $this->showLoginForm($validator->getFirstError());
            return;
        }

        $email = trim($_POST['email']);
        $password = $_POST['password']; // Passwords shouldn't be trimmed

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && $userModel->verifyPassword($password, $user['password_hash'])) {
            if (isset($user['is_active']) && $user['is_active'] == 0) {
                $_SESSION[$limitKey]['count']++;
                $this->showLoginForm("Your account has been deactivated.");
                return;
            }

            unset($_SESSION[$limitKey]);

            // Prevent session fixation attack (Issue #14)
            session_regenerate_id(true);

            // Success! Session is already started globally in app.php
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['first_name'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email_verified_at'] = $user['email_verified_at'];

            // Fetch is_verified for craftsman
            if ($user['role'] === 'craftsman') {
                $db = \App\Database\Database::getInstance()->getConnection();
                $stmt = $db->prepare("SELECT is_verified FROM craftsmen_profiles WHERE user_id = :uid");
                $stmt->execute(['uid' => $user['id']]);
                $result = $stmt->fetchColumn();
                $_SESSION['is_verified'] = $result ? true : false;
            } else {
                $_SESSION['is_verified'] = false;
            }

            // Redirect to role-based dashboard
            if ($user['role'] === 'craftsman') {
                header("Location: " . APP_URL . "/craftsman/dashboard");
            } elseif ($user['role'] === 'homeowner') {
                header("Location: " . APP_URL . "/homeowner/dashboard");
            } else {
                header("Location: " . APP_URL . "/");
            }
            exit;
        }
        else {
            $_SESSION[$limitKey]['count']++;
            $this->showLoginForm("Invalid credentials. Please try again.");
        }
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm($error = null)
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: " . APP_URL . "/profile");
            exit;
        }

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

        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $limitKey = 'register_attempts_' . md5($ip);
        if (!isset($_SESSION[$limitKey])) {
            $_SESSION[$limitKey] = ['count' => 0, 'time' => time()];
        }
        
        if (time() - $_SESSION[$limitKey]['time'] > 900) {
            $_SESSION[$limitKey] = ['count' => 0, 'time' => time()];
        }
        
        if ($_SESSION[$limitKey]['count'] >= 5) {
            $this->showRegisterForm("Too many attempts. Please try again later.");
            return;
        }
        $_SESSION[$limitKey]['count']++;

        $validator = new \App\Services\Validator();
        if (!$validator->validate($_POST, [
            'first_name' => 'required|min:2|max:50',
            'last_name'  => 'required|min:2|max:50',
            'email'      => 'required|email|max:100',
            'password'   => 'required|min:8',
            'role'       => 'required|in:homeowner,craftsman'
        ])) {
            $this->showRegisterForm($validator->getFirstError());
            return;
        }


        $firstName = trim($_POST['first_name']);
        $lastName  = trim($_POST['last_name']);
        $email     = trim($_POST['email']);
        $password  = $_POST['password'];
        $role      = trim($_POST['role']);

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
            unset($_SESSION[$limitKey]);

            // Prevent session fixation attack (Issue #14)
            session_regenerate_id(true);

            // Automatically log them in after registration
            $user = $userModel->findByEmail($email);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['first_name'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_verified'] = false; // Ensure verification session drops cleanly
            $_SESSION['email_verified_at'] = null; // Unverified at signup

            // Dispatch Welcome & Verification Email
            $emailVerificationModel = new \App\Models\EmailVerification();
            $token = $emailVerificationModel->createToken($email);
            
            // Build absolute URL to ensure email click tracking systems don't break on relative paths
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
            $host = $_SERVER['HTTP_HOST'];
            $verifyUrl = $protocol . '://' . $host . APP_URL . '/verify-email?token=' . $token;

            $subject = 'Action Required: Verify your CraftConnect account';

            $body = '
            <div style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; line-height: 1.6;">
                <h2 style="color: #4f46e5;">Welcome to CraftConnect, ' . htmlspecialchars($user['first_name']) . '!</h2>
                <p>Thank you for creating an account with us. To activate your profile and securely access the platform, we need to quickly verify your email address.</p>
                
                <div style="text-align: center; margin: 30px 0;">
                    <a href="' . $verifyUrl . '" style="display: inline-block; padding: 12px 24px; background-color: #4f46e5; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold;">Verify My Email Address</a>
                </div>

                <p>If the button does not work, you can securely copy and paste the following link into your browser:</p>
                <p style="word-break: break-all; color: #6b7280; font-size: 0.9em;">' . $verifyUrl . '</p>

                <p style="margin-top: 40px; font-size: 0.85em; color: #9ca3af;">
                    If you did not request this registration, please disregard this email.
                    <br><br>
                    Regards,<br>
                    <strong>The Crafts Platform Team</strong>
                </p>
            </div>
            ';
            \App\Services\Mailer::send($email, $subject, $body);

            // Redirect new user to their dashboard
            if ($user['role'] === 'craftsman') {
                header("Location: " . APP_URL . "/craftsman/dashboard");
            } elseif ($user['role'] === 'homeowner') {
                header("Location: " . APP_URL . "/homeowner/dashboard");
            } else {
                header("Location: " . APP_URL . "/");
            }
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
        session_unset();
        session_destroy();

        header("Location: " . APP_URL . "/");
        exit;
    }
}