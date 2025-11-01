<?php
/**
 * User Setup Script
 * Inserts sample users for testing login functionality
 * Run this script once to populate the database with test users
 */

require_once '../includes/db_connection.php';

// Start output buffering for cleaner output
ob_start();

echo "=== Tech-Hub User Setup Script ===\n\n";

// Sample users data
$users = [
    [
        'firstName' => 'Admin',
        'lastName' => 'User',
        'otherNames' => '',
        'email' => 'admin@techhub.com',
        'password' => 'admin123', // Will be hashed
        'userType' => 'admin',
        'profilePicture' => null
    ],
    [
        'firstName' => 'John',
        'lastName' => 'Doe',
        'otherNames' => '',
        'email' => 'john.doe@example.com',
        'password' => 'customer123',
        'userType' => 'customer',
        'profilePicture' => null
    ],
    [
        'firstName' => 'Jane',
        'lastName' => 'Smith',
        'otherNames' => '',
        'email' => 'jane.smith@example.com',
        'password' => 'customer123',
        'userType' => 'customer',
        'profilePicture' => null
    ],
    [
        'firstName' => 'Michael',
        'lastName' => 'Johnson',
        'otherNames' => 'David',
        'email' => 'michael.johnson@example.com',
        'password' => 'customer123',
        'userType' => 'customer',
        'profilePicture' => null
    ],
    [
        'firstName' => 'Sarah',
        'lastName' => 'Williams',
        'otherNames' => '',
        'email' => 'sarah.williams@example.com',
        'password' => 'customer123',
        'userType' => 'customer',
        'profilePicture' => null
    ],
    [
        'firstName' => 'Robert',
        'lastName' => 'Brown',
        'otherNames' => '',
        'email' => 'robert.brown@example.com',
        'password' => 'customer123',
        'userType' => 'customer',
        'profilePicture' => null
    ],
    [
        'firstName' => 'Emily',
        'lastName' => 'Davis',
        'otherNames' => 'Marie',
        'email' => 'emily.davis@example.com',
        'password' => 'customer123',
        'userType' => 'customer',
        'profilePicture' => null
    ],
    [
        'firstName' => 'David',
        'lastName' => 'Miller',
        'otherNames' => '',
        'email' => 'david.miller@example.com',
        'password' => 'customer123',
        'userType' => 'customer',
        'profilePicture' => null
    ]
];

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception("Failed to connect to database. Please check your database configuration.");
    }
    
    echo "Database connected successfully!\n\n";
    echo "Starting user insertion...\n\n";
    
    $insertedCount = 0;
    $skippedCount = 0;
    $failedCount = 0;
    
    foreach ($users as $userData) {
        $email = $userData['email'];
        $firstName = $userData['firstName'];
        $lastName = $userData['lastName'];
        
        // Check if user already exists
        $checkQuery = "SELECT userId FROM users WHERE email = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->execute([$email]);
        
        if ($checkStmt->rowCount() > 0) {
            echo "⏭️  SKIPPED: {$firstName} {$lastName} ({$email}) - User already exists\n";
            $skippedCount++;
            continue;
        }
        
        // Hash the password
        $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
        
        // Insert user
        $query = "INSERT INTO users (firstName, lastName, otherNames, email, passwordHash, userType, profilePicture) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $db->prepare($query);
        $result = $stmt->execute([
            $userData['firstName'],
            $userData['lastName'],
            $userData['otherNames'],
            $email,
            $hashedPassword,
            $userData['userType'],
            $userData['profilePicture']
        ]);
        
        if ($result) {
            echo "✅ INSERTED: {$firstName} {$lastName} ({$email}) - {$userData['userType']}\n";
            echo "   Password: {$userData['password']}\n\n";
            $insertedCount++;
        } else {
            echo "❌ FAILED: {$firstName} {$lastName} ({$email})\n";
            $failedCount++;
        }
    }
    
    echo "\n=== Summary ===\n";
    echo "Successfully inserted: {$insertedCount} users\n";
    echo "Skipped (already exist): {$skippedCount} users\n";
    echo "Failed: {$failedCount} users\n\n";
    
    echo "=== Test Credentials ===\n";
    echo "Admin Login:\n";
    echo "  Email: admin@techhub.com\n";
    echo "  Password: admin123\n\n";
    echo "Customer Login:\n";
    echo "  Email: john.doe@example.com\n";
    echo "  Password: customer123\n\n";
    echo "You can use any of the inserted customer emails with password: customer123\n\n";
    
    echo "✅ Setup completed successfully!\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    echo "\nTroubleshooting:\n";
    echo "1. Make sure the database 'tech_hub_db' exists\n";
    echo "2. Run the database schema from config/database.sql first\n";
    echo "3. Check database credentials in includes/db_connection.php\n\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

// Get and display the output
$output = ob_get_clean();
echo $output;
?>

