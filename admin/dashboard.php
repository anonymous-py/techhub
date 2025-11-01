<?php
require_once '../includes/db_connection.php';
require_once '../includes/auth.php';
requireAdmin();

$pageTitle = 'Admin Dashboard';
$activeNav = 'dashboard';

// Get counts for dashboard
try {
    // Total Users (excluding admins)
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE userType = 'customer'");
    $totalUsers = $stmt->fetch()['count'];
    
    // Total Products
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $totalProducts = $stmt->fetch()['count'];
    
    // Total Orders
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders");
    $totalOrders = $stmt->fetch()['count'];
    
    // Total Revenue
    $stmt = $pdo->query("SELECT COALESCE(SUM(totalAmount), 0) as total FROM orders WHERE paymentStatus = 'completed'");
    $totalRevenue = number_format($stmt->fetch()['total'], 2);
    
    // Recent Orders
    $stmt = $pdo->query("
        SELECT o.orderId, o.totalAmount, o.orderStatus, o.createdAt, 
               u.firstName, u.lastName, u.email
        FROM orders o
        JOIN users u ON o.userId = u.userId
        ORDER BY o.createdAt DESC
        LIMIT 5
    ");
    $recentOrders = $stmt->fetchAll();
    
    // Low Stock Products
    $stmt = $pdo->query("
        SELECT productId, productName, stockQuantity 
        FROM products 
        WHERE stockQuantity < 10 
        ORDER BY stockQuantity ASC
        LIMIT 5
    ");
    $lowStockProducts = $stmt->fetchAll();
    
} catch (PDOException $e) {
    error_log("Dashboard error: " . $e->getMessage());
    $error = "Error loading dashboard data. Please try again later.";
}

// Include header
include_once '../includes/admin_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php include_once 'includes/sidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                        <i class="bi bi-calendar"></i> This week
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-4">
                    <div class="card bg-primary text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase">Total Users</h6>
                                    <h2 class="mb-0"><?php echo $totalUsers; ?></h2>
                                </div>
                                <div class="icon-circle bg-white-10">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="users.php" class="text-white">View all users <i class="fas fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase">Total Products</h6>
                                    <h2 class="mb-0"><?php echo $totalProducts; ?></h2>
                                </div>
                                <div class="icon-circle bg-white-10">
                                    <i class="fas fa-boxes"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="products.php" class="text-white">View all products <i class="fas fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card bg-warning text-dark h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase">Total Orders</h6>
                                    <h2 class="mb-0"><?php echo $totalOrders; ?></h2>
                                </div>
                                <div class="icon-circle bg-white-10 text-dark">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="orders.php" class="text-dark">View all orders <i class="fas fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-4">
                    <div class="card bg-info text-white h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-uppercase">Total Revenue</h6>
                                    <h2 class="mb-0">$<?php echo $totalRevenue; ?></h2>
                                </div>
                                <div class="icon-circle bg-white-10">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="reports.php" class="text-white">View reports <i class="fas fa-arrow-right ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders & Low Stock -->
            <div class="row">
                <!-- Recent Orders -->
                <div class="col-md-8 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Recent Orders</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order #</th>
                                            <th>Customer</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentOrders as $order): ?>
                                            <tr>
                                                <td>#<?php echo $order['orderId']; ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-light rounded-circle me-2">
                                                            <?php echo strtoupper(substr($order['firstName'], 0, 1) . substr($order['lastName'], 0, 1)); ?>
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold"><?php echo htmlspecialchars($order['firstName'] . ' ' . $order['lastName']); ?></div>
                                                            <small class="text-muted"><?php echo htmlspecialchars($order['email']); ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?php echo date('M d, Y', strtotime($order['createdAt'])); ?></td>
                                                <td>$<?php echo number_format($order['totalAmount'], 2); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php 
                                                        switch($order['orderStatus']) {
                                                            case 'pending': echo 'warning'; break;
                                                            case 'processing': echo 'info'; break;
                                                            case 'shipped': echo 'primary'; break;
                                                            case 'delivered': echo 'success'; break;
                                                            case 'cancelled': echo 'danger'; break;
                                                            default: echo 'secondary';
                                                        }
                                                    ?>">
                                                        <?php echo ucfirst($order['orderStatus']); ?>
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="order_details.php?id=<?php echo $order['orderId']; ?>" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="orders.php" class="btn btn-sm btn-link">View all orders</a>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Products -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0">Low Stock Alert</h6>
                            <span class="badge bg-danger"><?php echo count($lowStockProducts); ?> items</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <?php if (count($lowStockProducts) > 0): ?>
                                    <?php foreach ($lowStockProducts as $product): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1"><?php echo htmlspecialchars($product['productName']); ?></h6>
                                                    <small class="text-muted">Stock: <?php echo $product['stockQuantity']; ?> left</small>
                                                </div>
                                                <a href="edit_product.php?id=<?php echo $product['productId']; ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i> Restock
                                                </a>
                                            </div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <?php 
                                                    $percentage = min(($product['stockQuantity'] / 10) * 100, 100);
                                                    $bgClass = $percentage < 20 ? 'bg-danger' : ($percentage < 50 ? 'bg-warning' : 'bg-success');
                                                ?>
                                                <div class="progress-bar <?php echo $bgClass; ?>" role="progressbar" 
                                                     style="width: <?php echo $percentage; ?>%;" 
                                                     aria-valuenow="<?php echo $percentage; ?>" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center p-4">
                                        <div class="text-muted mb-2">
                                            <i class="fas fa-check-circle fa-2x text-success"></i>
                                        </div>
                                        <p class="mb-0">All products are well stocked</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="products.php?filter=low_stock" class="btn btn-sm btn-link">View all low stock items</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Recent Activity</h6>
                        </div>
                        <div class="card-body">
                            <div class="activity-feed">
                                <div class="activity-item d-flex">
                                    <div class="activity-badge bg-primary text-white rounded-circle me-3">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">New user registered</h6>
                                            <small class="text-muted">5 min ago</small>
                                        </div>
                                        <p class="mb-0 text-muted">John Doe registered as a new customer</p>
                                    </div>
                                </div>
                                <div class="activity-item d-flex mt-3">
                                    <div class="activity-badge bg-success text-white rounded-circle me-3">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">New order received</h6>
                                            <small class="text-muted">2 hours ago</small>
                                        </div>
                                        <p class="mb-0 text-muted">Order #1234 for $199.99</p>
                                    </div>
                                </div>
                                <!-- Add more activity items as needed -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include_once '../includes/admin_footer.php'; ?>