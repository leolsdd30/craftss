<?php
namespace App\Database;

use PDO;
use PDOException;
use Exception;

class Database {
    // 1. Singleton Properties
    // We store the single active instance of the Database class here.
    private static $instance = null;
    
    // Stores the raw PHP Data Object (PDO) connection
    private $pdo;

    /**
     * Private constructor enforces the Singleton pattern.
     * It prevents other classes from using `new Database()`.
     */
    private function __construct() {
        // Fallback to defaults if $_ENV is not populated yet (e.g., in some CLI scripts)
        $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $port = $_ENV['DB_PORT'] ?? '3306';
        $db   = $_ENV['DB_DATABASE'] ?? 'craftconnect';
        $user = $_ENV['DB_USERNAME'] ?? 'root';
        $pass = $_ENV['DB_PASSWORD'] ?? '';
        $charset = 'utf8mb4';

        // Data Source Name (DSN) tells PDO exactly where and how to connect
        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
        
        // 2. Security Options
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Crash loudly on SQL errors so we can log them
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Return clean associative arrays, not duplicates
            PDO::ATTR_EMULATE_PREPARES   => false,                  // Forces MySQL to do real prepared statements (crucial to block SQL Injection)
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
            
            // 3. Timezone Synchronization
            // Force MySQL to use Algiers time (+01:00) permanently.
            // This guarantees that NOW() and CURRENT_TIMESTAMP inserts in SQL
            // perfectly match the user's real-world time, regardless of where the live server is hosted.
            $this->pdo->exec("SET time_zone = '+01:00'");
        } catch (PDOException $e) {
            // If the database is completely down, throw an Exception.
            // The global exception handler (in public/index.php) will catch this
            // and log it instead of printing the raw password on the website.
            throw new Exception("Database Connection Failed: " . $e->getMessage());
        }
    }

    /**
     * The core Singleton method.
     * Returns the one-and-only active database connection. 
     * If one hasn't been created yet, it boots exactly one.
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Returns the raw PDO object so Models can run prepare() and execute().
     */
    public function getConnection() {
        return $this->pdo;
    }
}
