<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\EmailVerification;
use App\Models\User;
use App\Auth\Middleware;
use App\Services\Mailer;

class EmailVerificationController extends Controller
{
    /**
     * Route: GET /verify-email?token=xxx
     */
    public function verify()
    {
        $token = trim($_GET['token'] ?? '');

        if (empty($token)) {
            $this->redirect(APP_URL . '/login?error=invalid_verification_link');
            return;
        }

        $verificationModel = new EmailVerification();
        $record = $verificationModel->findByToken($token);

        if (!$record) {
            $this->redirect(APP_URL . '/login?error=invalid_verification_link');
            return;
        }

        // Token found! Let's update the user.
        $userModel = new User();
        $user = $userModel->findByEmail($record['email']);

        if ($user) {
            // Update the user's email_verified_at field
            $userModel->executeQuery(
                "UPDATE users SET email_verified_at = CURRENT_TIMESTAMP WHERE id = :id",
                ['id' => $user['id']]
            );

            // If the user happens to be logged in right now, update their active session
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user['id']) {
                $_SESSION['email_verified_at'] = true; // We just need a truthy value
            }

            // Cleanup the token
            $verificationModel->deleteToken($record['email']);

            // Redirect them directly to their specific profile URL with the success message
            $profileUrl = APP_URL . '/profile';
            if (!empty($user['username'])) {
                $profileUrl .= '/' . urlencode($user['username']);
            }
            $this->redirect($profileUrl . '?success=email_verified');
            return;
        }

        // Just in case the user was deleted between signup and verification
        $this->redirect(APP_URL . '/login?error=user_not_found');
    }

    /**
     * Route: GET /verify-notice
     * Shows a screen telling the user they need to verify their email
     * before continuing with critical actions.
     */
    public function notice()
    {
        Middleware::requireLogin();
        
        // Prevent fully verified users from accidentally accessing the notice page
        if (!empty($_SESSION['email_verified_at'])) {
            $this->redirect(APP_URL . '/profile');
            return;
        }

        $this->view('layouts/app', [
            'pageTitle'   => 'Verify Email Required - CraftConnect',
            'contentView' => 'auth/verify-notice',
        ]);
    }

    /**
     * Route: POST /verify-resend
     * Resends the verification email, rate-limited to 10 seconds.
     */
    public function resend()
    {
        Middleware::requireLogin();
        Middleware::verifyCsrfToken();

        $lastSent = $_SESSION['last_verification_email'] ?? 0;
        
        // TODO: make this 5 min (300 seconds) in future
        if (time() - $lastSent < 10) {
            $this->redirect(APP_URL . '/verify-notice?error=wait');
            return;
        }

        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);

        // Already verified or missing user
        if (!$user || !empty($user['email_verified_at'])) {
            $this->redirect(APP_URL . '/profile');
            return;
        }

        $emailVerificationModel = new EmailVerification();
        $token = $emailVerificationModel->createToken($user['email']);
            
        // Build absolute URL
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $verifyUrl = $protocol . '://' . $host . APP_URL . '/verify-email?token=' . $token;
        
        $subject = 'Action Required: Verify your CraftConnect account';

        $body = '
        <div style="font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; line-height: 1.6;">
            <h2 style="color: #4f46e5;">Hello, ' . htmlspecialchars($user['first_name']) . '!</h2>
            <p>You requested a new verification link. To activate your profile and securely access the platform, please verify your email address.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="' . $verifyUrl . '" style="display: inline-block; padding: 12px 24px; background-color: #4f46e5; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold;">Verify My Email Address</a>
            </div>

            <p>If the button does not work, you can securely copy and paste the following link into your browser:</p>
            <p style="word-break: break-all; color: #6b7280; font-size: 0.9em;">' . $verifyUrl . '</p>
        </div>
        ';

        Mailer::send($user['email'], $subject, $body);

        // Update limit timer
        $_SESSION['last_verification_email'] = time();

        $this->redirect(APP_URL . '/verify-notice?success=resent');
    }
}
