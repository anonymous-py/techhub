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
    <title>Sign Up - Tech-Hub</title>
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
                        <h1>Create Account</h1>
                        <p>Join Tech-Hub today</p>
                    </div>

                    <form id="signupForm" class="authForm" method="POST" action="includes/auth_handler.php">
                        <input type="hidden" name="action" value="register">

                        <div class="formRow">
                            <div class="formGroup">
                                <label for="signupFirstName" class="formLabel">First Name *</label>
                                <input type="text" id="signupFirstName" name="firstName" class="formInput"
                                    placeholder="Enter first name" required>
                                <div class="errorMessage" id="signupFirstNameError"></div>
                            </div>

                            <div class="formGroup">
                                <label for="signupLastName" class="formLabel">Last Name *</label>
                                <input type="text" id="signupLastName" name="lastName" class="formInput"
                                    placeholder="Enter last name" required>
                                <div class="errorMessage" id="signupLastNameError"></div>
                            </div>
                        </div>

                        <div class="formGroup">
                            <label for="signupOtherNames" class="formLabel">Other Names</label>
                            <input type="text" id="signupOtherNames" name="otherNames" class="formInput"
                                placeholder="Enter other names (optional)">
                            <div class="errorMessage" id="signupOtherNamesError"></div>
                        </div>

                        <div class="formGroup">
                            <label for="signupEmail" class="formLabel">Email Address *</label>
                            <input type="email" id="signupEmail" name="email" class="formInput"
                                placeholder="Enter your email" required>
                            <div class="errorMessage" id="signupEmailError"></div>
                        </div>

                        <div class="formGroup">
                            <label for="signupPassword" class="formLabel">Password *</label>
                            <div class="passwordInputContainer">
                                <input type="password" id="signupPassword" name="password" class="formInput"
                                    placeholder="Create a password" required>
                                <button type="button" class="passwordToggle" id="signupPasswordToggle">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="passwordStrength" id="signupPasswordStrength">
                                <div class="strengthBar"></div>
                                <span class="strengthText">Password strength</span>
                            </div>
                            <div class="errorMessage" id="signupPasswordError"></div>
                        </div>

                        <div class="formGroup">
                            <label for="signupConfirmPassword" class="formLabel">Confirm Password *</label>
                            <div class="passwordInputContainer">
                                <input type="password" id="signupConfirmPassword" name="confirmPassword" class="formInput"
                                    placeholder="Confirm your password" required>
                                <button type="button" class="passwordToggle" id="signupConfirmPasswordToggle">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="errorMessage" id="signupConfirmPasswordError"></div>
                        </div>

                        <div class="formOptions">
                            <label class="checkboxContainer">
                                <input type="checkbox" name="agreeTerms" id="agreeTerms" required>
                                <span class="checkmark"></span>
                                I agree to the <a href="terms.php" class="textLink">Terms of Service</a> and <a href="privacy.php" class="textLink">Privacy Policy</a>
                            </label>
                            <div class="errorMessage" id="agreeTermsError"></div>
                        </div>

                        <button type="submit" class="authButton primaryButton" id="signupButton">
                            <span class="buttonText">Create Account</span>
                            <div class="buttonSpinner" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i>
                            </div>
                        </button>
                    </form>

                    <div class="authFooter">
                        <p>Already have an account? <a href="login.php" class="authLink">Sign in here</a></p>
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