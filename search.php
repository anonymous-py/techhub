<?php
session_start();
require_once 'includes/db_connection.php';
require_once 'includes/functions.php';

$searchQuery = isset($_GET['q']) ? sanitizeInput($_GET['q']) : '';
$categoryFilter = isset($_GET['category']) ? sanitizeInput($_GET['category']) : '';
$priceRange = isset($_GET['price']) ? sanitizeInput($_GET['price']) : '';
$sortBy = isset($_GET['sort']) ? sanitizeInput($_GET['sort']) : 'relevant';

// In production, this would query the database
// For now, we'll use sample data
$searchResults = [];
if (!empty($searchQuery)) {
    $searchResults = getSampleProducts(); // This would be filtered by search in production
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Tech-Hub</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animations.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <main class="mainContent">
        <div class="container">
            <!-- Search Header -->
            <div class="searchResultsHeader">
                <h1>Search Results</h1>
                <?php if (!empty($searchQuery)): ?>
                    <p>Search results for "<strong><?php echo htmlspecialchars($searchQuery); ?></strong>"</p>
                <?php endif; ?>

                <div class="resultsInfo">
                    <span id="resultsCount"><?php echo count($searchResults); ?> products found</span>
                    <div class="viewControls">
                        <button class="viewBtn active" data-view="grid"><i class="fas fa-th"></i></button>
                        <button class="viewBtn" data-view="list"><i class="fas fa-list"></i></button>
                    </div>
                </div>
            </div>

            <div class="searchPageContent">
                <!-- Filters Sidebar -->
                <aside class="filtersSidebar">
                    <div class="filtersHeader">
                        <h3>Filters</h3>
                        <button class="clearFilters">Clear All</button>
                    </div>

                    <!-- TODO: Implement Category Filter -->
                    <div class="filterGroup">
                        <h4 class="filterTitle">Category</h4>
                        <div class="filterOptions">
                            <label class="filterOption">
                                <input type="checkbox" name="category" value="all" checked>
                                <span class="checkmark"></span>
                                All Categories
                            </label>
                            <label class="filterOption">
                                <input type="checkbox" name="category" value="1">
                                <span class="checkmark"></span>
                                Gaming Devices
                            </label>
                            <label class="filterOption">
                                <input type="checkbox" name="category" value="2">
                                <span class="checkmark"></span>
                                Laptops
                            </label>
                            <label class="filterOption">
                                <input type="checkbox" name="category" value="3">
                                <span class="checkmark"></span>
                                Phones
                            </label>
                            <!-- More categories would be dynamically loaded -->
                        </div>
                    </div>

                    <!-- TODO: Implement Price Range Filter -->
                    <div class="filterGroup">
                        <h4 class="filterTitle">Price Range</h4>
                        <div class="filterOptions">
                            <label class="filterOption">
                                <input type="radio" name="price" value="all" checked>
                                <span class="checkmark radio"></span>
                                All Prices
                            </label>
                            <label class="filterOption">
                                <input type="radio" name="price" value="0-100">
                                <span class="checkmark radio"></span>
                                Under $100
                            </label>
                            <label class="filterOption">
                                <input type="radio" name="price" value="100-500">
                                <span class="checkmark radio"></span>
                                $100 - $500
                            </label>
                            <label class="filterOption">
                                <input type="radio" name="price" value="500-1000">
                                <span class="checkmark radio"></span>
                                $500 - $1000
                            </label>
                            <label class="filterOption">
                                <input type="radio" name="price" value="1000+">
                                <span class="checkmark radio"></span>
                                Over $1000
                            </label>
                        </div>
                    </div>

                    <!-- TODO: Implement Rating Filter -->
                    <div class="filterGroup">
                        <h4 class="filterTitle">Customer Rating</h4>
                        <div class="filterOptions">
                            <label class="filterOption">
                                <input type="radio" name="rating" value="all" checked>
                                <span class="checkmark radio"></span>
                                All Ratings
                            </label>
                            <label class="filterOption">
                                <input type="radio" name="rating" value="4">
                                <span class="checkmark radio"></span>
                                <span class="ratingStars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i> & Up
                                </span>
                            </label>
                            <label class="filterOption">
                                <input type="radio" name="rating" value="3">
                                <span class="checkmark radio"></span>
                                <span class="ratingStars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                    <i class="far fa-star"></i> & Up
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- TODO: Implement Availability Filter -->
                    <div class="filterGroup">
                        <h4 class="filterTitle">Availability</h4>
                        <div class="filterOptions">
                            <label class="filterOption">
                                <input type="checkbox" name="availability" value="in_stock" checked>
                                <span class="checkmark"></span>
                                In Stock
                            </label>
                            <label class="filterOption">
                                <input type="checkbox" name="availability" value="out_of_stock">
                                <span class="checkmark"></span>
                                Out of Stock
                            </label>
                        </div>
                    </div>

                    <button class="applyFiltersBtn">Apply Filters</button>
                </aside>

                <!-- Results Section -->
                <section class="resultsSection">
                    <!-- Sort Options -->
                    <div class="sortOptions">
                        <label for="sortSelect">Sort by:</label>
                        <select id="sortSelect" name="sort">
                            <option value="relevant" <?php echo $sortBy === 'relevant' ? 'selected' : ''; ?>>Most Relevant</option>
                            <option value="price_low" <?php echo $sortBy === 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                            <option value="price_high" <?php echo $sortBy === 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                            <option value="rating" <?php echo $sortBy === 'rating' ? 'selected' : ''; ?>>Highest Rated</option>
                            <option value="newest" <?php echo $sortBy === 'newest' ? 'selected' : ''; ?>>Newest First</option>
                        </select>
                    </div>

                    <!-- Products Grid/List -->
                    <div class="productsContainer view-grid" id="productsContainer">
                        <?php if (empty($searchQuery)): ?>
                            <div class="noSearchQuery">
                                <i class="fas fa-search fa-3x"></i>
                                <h3>Enter a search term to find products</h3>
                                <p>Try searching for laptops, phones, gaming devices, or any other tech products</p>
                            </div>
                        <?php elseif (empty($searchResults)): ?>
                            <div class="noResults">
                                <i class="fas fa-search fa-3x"></i>
                                <h3>No products found</h3>
                                <p>Try adjusting your search or filter criteria</p>
                                <div class="suggestions">
                                    <h4>Suggestions:</h4>
                                    <ul>
                                        <li>Check your spelling</li>
                                        <li>Try more general keywords</li>
                                        <li>Browse through categories</li>
                                    </ul>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="productsGrid" id="productsGrid">
                                <!-- Products will be loaded by JavaScript -->
                            </div>
                            <script id="productsData" type="application/json">
                            <?php echo json_encode($searchResults); ?>
                            </script>

                            <!-- TODO: Implement Pagination -->
                            <div class="pagination">
                                <button class="paginationBtn disabled">Previous</button>
                                <span class="paginationInfo">Page 1 of 1</span>
                                <button class="paginationBtn disabled">Next</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
    <script>
        // Make getSampleProducts available globally for search-page.js
        function getSampleProducts() {
            return [
                {
                    id: 1,
                    name: "Gaming Laptop Pro",
                    description: "High-performance gaming laptop with RTX 4080 and Intel i9 processor",
                    price: "$2,499.99",
                    category: "Laptops",
                    categoryId: 2,
                    image: "https://images.unsplash.com/photo-1603302576837-37561b2e2302?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                    rating: 4.5,
                    reviewCount: 128,
                    stockStatus: 'in_stock'
                },
                {
                    id: 2,
                    name: "Smartphone X",
                    description: "Latest smartphone with advanced camera and 5G connectivity",
                    price: "$999.99",
                    category: "Phones",
                    categoryId: 3,
                    image: "https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                    rating: 4.2,
                    reviewCount: 89,
                    stockStatus: 'in_stock'
                },
                {
                    id: 3,
                    name: "Gaming Console Elite",
                    description: "Next-gen gaming console with 4K gaming and VR support",
                    price: "$499.99",
                    category: "Gaming Devices",
                    categoryId: 1,
                    image: "https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                    rating: 4.8,
                    reviewCount: 256,
                    stockStatus: 'in_stock'
                },
                {
                    id: 4,
                    name: "Smart Watch Pro",
                    description: "Advanced smartwatch with health monitoring and GPS",
                    price: "$349.99",
                    category: "Smartwatches",
                    categoryId: 4,
                    image: "https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                    rating: 4.3,
                    reviewCount: 67,
                    stockStatus: 'in_stock'
                },
                {
                    id: 5,
                    name: "4K Ultra HD TV",
                    description: "65-inch 4K Smart TV with HDR and streaming apps",
                    price: "$899.99",
                    category: "TVs",
                    categoryId: 5,
                    image: "https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                    rating: 4.6,
                    reviewCount: 142,
                    stockStatus: 'in_stock'
                },
                {
                    id: 6,
                    name: "Wireless Headphones",
                    description: "Noise-cancelling wireless headphones with 30hr battery",
                    price: "$299.99",
                    category: "Accessories",
                    categoryId: 6,
                    image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                    rating: 4.4,
                    reviewCount: 203,
                    stockStatus: 'in_stock'
                },
                {
                    id: 7,
                    name: "Mechanical Keyboard",
                    description: "RGB mechanical keyboard with customizable switches",
                    price: "$129.99",
                    category: "Accessories",
                    categoryId: 6,
                    image: "https://images.unsplash.com/photo-1541140532154-b024d705b90a?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                    rating: 4.7,
                    reviewCount: 94,
                    stockStatus: 'in_stock'
                },
                {
                    id: 8,
                    name: "Gaming Mouse",
                    description: "High-precision gaming mouse with programmable buttons",
                    price: "$79.99",
                    category: "Accessories",
                    categoryId: 6,
                    image: "https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80",
                    rating: 4.1,
                    reviewCount: 56,
                    stockStatus: 'in_stock'
                }
            ];
        }
    </script>
    <script src="js/search-page.js"></script>
</body>

</html>