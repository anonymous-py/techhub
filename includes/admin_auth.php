<?php
/**
 * Admin Authentication Helper Functions
 * Checks admin status and enforces admin-only access
 */

require_once 'auth.php';

/**
 * Require admin login - redirect if not admin
 */
function requireAdmin() {
    safeSessionStart();
    
    $auth = new Auth();
    
    // Check if user is logged in
    if (!$auth->isLoggedIn()) {
        // Store the requested page for redirect after login
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        redirect('../login.php');
    }
    
    // Check if user is admin
    if (!$auth->isAdmin()) {
        // User is logged in but not admin - redirect to home
        redirect('../index.php');
    }
}

/**
 * Check if current user is admin
 * @return bool
 */
function isCurrentUserAdmin() {
    safeSessionStart();
    
    if (!isset($_SESSION['user_type'])) {
        return false;
    }
    
    return $_SESSION['user_type'] === 'admin';
}

/**
 * Get current admin user ID
 * @return int|null
 */
function getCurrentAdminId() {
    safeSessionStart();
    
    if (!isCurrentUserAdmin()) {
        return null;
    }
    
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current admin details
 * @return array|null
 */
function getCurrentAdmin() {
    safeSessionStart();
    
    if (!isCurrentUserAdmin()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'name' => $_SESSION['user_name'] ?? null,
        'email' => $_SESSION['user_email'] ?? null,
        'type' => $_SESSION['user_type'] ?? null,
    ];
}
