<?php
// Start session and include functions
require_once 'includes/functions.php';
safeSessionStart();

// Redirect if already logged in
if (isLoggedIn()) {
    if (isAdmin()) {
        redirect('admin/index.php');
    } else {
        redirect('index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tech-Hub</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animations.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="auth-page">
    <?php include 'includes/header.php'; ?>

    <main class="mainContent">
        <div class="container">
            <div class="authContainer">
                <div class="authCard slideInUp">
                    <div class="authHeader">
                        <h1>Welcome Back</h1>
                        <p>Sign in to your Tech-Hub account</p>
                    </div>

                    <form id="loginForm" class="authForm" method="POST" action="includes/auth_handler.php">
                        <input type="hidden" name="action" value="login">

                        <div class="formGroup">
                            <label for="loginEmail" class="formLabel">Email Address</label>
                            <input type="email" id="loginEmail" name="email" class="formInput"
                                placeholder="Enter your email" required>
                            <div class="errorMessage" id="loginEmailError"></div>
                        </div>

                        <div class="formGroup">
                            <label for="loginPassword" class="formLabel">Password</label>
                            <div class="passwordInputContainer">
                                <input type="password" id="loginPassword" name="password" class="formInput"
                                    placeholder="Enter your password" required>
                                <button type="button" class="passwordToggle" id="loginPasswordToggle">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="errorMessage" id="loginPasswordError"></div>
                        </div>

                        <div class="formOptions">
                            <label class="checkboxContainer">
                                <input type="checkbox" name="remember" id="remember">
                                <span class="checkmark"></span>
                                Remember me
                            </label>
                            <a href="forgot_password.php" class="forgotPassword">Forgot Password?</a>
                        </div>

                        <button type="submit" class="authButton primaryButton" id="loginButton">
                            <span class="buttonText">Sign In</span>
                            <div class="buttonSpinner" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </button>
                    </form>

                    <div class="authFooter">
                        <p>Don't have an account? <a href="signup.php" class="authLink">Sign up here</a></p>
                    </div>

                    <?php
                    // Display error messages
                    if (isset($_GET['error'])):
                        $errorMessage = htmlspecialchars($_GET['error']);
                    ?>
                        <div class="alert alertError fadeIn">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo $errorMessage; ?>
                        </div>
                    <?php endif; ?>

                    <?php
                    // Display success messages
                    if (isset($_GET['success'])):
                        $successMessage = htmlspecialchars($_GET['success']);
                    ?>
                        <div class="alert alertSuccess fadeIn">
                            <i class="fas fa-check-circle"></i>
                            <?php echo $successMessage; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
    <script src="js/validation.js"></script>
</body>

</html>