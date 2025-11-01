<?php
$currentUser = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;
$isAdmin = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
?>
<header class="header">
    <div class="container">
        <div class="headerContent">
            <div class="logo">
                <a href="index.php" class="logoLink">
                    <i class="fas fa-laptop-code logoIcon"></i>
                    <span class="logoText">Tech-Hub</span>
                </a>
            </div>

            <nav class="nav">
                <ul class="navList">
                    <li class="navItem"><a href="index.php" class="navLink">Home</a></li>
                    <li class="navItem"><a href="about.php" class="navLink">About</a></li>
                    <li class="navItem"><a href="services.php" class="navLink">Services</a></li>
                    <?php if ($isAdmin): ?>
                        <li class="navItem"><a href="admin/index.php" class="navLink">Admin</a></li>
                    <?php endif; ?>
                </ul>
            </nav>

            <div class="headerActions">
                <div class="cartIcon">
                    <a href="cart.php" class="cartLink">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cartCount" id="cartCount">0</span>
                    </a>
                </div>

                <div class="userMenu">
                    <?php if ($currentUser): ?>
                        <div class="userDropdown">
                            <button class="userBtn">
                                <i class="fas fa-user-circle"></i>
                                <span class="userName"><?php echo htmlspecialchars($currentUser); ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="dropdownContent">
                                <a href="#" class="dropdownLink"><i class="fas fa-user"></i> Profile</a>
                                <a href="#" class="dropdownLink"><i class="fas fa-shopping-bag"></i> Orders</a>
                                <a href="logout.php" class="dropdownLink"><i class="fas fa-sign-out-alt"></i> Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="authButtons">
                            <a href="login.php" class="authBtn loginBtn">Login</a>
                            <a href="signup.php" class="authBtn signupBtn">Sign Up</a>
                        </div>
                    <?php endif; ?>
                </div>

                <button class="mobileMenuBtn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="mobileNav" id="mobileNav">
            <ul class="mobileNavList">
                <li class="mobileNavItem"><a href="index.php" class="mobileNavLink">Home</a></li>
                <li class="mobileNavItem"><a href="about.php" class="mobileNavLink">About</a></li>
                <li class="mobileNavItem"><a href="services.php" class="mobileNavLink">Services</a></li>
                <?php if ($isAdmin): ?>
                    <li class="mobileNavItem"><a href="admin/index.php" class="mobileNavLink">Admin</a></li>
                <?php endif; ?>
                <?php if ($currentUser): ?>
                    <li class="mobileNavItem"><a href="#" class="mobileNavLink">Profile</a></li>
                    <li class="mobileNavItem"><a href="#" class="mobileNavLink">Orders</a></li>
                    <li class="mobileNavItem"><a href="logout.php" class="mobileNavLink">Logout</a></li>
                <?php else: ?>
                    <li class="mobileNavItem"><a href="login.php" class="mobileNavLink">Login</a></li>
                    <li class="mobileNavItem"><a href="signup.php" class="mobileNavLink">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</header>