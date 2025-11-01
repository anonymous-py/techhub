-- Create Database
CREATE DATABASE tech_hub_db;
USE tech_hub_db;

-- Users Table (for both customers and admin)
CREATE TABLE users (
    userId INT PRIMARY KEY AUTO_INCREMENT,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    otherNames VARCHAR(50),
    email VARCHAR(100) UNIQUE NOT NULL,
    passwordHash VARCHAR(255) NOT NULL,
    profilePicture VARCHAR(255),
    userType ENUM('customer', 'admin') DEFAULT 'customer',
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categories Table
CREATE TABLE categories (
    categoryId INT PRIMARY KEY AUTO_INCREMENT,
    categoryName VARCHAR(50) NOT NULL UNIQUE,
    categoryDescription TEXT,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products Table
CREATE TABLE products (
    productId INT PRIMARY KEY AUTO_INCREMENT,
    productName VARCHAR(100) NOT NULL,
    productDescription TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stockQuantity INT NOT NULL DEFAULT 0,
    categoryId INT,
    productImage VARCHAR(255),
    productImages JSON, -- For multiple images
    rating DECIMAL(3,2) DEFAULT 0.00,
    totalRatings INT DEFAULT 0,
    isActive BOOLEAN DEFAULT TRUE,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoryId) REFERENCES categories(categoryId) ON DELETE SET NULL
);

-- Services Table
CREATE TABLE services (
    serviceId INT PRIMARY KEY AUTO_INCREMENT,
    serviceName VARCHAR(100) NOT NULL,
    serviceDescription TEXT NOT NULL,
    serviceImage VARCHAR(255),
    phoneNumber VARCHAR(20),
    whatsappNumber VARCHAR(20),
    priceRange VARCHAR(50),
    isActive BOOLEAN DEFAULT TRUE,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cart Table
CREATE TABLE cart (
    cartId INT PRIMARY KEY AUTO_INCREMENT,
    userId INT NOT NULL,
    productId INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    addedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_product (userId, productId), -- Prevent duplicate cart items
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE,
    FOREIGN KEY (productId) REFERENCES products(productId) ON DELETE CASCADE
);

-- Orders Table
CREATE TABLE orders (
    orderId INT PRIMARY KEY AUTO_INCREMENT,
    userId INT NOT NULL,
    totalAmount DECIMAL(10,2) NOT NULL,
    deliveryLocation VARCHAR(255) NOT NULL,
    deliveryFee DECIMAL(10,2) DEFAULT 0.00,
    orderStatus ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    paymentStatus ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    paystackReference VARCHAR(100),
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE
);

-- Order Items Table
CREATE TABLE order_items (
    orderItemId INT PRIMARY KEY AUTO_INCREMENT,
    orderId INT NOT NULL,
    productId INT NOT NULL,
    quantity INT NOT NULL,
    unitPrice DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (orderId) REFERENCES orders(orderId) ON DELETE CASCADE,
    FOREIGN KEY (productId) REFERENCES products(productId) ON DELETE CASCADE
);

-- Product Reviews Table
CREATE TABLE product_reviews (
    reviewId INT PRIMARY KEY AUTO_INCREMENT,
    productId INT NOT NULL,
    userId INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_product_review (userId, productId), -- One review per user per product
    FOREIGN KEY (productId) REFERENCES products(productId) ON DELETE CASCADE,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE
);

-- Website Comments Table (for About page)
CREATE TABLE website_comments (
    commentId INT PRIMARY KEY AUTO_INCREMENT,
    userId INT NOT NULL,
    commentText TEXT NOT NULL,
    commentType ENUM('feedback', 'complaint', 'suggestion') DEFAULT 'feedback',
    isApproved BOOLEAN DEFAULT FALSE,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE
);

-- Recently Viewed Products Table
CREATE TABLE recently_viewed (
    viewId INT PRIMARY KEY AUTO_INCREMENT,
    userId INT NOT NULL,
    productId INT NOT NULL,
    viewedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE,
    FOREIGN KEY (productId) REFERENCES products(productId) ON DELETE CASCADE
);

-- Insert Default Categories
INSERT INTO categories (categoryName, categoryDescription) VALUES
('Gaming Devices', 'Latest gaming consoles, accessories and gaming PCs'),
('Laptops', 'High-performance laptops for work and gaming'),
('Phones', 'Smartphones from various brands'),
('Smartwatches', 'Wearable technology and smartwatches'),
('TVs', 'Smart TVs and home entertainment systems'),
('Accessories', 'Tech accessories and peripherals');

-- Insert Default Admin User (password: admin123)
-- Using password_hash('admin123', PASSWORD_DEFAULT) in PHP instead of hardcoded hash
INSERT INTO users (firstName, lastName, email, passwordHash, userType) VALUES
('Admin', 'User', 'admin@techhub.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert Sample Services
INSERT INTO services (serviceName, serviceDescription, phoneNumber, whatsappNumber, priceRange) VALUES
('Web Design', 'Professional website design and development services', '+1234567890', '+1234567890', '$500 - $5000'),
('Video Editing', 'High-quality video editing and post-production', '+1234567891', '+1234567891', '$50 - $500'),
('Tech Support', '24/7 technical support and troubleshooting', '+1234567892', '+1234567892', '$25 - $100/hr'),
('App Development', 'Mobile and desktop application development', '+1234567893', '+1234567893', '$1000 - $10000');

-- Create indexes for better performance
CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_product_category ON products(categoryId);
CREATE INDEX idx_product_active ON products(isActive);
CREATE INDEX idx_order_user ON orders(userId);
CREATE INDEX idx_cart_user ON cart(userId);
CREATE INDEX idx_reviews_product ON product_reviews(productId);