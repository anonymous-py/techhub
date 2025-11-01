<?php
// Include database connection
require_once 'db_connection.php';

// Basic utility functions
function sanitizeInput($data)
{
    if (!isset($data)) {
        return '';
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function formatPrice($price)
{
    if (!is_numeric($price)) {
        return '$0.00';
    }
    return '$' . number_format($price, 2);
}

function getCategoryName($categoryId)
{
    global $db;
    if (!$db) {
        return 'Uncategorized';
    }

    try {
        $query = "SELECT categoryName FROM categories WHERE categoryId = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$categoryId]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        return $category ? $category['categoryName'] : 'Uncategorized';
    } catch (PDOException $e) {
        error_log("Database error in getCategoryName: " . $e->getMessage());
        return 'Uncategorized';
    }
}

// Add this function to includes/functions.php (after the existing functions)

function getSampleProducts()
{
    return [
        [
            'id' => 1,
            'name' => "Gaming Laptop Pro",
            'description' => "High-performance gaming laptop with RTX 4080 and Intel i9 processor",
            'price' => "$2,499.99",
            'category' => "Laptops",
            'categoryId' => 2,
            'image' => "https://images.unsplash.com/photo-1603302576837-37561b2e2302?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
            'rating' => 4.5,
            'reviewCount' => 128
        ],
        [
            'id' => 2,
            'name' => "Smartphone X",
            'description' => "Latest smartphone with advanced camera and 5G connectivity",
            'price' => "$999.99",
            'category' => "Phones",
            'categoryId' => 3,
            'image' => "https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
            'rating' => 4.2,
            'reviewCount' => 89
        ],
        [
            'id' => 3,
            'name' => "Gaming Console Elite",
            'description' => "Next-gen gaming console with 4K gaming and VR support",
            'price' => "$499.99",
            'category' => "Gaming Devices",
            'categoryId' => 1,
            'image' => "https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
            'rating' => 4.8,
            'reviewCount' => 256
        ],
        [
            'id' => 4,
            'name' => "Smart Watch Pro",
            'description' => "Advanced smartwatch with health monitoring and GPS",
            'price' => "$349.99",
            'category' => "Smartwatches",
            'categoryId' => 4,
            'image' => "https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
            'rating' => 4.3,
            'reviewCount' => 67
        ],
        [
            'id' => 5,
            'name' => "4K Ultra HD TV",
            'description' => "65-inch 4K Smart TV with HDR and streaming apps",
            'price' => "$899.99",
            'category' => "TVs",
            'categoryId' => 5,
            'image' => "https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
            'rating' => 4.6,
            'reviewCount' => 142
        ],
        [
            'id' => 6,
            'name' => "Wireless Headphones",
            'description' => "Noise-cancelling wireless headphones with 30hr battery",
            'price' => "$299.99",
            'category' => "Accessories",
            'categoryId' => 6,
            'image' => "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
            'rating' => 4.4,
            'reviewCount' => 203
        ],
        [
            'id' => 7,
            'name' => "Mechanical Keyboard",
            'description' => "RGB mechanical keyboard with customizable switches",
            'price' => "$129.99",
            'category' => "Accessories",
            'categoryId' => 6,
            'image' => "https://images.unsplash.com/photo-1541140532154-b024d705b90a?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
            'rating' => 4.7,
            'reviewCount' => 94
        ],
        [
            'id' => 8,
            'name' => "Gaming Mouse",
            'description' => "High-precision gaming mouse with programmable buttons",
            'price' => "$79.99",
            'category' => "Accessories",
            'categoryId' => 6,
            'image' => "https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
            'rating' => 4.1,
            'reviewCount' => 56
        ]
    ];
}

// Add PHP version of getStarRating function
function getStarRating($rating)
{
    if (!is_numeric($rating) || $rating < 0 || $rating > 5) {
        $rating = 0;
    }

    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

    $stars = '';

    // Full stars
    for ($i = 0; $i < $fullStars; $i++) {
        $stars .= '<i class="fas fa-star"></i>';
    }

    // Half star
    if ($halfStar) {
        $stars .= '<i class="fas fa-star-half-alt"></i>';
    }

    // Empty stars
    for ($i = 0; $i < $emptyStars; $i++) {
        $stars .= '<i class="far fa-star"></i>';
    }

    return $stars;
}

// Debug function
function consoleLog($data)
{
    if (is_array($data) || is_object($data)) {
        $data = json_encode($data);
    }
    echo '<script>';
    echo 'console.log(' . json_encode($data) . ')';
    echo '</script>';
}

// Redirect function
function redirect($url)
{
    header("Location: " . $url);
    exit();
}

// Check if user is logged in
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin()
{
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

// Safe session start function - ADD THIS FUNCTION
function safeSessionStart()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}
