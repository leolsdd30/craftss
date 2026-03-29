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
            $resetModel = new PasswordReset();
            $rawToken   = $resetModel->createToken($email);

            $resetUrl   = APP_URL . '/reset-password?token=' . $rawToken;
            $subject    = 'Reset your Crafts password';
            $body       = '
                <p>Hi ' . htmlspecialchars($user['first_name']) . ',</p>
                <p>We received a request to reset your password. Click the link below (valid for 1 hour):</p>
                <p><a href="' . $resetUrl . '">' . $resetUrl . '</a></p>
                <p>If you did not request this, you can safely ignore this email.</p>
                <p>— The Crafts Team</p>
            ';

            Mailer::send($email, $subject, $body);

            // MOCK: expose the link on screen so the flow can be tested
            // without a real mailer. Remove $mockResetUrl once Resend is wired up.
            if (($_ENV['APP_ENV'] ?? 'production') !== 'production') {
                $mockResetUrl = $resetUrl;
            }
        }

        $this->view('layouts/app', [
            'pageTitle'    => 'Forgot Password - Crafts',
            'contentView'  => 'auth/forgot-password',
            'submitted'    => true,
            'mockResetUrl' => $mockResetUrl ?? null,
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
