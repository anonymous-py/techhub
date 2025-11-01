<?php
// Start session and include required files
require_once 'auth.php';
require_once 'functions.php';

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

// Get the action
$action = $_POST['action'] ?? '';

// Validate action
if (!in_array($action, ['login', 'register'])) {
    redirectWithError('Invalid action');
}

// Create auth instance
$auth = new Auth();

try {
    switch ($action) {
        case 'login':
            handleLogin($auth);
            break;

        case 'register':
            handleRegistration($auth);
            break;

        default:
            redirectWithError('Invalid action');
    }
} catch (Exception $e) {
    error_log("Auth handler error: " . $e->getMessage());
    redirectWithError('An error occurred. Please try again.');
}

function handleLogin($auth)
{
    // Get and sanitize input
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($email) || empty($password)) {
        redirectWithError('Email and password are required', 'login.php');
    }

    // Attempt login
    $result = $auth->login($email, $password);

    if ($result['success']) {
        // Login successful - redirect based on user type
        if ($result['userType'] === 'admin') {
            redirect('../admin/index.php');
        } else {
            redirect('../index.php');
        }
    } else {
        // Login failed
        redirectWithError($result['message'], 'login.php');
    }
}

function handleRegistration($auth)
{
    // Get and sanitize input
    $firstName = sanitizeInput($_POST['firstName'] ?? '');
    $lastName = sanitizeInput($_POST['lastName'] ?? '');
    $otherNames = sanitizeInput($_POST['otherNames'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    // Basic validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        redirectWithError('All required fields must be filled', 'signup.php');
    }

    if ($password !== $confirmPassword) {
        redirectWithError('Passwords do not match', 'signup.php');
    }

    // Attempt registration
    $result = $auth->register($firstName, $lastName, $otherNames, $email, $password);

    if ($result['success']) {
        // Registration successful
        redirectWithSuccess($result['message'], 'login.php');
    } else {
        // Registration failed
        redirectWithError($result['message'], 'signup.php');
    }
}

function redirectWithError($message, $page = 'login.php')
{
    $url = "../$page?error=" . urlencode($message);
    redirect($url);
}

function redirectWithSuccess($message, $page = 'login.php')
{
    $url = "../$page?success=" . urlencode($message);
    redirect($url);
}
