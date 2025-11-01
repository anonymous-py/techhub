<?php
/**
 * Web-based User Setup
 * Adds sample users through a web interface
 * Access via: http://localhost/Tech-hub/setup/add_users.php
 */

require_once '../includes/db_connection.php';
require_once '../includes/functions.php';

// Start session for messages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$message = '';
$messageType = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_users') {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        if (!$db) {
            throw new Exception("Failed to connect to database.");
        }
        
        $users = [
            [
                'firstName' => 'Admin',
                'lastName' => 'User',
                'email' => 'admin@techhub.com',
                'password' => 'admin123',
                'userType' => 'admin'
            ],
            [
                'firstName' => 'John',
                'lastName' => 'Doe',
                'email' => 'john.doe@example.com',
                'password' => 'customer123',
                'userType' => 'customer'
            ],
            [
                'firstName' => 'Jane',
                'lastName' => 'Smith',
                'email' => 'jane.smith@example.com',
                'password' => 'customer123',
                'userType' => 'customer'
            ],
            [
                'firstName' => 'Michael',
                'lastName' => 'Johnson',
                'otherNames' => 'David',
                'email' => 'michael.johnson@example.com',
                'password' => 'customer123',
                'userType' => 'customer'
            ]
        ];
        
        $inserted = 0;
        $skipped = 0;
        
        foreach ($users as $userData) {
            // Check if user exists
            $checkQuery = "SELECT userId FROM users WHERE email = ?";
            $checkStmt = $db->prepare($checkQuery);
            $checkStmt->execute([$userData['email']]);
            
            if ($checkStmt->rowCount() > 0) {
                $skipped++;
                continue;
            }
            
            // Insert user
            $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
            $query = "INSERT INTO users (firstName, lastName, otherNames, email, passwordHash, userType) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                $userData['firstName'],
                $userData['lastName'],
                $userData['otherNames'] ?? '',
                $userData['email'],
                $hashedPassword,
                $userData['userType']
            ]);
            
            $inserted++;
        }
        
        $message = "Users added successfully! Inserted: {$inserted}, Skipped: {$skipped}";
        $messageType = 'success';
        
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = 'error';
    }
}

// Get existing users count
try {
    $database = new Database();
    $db = $database->getConnection();
    
    $stmt = $db->query("SELECT COUNT(*) as count FROM users");
    $totalUsers = $stmt->fetch()['count'];
    
    $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE userType = 'admin'");
    $adminCount = $stmt->fetch()['count'];
    
    $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE userType = 'customer'");
    $customerCount = $stmt->fetch()['count'];
    
} catch (Exception $e) {
    $totalUsers = 0;
    $adminCount = 0;
    $customerCount = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Users - Tech-Hub</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-box {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .stat-box h3 {
            font-size: 32px;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .stat-box p {
            color: #666;
            font-size: 14px;
        }
        
        .credentials {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .credentials h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .credentials .cred-item {
            margin-bottom: 10px;
            padding: 10px;
            background: white;
            border-radius: 5px;
            border-left: 3px solid #667eea;
        }
        
        .credentials .cred-item strong {
            display: block;
            color: #333;
            margin-bottom: 5px;
        }
        
        .credentials .cred-item span {
            color: #666;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }
        
        .message {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        button {
            width: 100%;
            padding: 15px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 User Setup</h1>
        <p class="subtitle">Add test users to the Tech-Hub database</p>
        
        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="stats">
            <div class="stat-box">
                <h3><?php echo $totalUsers; ?></h3>
                <p>Total Users</p>
            </div>
            <div class="stat-box">
                <h3><?php echo $adminCount; ?></h3>
                <p>Admin Users</p>
            </div>
            <div class="stat-box">
                <h3><?php echo $customerCount; ?></h3>
                <p>Customers</p>
            </div>
        </div>
        
        <div class="credentials">
            <h3>📋 Test Credentials</h3>
            <div class="cred-item">
                <strong>Admin Account:</strong>
                <span>admin@techhub.com / admin123</span>
            </div>
            <div class="cred-item">
                <strong>Customer Account:</strong>
                <span>john.doe@example.com / customer123</span>
            </div>
            <div class="cred-item">
                <strong>Another Customer:</strong>
                <span>jane.smith@example.com / customer123</span>
            </div>
        </div>
        
        <form method="POST" action="">
            <input type="hidden" name="action" value="add_users">
            <button type="submit">➕ Add Sample Users</button>
        </form>
        
        <div class="footer">
            <p>This will add 1 admin and 3 customer accounts to your database.</p>
            <p><a href="../login.php">Go to Login Page</a> | <a href="../index.php">Go to Home</a></p>
        </div>
    </div>
</body>
</html>

