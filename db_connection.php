<?php
require_once __DIR__ . '/vendor/autoload.php'; // Composer autoload for phpdotenv
use Dotenv\Dotenv;

if (!class_exists('Database')) {
    class Database {
        private $conn;

        public function getConnection() {
            if ($this->conn) {
                return $this->conn;
            }

            // Load environment variables (local or production)
            $envPath = __DIR__;
            if (file_exists($envPath . '/.env')) {
                $dotenv = Dotenv::createImmutable($envPath);
                $dotenv->load();
            }

            $host = getenv('DB_HOST') ?: 'localhost';
            $port = getenv('DB_PORT') ?: '5432';
            $db   = getenv('DB_NAME') ?: 'techhub_db';
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('DB_PASS') ?: '';

            try {
                $this->conn = new PDO(
                    "pgsql:host={$host};port={$port};dbname={$db}",
                    $user,
                    $pass,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $e) {
                // Log error silently to Render logs
                error_log("Database connection error: " . $e->getMessage());
                $this->conn = null;
            }

            return $this->conn;
        }
    }
}

// Initialize connection
$database = new Database();
$db = $database->getConnection();
