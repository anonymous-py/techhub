<?php
if (!isset($pageTitle)) {
    $pageTitle = 'Admin Panel';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Tech-Hub Admin</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/admin.css">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    
    <!-- Custom styles for this template -->
    <style>
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        }
        
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: .5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            font-weight: 500;
            color: #333;
            padding: 0.5rem 1rem;
            margin: 0.2rem 0.5rem;
            border-radius: 0.25rem;
        }
        
        .sidebar .nav-link.active {
            color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.1);
        }
        
        .sidebar .nav-link:hover {
            color: #0d6efd;
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        .sidebar .nav-link i {
            margin-right: 4px;
            color: #6c757d;
            width: 20px;
            text-align: center;
        }
        
        .sidebar .nav-link.active i {
            color: #0d6efd;
        }
        
        .icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        
        .bg-white-10 {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <button class="btn btn-link text-white order-1 order-md-0 me-4" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <a class="navbar-brand me-auto" href="dashboard.php">Tech-Hub Admin</a>
            
            <div class="d-flex align-items-center">
                <!-- Notifications Dropdown -->
                <div class="dropdown me-3">
                    <button class="btn btn-link text-white dropdown-toggle" type="button" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                            <span class="visually-hidden">unread notifications</span>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                        <li><h6 class="dropdown-header">Notifications</h6></li>
                        <li><a class="dropdown-item" href="#">New order received</a></li>
                        <li><a class="dropdown-item" href="#">New user registered</a></li>
                        <li><a class="dropdown-item" href="#">Product low in stock</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="#">View all notifications</a></li>
                    </ul>
                </div>
                
                <!-- User Dropdown -->
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://via.placeholder.com/30" alt="User" class="rounded-circle me-2">
                        <span class="d-none d-md-inline"><?php echo htmlspecialchars($_SESSION['first_name'] ?? 'Admin'); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="../profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="../settings.php"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Sidebar -->
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $activeNav === 'dashboard' ? 'active' : ''; ?>" href="dashboard.php">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $activeNav === 'products' ? 'active' : ''; ?>" href="products.php">
                                <i class="fas fa-box"></i>
                                Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $activeNav === 'categories' ? 'active' : ''; ?>" href="categories.php">
                                <i class="fas fa-tags"></i>
                                Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $activeNav === 'orders' ? 'active' : ''; ?>" href="orders.php">
                                <i class="fas fa-shopping-cart"></i>
                                Orders
                                <span class="badge bg-danger rounded-pill ms-1">3</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $activeNav === 'customers' ? 'active' : ''; ?>" href="customers.php">
                                <i class="fas fa-users"></i>
                                Customers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $activeNav === 'reviews' ? 'active' : ''; ?>" href="reviews.php">
                                <i class="fas fa-star"></i>
                                Reviews
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $activeNav === 'settings' ? 'active' : ''; ?>" href="settings.php">
                                <i class="fas fa-cog"></i>
                                Settings
                            </a>
                        </li>
                    </ul>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>Reports</span>
                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="reports.php?type=sales">
                                <i class="fas fa-chart-line"></i>
                                Sales Report
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="reports.php?type=inventory">
                                <i class="fas fa-warehouse"></i>
                                Inventory Report
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="margin-top: 60px;">
                <!-- Content will be loaded here -->