<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db_connection.php';

class Auth
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function register($firstName, $lastName, $otherNames, $email, $password)
    {
        // Validate input
        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            return ["success" => false, "message" => "All fields are required."];
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["success" => false, "message" => "Invalid email format."];
        }

        // Check password length
        if (strlen($password) < 6) {
            return ["success" => false, "message" => "Password must be at least 6 characters long."];
        }

        try {
            // Check if user already exists
            $checkQuery = "SELECT userId FROM users WHERE email = ?";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->execute([$email]);

            if ($checkStmt->rowCount() > 0) {
                return ["success" => false, "message" => "Email already registered."];
            }

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user with default 'customer' userType
            $query = "INSERT INTO users (firstName, lastName, otherNames, email, passwordHash, userType) 
                  VALUES (?, ?, ?, ?, ?, 'customer')";
            $stmt = $this->db->prepare($query);

            if ($stmt->execute([$firstName, $lastName, $otherNames, $email, $hashedPassword])) {
                return ["success" => true, "message" => "Registration successful. You can now login."];
            }

            return ["success" => false, "message" => "Registration failed. Please try again."];
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            return ["success" => false, "message" => "Database error. Please try again."];
        }
    }

    public function login($email, $password)
    {
        // Validate input
        if (empty($email) || empty($password)) {
            return ["success" => false, "message" => "Email and password are required."];
        }

        try {
            $query = "SELECT userId, firstName, lastName, email, passwordHash, userType, profilePicture 
                      FROM users WHERE email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$email]);

            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($password, $user['passwordHash'])) {
                    $_SESSION['user_id'] = $user['userId'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_type'] = $user['userType'];
                    $_SESSION['user_name'] = $user['firstName'] . ' ' . $user['lastName'];
                    $_SESSION['profile_picture'] = $user['profilePicture'];

                    return ["success" => true, "userType" => $user['userType']];
                }
            }

            return ["success" => false, "message" => "Invalid email or password."];
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return ["success" => false, "message" => "Database error. Please try again."];
        }
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public function isAdmin()
    {
        return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
    }

    public function logout()
    {
        // Clear all session variables
        $_SESSION = array();

        // Delete session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destroy the session
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        
        // Return success - let the caller handle the redirect
        return true;
    }

    // Get current user info
    public function getCurrentUser()
    {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'email' => $_SESSION['user_email'],
                'name' => $_SESSION['user_name'],
                'type' => $_SESSION['user_type'],
                'profile_picture' => $_SESSION['profile_picture'] ?? null
            ];
        }
        return null;
    }
}

// Handle GET requests for logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $auth = new Auth();
    $auth->logout();
    header("Location: ../index.php");
    exit();
}
?>