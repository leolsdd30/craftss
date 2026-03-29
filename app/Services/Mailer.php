<?php
namespace App\Services;

class Mailer
{
    /**
     * Send an email using Brevo (Sendinblue) API.
     * Requires BREVO_API_KEY in the environment.
     *
     * @param string $to      Recipient email address
     * @param string $subject Email subject line
     * @param string $body    HTML email body
     * @return bool
     */
    public static function send(string $to, string $subject, string $body): bool
    {
        // Strictly use purely Environment variables. NEVER hardcode real API keys here.
        $apiKey = $_ENV['BREVO_API_KEY'] ?? '';
        $apiKey = trim(trim($apiKey, '"\''));
        
        // Grab the sender info from .env
        $senderEmail = $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@yourdomain.com';
        $senderName  = $_ENV['MAIL_FROM_NAME'] ?? 'Crafts Platform';

        if (empty($apiKey)) {
            error_log("CRITICAL: BREVO_API_KEY is missing in your .env file.");
            return false; // Cannot send without API key
        }

        $payload = [
            'sender'      => ['name' => $senderName, 'email' => $senderEmail],
            'to'          => [['email' => $to]],
            'subject'     => $subject,
            'htmlContent' => $body
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.brevo.com/v3/smtp/email");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        
        // Disable SSL verification for local XAMPP testing (common issue)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "api-key: " . $apiKey,
            "Content-Type: application/json",
            "Accept: application/json"
        ]);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new \Exception("Brevo Mailer cURL Network Error: " . $err);
            return false;
        }

        if ($httpcode >= 200 && $httpcode < 300) {
            return true;
        } else {
            throw new \Exception("Brevo API HTTP Error ($httpcode): " . $response . " | Hint: Make sure grgh1414@gmail.com is strictly verified as a Sender in your Brevo account dashboard.");
            return false;
        }
    }
}
