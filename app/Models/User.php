<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    /**
     * Find a user by their unique email address
     * 
     * @param string $email
     * @return array|false 
     */
    public function findByEmail(string $email)
    {
        $stmt = $this->query("SELECT * FROM users WHERE email = :email LIMIT 1", [
            'email' => $email
        ]);

        return $stmt->fetch();
    }

    /**
     * Find a user by their unique username
     * 
     * @param string $username
     * @return array|false 
     */
    public function findByUsername(string $username)
    {
        $stmt = $this->query("SELECT * FROM users WHERE username = :username LIMIT 1", [
            'username' => $username
        ]);

        return $stmt->fetch();
    }

    /**
     * Find a user by their ID
     * 
     * @param int $id
     * @return array|false 
     */
    public function findById(int $id)
    {
        $stmt = $this->query("SELECT * FROM users WHERE id = :id LIMIT 1", [
            'id' => $id
        ]);

        return $stmt->fetch();
    }

    /**
     * Create a new user record in the database
     * 
     * @param array $data Contains first_name, last_name, email, password, role
     * @return bool
     */
    public function create(array $data): bool
    {
        // Hash the password securely before saving
        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);
        
        // Auto-generate a basic username if not provided
        if (empty($data['username'])) {
            $baseName = strtolower($data['first_name'] . $data['last_name']);
            // Remove anything that isn't an english letter or number
            $baseSlug = preg_replace('/[^a-z0-9]/', '', $baseName);
            
            // Ensure the username starts with a letter (regex requirement)
            if (empty($baseSlug) || !preg_match('/^[a-z]/', $baseSlug)) {
                $baseSlug = 'user' . $baseSlug;
            }
            
            // Append a 4-character random suffix to ensure it's highly likely unique
            $data['username'] = $baseSlug . '_' . substr(uniqid(), -4);
        }

        $sql = "INSERT INTO users (first_name, last_name, email, password_hash, role, username) 
                VALUES (:first_name, :last_name, :email, :password_hash, :role, :username)";

        return $this->execute($sql, [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password_hash' => $passwordHash,
            'role' => $data['role'] ?? 'homeowner',
            'username' => $data['username']
        ]);
    }

    /**
     * Helper to verify a password against a stored hash
     * 
     * @param string $password The plain text password
     * @param string $hash The hashed password from the DB
     * @return bool
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Public wrapper allowing controllers to execute ad-hoc updates on users table
     * 
     * @param string $sql
     * @param array $params
     * @return bool
     */
    public function executeQuery(string $sql, array $params = []): bool
    {
        return $this->execute($sql, $params);
    }
}
