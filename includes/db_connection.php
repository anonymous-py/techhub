<?php
require_once __DIR__ . '/../vendor/autoload.php'; // adjust if needed

use Dotenv\Dotenv;

if (!class_exists('Database')) {
    class Database {
        private $conn;

        public function getConnection() {
            if ($this->conn) {
                return $this->conn;
            }

            // Load .env only if it exists
            if (file_exists(__DIR__ . '/../.env')) {
                $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
                $dotenv->load();
            }

            $host = getenv('DB_HOST') ?: 'localhost';
            $port = getenv('DB_PORT') ?: 5432;
            $db   = getenv('DB_NAME') ?: 'techhub_db';
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('DB_PASS') ?: '';

            try {
                $this->conn = new PDO(
                    "pgsql:host=$host;port=$port;dbname=$db",
                    $user,
                    $pass,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $e) {
                // Only log errors — never echo or print anything
                error_log("Database connection error: " . $e->getMessage());
                $this->conn = null;
            }

            return $this->conn;
        }
    }
}

// Global connection (safe)
$database = new Database();
$db = $database->getConnection();
