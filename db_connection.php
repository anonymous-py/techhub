<?php
class Database {
    private $host = "localhost";      // Your database host (usually 'localhost')
    private $db_name = "tech_hub_db"; // Your database name
    private $username = "your_username";  // Your database username
    private $password = "your_password";  // Your database password
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch(PDOException $exception) {
            error_log("Connection error: " . $e->getMessage());
            die("Database connection failed. Please try again later.");
        }
        return $this->conn;
    }
}
 
// Create database connection
$database = new Database();
$pdo = $database->getConnection();