<?php
namespace App\Services;

class Mailer
{
    /**
     * Send an email.
     *
     * RIGHT NOW: Mock mode — does nothing, returns true.
     * The reset link is shown directly on screen by the controller.
     *
     * LATER (Resend swap): Replace the body of this method only.
     * Nothing else in the codebase needs to change.
     *
     * Example Resend swap:
     *   $response = file_get_contents('https://api.resend.com/emails', false, stream_context_create([
     *       'http' => [
     *           'method'  => 'POST',
     *           'header'  => "Authorization: Bearer " . $_ENV['RESEND_API_KEY'] . "\r\n" .
     *                        "Content-Type: application/json\r\n",
     *           'content' => json_encode([
     *               'from'    => 'Crafts <noreply@crafts.com>',
     *               'to'      => [$to],
     *               'subject' => $subject,
     *               'html'    => $body,
     *           ]),
     *       ]
     *   ]));
     *   return $response !== false;
     *
     * @param string $to      Recipient email address
     * @param string $subject Email subject line
     * @param string $body    HTML email body
     * @return bool
     */
    public static function send(string $to, string $subject, string $body): bool
    {
        // Mock: no email is sent — reset link is displayed on screen instead.
        return true;
    }
}
