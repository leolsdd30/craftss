<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\PasswordReset;
use App\Services\Mailer;
use App\Auth\Middleware;

class PasswordResetController extends Controller
{
    /**
     * GET /forgot-password
     * Show the "enter your email" form.
     */
    public function showForgotForm()
    {
        // Already logged in? No need to be here.
        if (isset($_SESSION['user_id'])) {
            $this->redirect(APP_URL . '/profile');
        }

        $this->view('layouts/app', [
            'pageTitle'   => 'Forgot Password - Crafts',
            'contentView' => 'auth/forgot-password',
        ]);
    }

    /**
     * POST /forgot-password
     * Validate the email, generate a token, (mock) send the reset link.
     */
    public function sendResetLink()
    {
        Middleware::verifyCsrfToken();

        $validator = new \App\Services\Validator();
        if (!$validator->validate($_POST, ['email' => 'required|email'])) {
            $this->view('layouts/app', [
                'pageTitle'   => 'Forgot Password - Crafts',
                'contentView' => 'auth/forgot-password',
                'error'       => $validator->getFirstError(),
            ]);
            return;
        }
        $email = trim($_POST['email']);

        $userModel = new User();
        $user      = $userModel->findByEmail($email);

        // Always generate the same "check your email" response whether the
        // user exists or not — prevents email enumeration attacks.
        $mockResetUrl = null;

        if ($user) {
            $resetModel = new \App\Models\PasswordReset();
            $rawToken   = $resetModel->createToken($email);

            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
            $host = $_SERVER['HTTP_HOST'];
            $resetUrl = $protocol . '://' . $host . APP_URL . '/reset-password?token=' . $rawToken;
            
            $subject    = 'Password Reset Request - CraftConnect';
            $body       = '
            <div style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; line-height: 1.6;">
                <h2 style="color: #4f46e5;">Hello ' . htmlspecialchars($user['first_name']) . ',</h2>
                <p>We received a secure request to reset the password associated with your account on the Crafts Platform.</p>
                
                <div style="text-align: center; margin: 30px 0;">
                    <a href="' . $resetUrl . '" style="display: inline-block; padding: 12px 24px; background-color: #4f46e5; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold;">Reset Your Password</a>
                </div>

                <p>This secure link will safely expire in 1 hour.</p>
                
                <p style="margin-top: 40px; font-size: 0.85em; color: #9ca3af;">
                    If you did not initiate this request, no action is required and your account remains secure.
                    <br><br>
                    Regards,<br>
                    <strong>The Crafts Platform Team</strong>
                </p>
            </div>
            ';

            Mailer::send($email, $subject, $body);
        }

        $this->view('layouts/app', [
            'pageTitle'    => 'Forgot Password - Crafts',
            'contentView'  => 'auth/forgot-password',
            'submitted'    => true
        ]);
    }

    /**
     * GET /reset-password?token=xxx
     * Validate the token and show the new-password form.
     */
    public function showResetForm()
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect(APP_URL . '/profile');
        }

        $rawToken = trim($_GET['token'] ?? '');

        if (empty($rawToken)) {
            $this->redirect(APP_URL . '/forgot-password?error=invalid_token');
        }

        $resetModel = new PasswordReset();
        $record     = $resetModel->findValidToken($rawToken);

        if (!$record) {
            // Token is invalid or expired
            $this->view('layouts/app', [
                'pageTitle'   => 'Reset Password - Crafts',
                'contentView' => 'auth/forgot-password',
                'error'       => 'This reset link is invalid or has expired. Please request a new one.',
            ]);
            return;
        }

        $this->view('layouts/app', [
            'pageTitle'   => 'Reset Password - Crafts',
            'contentView' => 'auth/reset-password',
            'token'       => $rawToken,
        ]);
    }

    /**
     * POST /reset-password
     * Validate token + passwords, update the password, delete the token.
     */
    public function processReset()
    {
        Middleware::verifyCsrfToken();

        $rawToken        = trim($_POST['token'] ?? '');
        $password        = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        // Re-validate token first (could have expired between page load and submit)
        $resetModel = new PasswordReset();
        $record     = $resetModel->findValidToken($rawToken);

        if (!$record) {
            $this->renderResetError($rawToken, 'This reset link is invalid or has expired. Please request a new one.');
            return;
        }

        // Validate passwords
        $validator = new \App\Services\Validator();
        if (!$validator->validate($_POST, ['password' => 'required|min:8'])) {
            $this->renderResetError($rawToken, $validator->getFirstError());
            return;
        }

        if ($password !== $passwordConfirm) {
            $this->renderResetError($rawToken, 'Passwords do not match.');
            return;
        }

        // Update password in DB
        $newHash   = password_hash($password, PASSWORD_BCRYPT);
        $userModel = new User();
        $userModel->executeQuery(
            "UPDATE users SET password_hash = :hash WHERE email = :email",
            ['hash' => $newHash, 'email' => $record['email']]
        );

        // Immediately invalidate the token — one-time use
        $resetModel->deleteToken($record['email']);

        // Redirect to login with success message
        $this->redirect(APP_URL . '/login?success=password_reset');
    }

    /**
     * Helper to render the reset form with an error message (DRY)
     */
    private function renderResetError($token, $errorMsg)
    {
        $this->view('layouts/app', [
            'pageTitle'   => 'Reset Password - Crafts',
            'contentView' => 'auth/reset-password',
            'token'       => $token,
            'error'       => $errorMsg,
        ]);
    }
}
