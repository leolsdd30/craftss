<?php
namespace App\Models;

use App\Core\Model;

class PasswordReset extends Model
{
    /**
     * Create a new password reset token for the given email.
     * Deletes any existing token for that email first (one active token at a time).
     *
     * @param  string $email
     * @return string  The raw token (put in the reset URL)
     */
    public function createToken(string $email): string
    {
        // Delete any existing token for this email
        $this->execute(
            "DELETE FROM password_resets WHERE email = :email",
            ['email' => $email]
        );

        // Generate a cryptographically secure raw token
        $rawToken = bin2hex(random_bytes(32));

        // Store only the hashed version — raw token never touches the DB
        $hashedToken = hash('sha256', $rawToken);

        $this->execute(
            "INSERT INTO password_resets (email, token, expires_at)
             VALUES (:email, :token, DATE_ADD(NOW(), INTERVAL 1 HOUR))",
            [
                'email' => $email,
                'token' => $hashedToken,
            ]
        );

        return $rawToken;
    }

    /**
     * Find a valid (non-expired) reset record by raw token.
     * Hashes the incoming token and looks it up in the DB.
     *
     * @param  string $rawToken  Token from the URL query string
     * @return array|null        The DB record, or null if invalid/expired
     */
    public function findValidToken(string $rawToken): ?array
    {
        $hashedToken = hash('sha256', $rawToken);

        $stmt = $this->query(
            "SELECT * FROM password_resets
             WHERE token = :token
               AND expires_at > NOW()
             LIMIT 1",
            ['token' => $hashedToken]
        );

        $record = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $record ?: null;
    }

    /**
     * Delete all reset tokens for a given email.
     * Called immediately after a successful password reset.
     *
     * @param  string $email
     * @return bool
     */
    public function deleteToken(string $email): bool
    {
        return $this->execute(
            "DELETE FROM password_resets WHERE email = :email",
            ['email' => $email]
        );
    }
}
