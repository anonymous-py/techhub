<?php
class Database {
    private $host = "localhost";
    private $db_name = "tech_hub_db";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch(PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
            // Don't echo in production, just log
            echo "Database connection failed. Please try again later.";
        }
        return $this->conn;
    }
}

// Create database connection
$database = new Database();
$db = $database->getConnection();

// Function to handle file uploads
function handleFileUpload($file, $uploadDir = "../assets/uploads/") {
    // Check if upload directory exists, create if not
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            return ["success" => false, "message" => "Failed to create upload directory."];
        }
    }
    
    // Check if file was uploaded without errors
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ["success" => false, "message" => "File upload error."];
    }
    
    $targetFile = $uploadDir . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    // Check if image file is actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if($check === false) {
        return ["success" => false, "message" => "File is not an image."];
    }
    
    // Check file size (5MB limit)
    if ($file["size"] > 5000000) {
        return ["success" => false, "message" => "File is too large. Maximum size is 5MB."];
    }
    
    // Allow certain file formats
    $allowedFormats = ["jpg", "png", "jpeg", "gif", "webp"];
    if(!in_array($imageFileType, $allowedFormats)) {
        return ["success" => false, "message" => "Only JPG, JPEG, PNG, GIF, and WebP files are allowed."];
    }
    
    // Generate unique filename
    $fileName = uniqid() . "_" . time() . "." . $imageFileType;
    $targetFile = $uploadDir . $fileName;
    
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return ["success" => true, "fileName" => $fileName];
    } else {
        return ["success" => false, "message" => "Error uploading file."];
    }
}
?>