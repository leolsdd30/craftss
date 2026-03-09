<?php
namespace App\Database;

require_once __DIR__ . '/init.php';

use Exception;

class Migrations
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function up()
    {
        echo "Starting Migrations...\n";

        $queries = [
            "Users Table" => "
                CREATE TABLE IF NOT EXISTS users (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    first_name VARCHAR(100) NOT NULL,
                    last_name VARCHAR(100) NOT NULL,
                    email VARCHAR(255) UNIQUE NOT NULL,
                    password_hash VARCHAR(255) NOT NULL,
                    role ENUM('homeowner', 'craftsman', 'admin') DEFAULT 'homeowner',
                    phone_number VARCHAR(20) NULL,
                    wilaya VARCHAR(100) NULL,
                    profile_picture VARCHAR(255) DEFAULT 'default.png',
                    is_active BOOLEAN DEFAULT TRUE,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                );
            ",

            "Craftsmen Profiles Table" => "
                CREATE TABLE IF NOT EXISTS craftsmen_profiles (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT NOT NULL UNIQUE,
                    service_category VARCHAR(100) NOT NULL,
                    hourly_rate DECIMAL(10, 2) NOT NULL,
                    is_verified BOOLEAN DEFAULT FALSE,
                    portfolio_images JSON NULL,
                    latitude DECIMAL(10, 8) NULL,
                    longitude DECIMAL(11, 8) NULL,
                    bio TEXT NULL,
                    json_metadata JSON NULL,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                );
            ",

            "Job Postings Table" => "
                CREATE TABLE IF NOT EXISTS job_postings (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    posted_by_user_id INT NOT NULL,
                    service_category VARCHAR(100) NOT NULL,
                    title VARCHAR(255) NOT NULL,
                    description TEXT NOT NULL,
                    address TEXT NOT NULL,
                    budget_range VARCHAR(100) NULL,
                    status ENUM('open', 'assigned', 'completed', 'cancelled') DEFAULT 'open',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (posted_by_user_id) REFERENCES users(id) ON DELETE CASCADE
                );
            ",

            "Job Quotes Table" => "
                CREATE TABLE IF NOT EXISTS job_quotes (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    job_posting_id INT NOT NULL,
                    craftsman_id INT NOT NULL,
                    quoted_price DECIMAL(10, 2) NOT NULL,
                    cover_message TEXT NULL,
                    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (job_posting_id) REFERENCES job_postings(id) ON DELETE CASCADE,
                    FOREIGN KEY (craftsman_id) REFERENCES users(id) ON DELETE CASCADE
                );
            ",

            "Requests Bookings Table" => "
                CREATE TABLE IF NOT EXISTS requests_bookings (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    homeowner_id INT NOT NULL,
                    craftsman_id INT NOT NULL,
                    job_posting_id INT NULL,
                    description TEXT NOT NULL,
                    address TEXT NOT NULL, 
                    scheduled_date DATETIME NOT NULL,
                    quoted_price DECIMAL(10, 2) NULL,
                    status ENUM('requested', 'quoted', 'hired', 'completed', 'cancelled') DEFAULT 'requested',
                    completion_date DATETIME NULL, 
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (homeowner_id) REFERENCES users(id) ON DELETE CASCADE,
                    FOREIGN KEY (craftsman_id) REFERENCES users(id) ON DELETE CASCADE,
                    FOREIGN KEY (job_posting_id) REFERENCES job_postings(id) ON DELETE SET NULL
                );
            ",

            "Messages Table" => "
                CREATE TABLE IF NOT EXISTS messages (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    booking_id INT NOT NULL,
                    sender_id INT NOT NULL,
                    receiver_id INT NOT NULL,
                    message_body TEXT NOT NULL,
                    is_read BOOLEAN DEFAULT FALSE,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (booking_id) REFERENCES requests_bookings(id) ON DELETE CASCADE,
                    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
                    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
                );
            ",

            "Invoices Table" => "
                CREATE TABLE IF NOT EXISTS invoices (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    booking_id INT NOT NULL UNIQUE,
                    amount_due DECIMAL(10, 2) NOT NULL,
                    status ENUM('draft', 'sent', 'paid', 'overdue') DEFAULT 'draft',
                    issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (booking_id) REFERENCES requests_bookings(id) ON DELETE CASCADE
                );
            ",

            "Transactions Table" => "
                CREATE TABLE IF NOT EXISTS transactions (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    invoice_id INT NOT NULL,
                    stripe_charge_id VARCHAR(255) NULL, 
                    amount_paid DECIMAL(10, 2) NOT NULL,
                    status ENUM('pending', 'succeeded', 'failed') DEFAULT 'pending',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE
                );
            ",

            "Disputes Table" => "
                CREATE TABLE IF NOT EXISTS disputes (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    booking_id INT NOT NULL UNIQUE,
                    raised_by_user_id INT NOT NULL,
                    reason TEXT NOT NULL,
                    status ENUM('open', 'investigating', 'resolved_refund', 'resolved_payout') DEFAULT 'open',
                    admin_notes TEXT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (booking_id) REFERENCES requests_bookings(id) ON DELETE CASCADE,
                    FOREIGN KEY (raised_by_user_id) REFERENCES users(id) ON DELETE CASCADE
                );
            ",

            "Reviews Table" => "
                CREATE TABLE IF NOT EXISTS reviews (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    booking_id INT NOT NULL UNIQUE, 
                    homeowner_id INT NOT NULL,
                    craftsman_id INT NOT NULL,
                    star_rating INT NOT NULL CHECK (star_rating >= 1 AND star_rating <= 5),
                    comment TEXT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (booking_id) REFERENCES requests_bookings(id) ON DELETE CASCADE,
                    FOREIGN KEY (homeowner_id) REFERENCES users(id) ON DELETE CASCADE,
                    FOREIGN KEY (craftsman_id) REFERENCES users(id) ON DELETE CASCADE
                );
            "
        ];

        foreach ($queries as $name => $sql) {
            try {
                $this->pdo->exec($sql);
                echo "[OK] Created $name\n";
            }
            catch (Exception $e) {
                echo "[ERROR] Failed to create $name: " . $e->getMessage() . "\n";
            }
        }

        echo "Migrations Complete.\n";
    }

    public function down()
    {
        echo "Rolling back migrations...\n";

        // Reverse order of creation to avoid foreign key constraint failures
        $tables = [
            'reviews', 'disputes', 'transactions', 'invoices', 'messages', 'requests_bookings', 'job_quotes', 'job_postings', 'craftsmen_profiles', 'users'
        ];

        // Disable foreign key checks temporarily to make dropping tables cleaner
        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");

        foreach ($tables as $table) {
            try {
                $this->pdo->exec("DROP TABLE IF EXISTS {$table};");
                echo "[OK] Dropped {$table}\n";
            }
            catch (Exception $e) {
                echo "[ERROR] Failed to drop {$table}: " . $e->getMessage() . "\n";
            }
        }

        $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
        echo "Rollback Complete.\n";
    }
}

// Simple CLI runner
if (php_sapi_name() === 'cli') {
    // Only run this script directly if executed from command line
    if (isset($argv[0]) && basename($argv[0]) == 'Migrations.php') {
        $migration = new Migrations();

        $action = $argv[1] ?? 'up';

        if ($action === 'up') {
            $migration->up();
        }
        elseif ($action === 'down') {
            $migration->down();
        }
        elseif ($action === 'fresh') {
            $migration->down();
            $migration->up();
        }
        else {
            echo "Unknown command. Use: php Migrations.php [up|down|fresh]\n";
        }
    }
}
