<?php
class Database {
    private $conn;

    public function getConnection() {
        if ($this->conn) {
            return $this->conn;
        }

        // Get credentials from environment variables
        $host = getenv('DB_HOST');     
        $port = getenv('DB_PORT');     
        $db   = getenv('DB_NAME');     
        $user = getenv('DB_USER');     
        $pass = getenv('DB_PASS');      

        try {
            $this->conn = new PDO(
                "pgsql:host=$host;port=$port;dbname=$db",
                $user,
                $pass,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            die("Database connection failed. Please try again later.");
        }

        return $this->conn;
    }
}

// Use require_once when including this file elsewhere
$database = new Database();
$pdo = $database->getConnection();
