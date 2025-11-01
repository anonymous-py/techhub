<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4">
            <h4 class="text-white">Tech-Hub Admin</h4>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo $activeNav === 'dashboard' ? 'active' : ''; ?>" href="dashboard.php">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
            </li>
            
            <li class="nav-item mt-3">
                <span class="sidebar-heading px-3 text-uppercase text-muted small">Shop</span>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo $activeNav === 'products' ? 'active' : ''; ?>" href="products.php">
                    <i class="fas fa-box me-2"></i>
                    Products
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo $activeNav === 'categories' ? 'active' : ''; ?>" href="categories.php">
                    <i class="fas fa-tags me-2"></i>
                    Categories
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo $activeNav === 'orders' ? 'active' : ''; ?>" href="orders.php">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Orders
                    <span class="badge bg-danger rounded-pill float-end">3</span>
                </a>
            </li>
            
            <li class="nav-item mt-3">
                <span class="sidebar-heading px-3 text-uppercase text-muted small">Users</span>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo $activeNav === 'customers' ? 'active' : ''; ?>" href="customers.php">
                    <i class="fas fa-users me-2"></i>
                    Customers
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo $activeNav === 'reviews' ? 'active' : ''; ?>" href="reviews.php">
                    <i class="fas fa-star me-2"></i>
                    Reviews
                </a>
            </li>
            
            <li class="nav-item mt-3">
                <span class="sidebar-heading px-3 text-uppercase text-muted small">Content</span>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo $activeNav === 'pages' ? 'active' : ''; ?>" href="pages.php">
                    <i class="fas fa-file-alt me-2"></i>
                    Pages
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo $activeNav === 'media' ? 'active' : ''; ?>" href="media.php">
                    <i class="fas fa-image me-2"></i>
                    Media Library
                </a>
            </li>
            
            <li class="nav-item mt-3">
                <span class="sidebar-heading px-3 text-uppercase text-muted small">Reports</span>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="reports.php?type=sales">
                    <i class="fas fa-chart-line me-2"></i>
                    Sales Reports
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="reports.php?type=inventory">
                    <i class="fas fa-warehouse me-2"></i>
                    Inventory
                </a>
            </li>
            
            <li class="nav-item mt-3">
                <span class="sidebar-heading px-3 text-uppercase text-muted small">Settings</span>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo $activeNav === 'settings' ? 'active' : ''; ?>" href="settings.php">
                    <i class="fas fa-cog me-2"></i>
                    Settings
                </a>
            </li>
        </ul>
    </div>
</nav>