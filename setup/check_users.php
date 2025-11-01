<?php
/**
 * Quick User Check
 * Displays all users in the database
 * Access via: http://localhost/Tech-hub/setup/check_users.php
 */

require_once '../includes/db_connection.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $stmt = $db->query("SELECT userId, firstName, lastName, email, userType, createdAt FROM users ORDER BY createdAt DESC");
    $users = $stmt->fetchAll();
    
} catch (Exception $e) {
    $users = [];
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List - Tech-Hub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 { color: #333; margin-bottom: 30px; }
        .user-count { color: #667eea; font-size: 24px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; font-weight: 600; color: #333; }
        .badge { padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge.admin { background: #dc3545; color: white; }
        .badge.customer { background: #28a745; color: white; }
        .actions { margin-top: 30px; display: flex; gap: 15px; }
        .btn { padding: 15px 30px; border-radius: 8px; text-decoration: none; text-align: center; font-weight: 600; }
        .btn-primary { background: #667eea; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .empty { text-align: center; padding: 40px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h1>👥 Users in Database</h1>
        <div class="user-count">Total Users: <?php echo count($users); ?></div>
        
        <?php if (empty($users)): ?>
            <div class="empty">
                <h3>No users found in database</h3>
                <p>Run the setup script to add users</p>
                <div class="actions">
                    <a href="add_users.php" class="btn btn-primary">➕ Add Users</a>
                </div>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['userId']; ?></td>
                            <td><?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="badge <?php echo $user['userType']; ?>">
                                    <?php echo ucfirst($user['userType']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($user['createdAt'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="actions">
                <a href="add_users.php" class="btn btn-primary">➕ Add More Users</a>
                <a href="test_login.php" class="btn btn-secondary">🧪 Test Login</a>
                <a href="../login.php" class="btn btn-secondary">🔐 Login Page</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

