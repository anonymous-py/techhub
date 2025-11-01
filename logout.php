<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include auth class
require_once 'includes/auth.php';

// Create auth instance and logout
$auth = new Auth();
$auth->logout();

// Redirect to index page
header("Location: index.php");
exit();
