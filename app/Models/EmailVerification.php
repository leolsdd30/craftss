<?php
namespace App\Models;

use App\Core\Model;

class EmailVerification extends Model
{
    /**
     * Create a new verification token for a user.
     * Deletes any existing tokens for that email automatically.
     */
    public function createToken(string $email): string
    {
        // First, clean up old tokens
        $this->deleteToken($email);

        $token = bin2hex(random_bytes(32));

        $this->execute(
            "INSERT INTO email_verifications (email, token) VALUES (:email, :token)",
            ['email' => $email, 'token' => $token]
        );

        return $token;
    }

    /**
     * Find a record by its exact token string.
     */
    public function findByToken(string $token)
    {
        $stmt = $this->query("SELECT * FROM email_verifications WHERE token = :token LIMIT 1", [
            'token' => $token
        ]);
        return $stmt->fetch();
    }

    /**
     * Delete token(s) for a specific email
     */
    public function deleteToken(string $email): bool
    {
        return $this->execute(
            "DELETE FROM email_verifications WHERE email = :email",
            ['email' => $email]
        );
    }
}
