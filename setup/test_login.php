<?php
/**
 * Login Test Script
 * Test if users can login with the inserted credentials
 * Access via: http://localhost/Tech-hub/setup/test_login.php
 */

require_once '../includes/auth.php';

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$testResults = [];
$testUsers = [
    [
        'email' => 'admin@techhub.com',
        'password' => 'admin123',
        'type' => 'admin'
    ],
    [
        'email' => 'john.doe@example.com',
        'password' => 'customer123',
        'type' => 'customer'
    ],
    [
        'email' => 'jane.smith@example.com',
        'password' => 'customer123',
        'type' => 'customer'
    ]
];

// Run tests
$auth = new Auth();

foreach ($testUsers as $user) {
    $result = $auth->login($user['email'], $user['password']);
    
    $testResults[] = [
        'email' => $user['email'],
        'expected_type' => $user['type'],
        'success' => $result['success'],
        'actual_type' => $result['userType'] ?? null,
        'message' => $result['message'] ?? 'Unknown error'
    ];
    
    // Logout after each test
    if ($result['success']) {
        $auth->logout();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Test - Tech-Hub</title>
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
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .card {
            background: white;
            border-radius: 15px;
            padding: 40px;
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
        
        .test-result {
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 10px;
            border-left: 5px solid;
        }
        
        .test-result.success {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        
        .test-result.failure {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }
        
        .test-result h3 {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .test-result .details {
            margin-left: 30px;
            margin-top: 10px;
        }
        
        .test-result .details p {
            margin: 5px 0;
            font-size: 14px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background: white;
            margin-left: 10px;
        }
        
        .summary {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            text-align: center;
        }
        
        .summary h2 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .summary .stats {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }
        
        .stat {
            text-align: center;
        }
        
        .stat h3 {
            font-size: 32px;
            color: #667eea;
        }
        
        .stat p {
            color: #666;
            font-size: 14px;
        }
        
        .actions {
            margin-top: 30px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .btn {
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            text-align: center;
            font-weight: 600;
            transition: transform 0.2s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>🧪 Login Test Results</h1>
            <p class="subtitle">Verifying user credentials in the database</p>
            
            <?php
            $successCount = 0;
            $failureCount = 0;
            
            foreach ($testResults as $result):
                if ($result['success']) {
                    $successCount++;
                } else {
                    $failureCount++;
                }
            ?>
                <div class="test-result <?php echo $result['success'] ? 'success' : 'failure'; ?>">
                    <h3>
                        <?php if ($result['success']): ?>
                            ✅ PASS
                        <?php else: ?>
                            ❌ FAIL
                        <?php endif; ?>
                        <?php echo htmlspecialchars($result['email']); ?>
                        <span class="status-badge" style="background: <?php echo $result['success'] ? '#28a745' : '#dc3545'; ?>; color: white;">
                            <?php echo strtoupper($result['expected_type']); ?>
                        </span>
                    </h3>
                    <div class="details">
                        <?php if ($result['success']): ?>
                            <p>✅ User authenticated successfully</p>
                            <p>👤 User Type: <?php echo ucfirst($result['actual_type']); ?></p>
                            <p>📧 Email: <?php echo htmlspecialchars($result['email']); ?></p>
                        <?php else: ?>
                            <p>❌ Login failed: <?php echo htmlspecialchars($result['message']); ?></p>
                            <p>📧 Email tested: <?php echo htmlspecialchars($result['email']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="summary">
                <h2>Test Summary</h2>
                <div class="stats">
                    <div class="stat">
                        <h3><?php echo $successCount; ?></h3>
                        <p>Successful</p>
                    </div>
                    <div class="stat">
                        <h3><?php echo $failureCount; ?></h3>
                        <p>Failed</p>
                    </div>
                    <div class="stat">
                        <h3><?php echo count($testResults); ?></h3>
                        <p>Total Tests</p>
                    </div>
                </div>
            </div>
            
            <div class="actions">
                <a href="add_users.php" class="btn btn-primary">➕ Add More Users</a>
                <a href="../login.php" class="btn btn-secondary">🔐 Try Login</a>
            </div>
        </div>
    </div>
</body>
</html>

