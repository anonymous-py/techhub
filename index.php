<?php
session_start();
require_once 'includes/db_connection.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech-Hub - Your Technology Destination</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animations.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <main class="mainContent">
        <!-- Hot Sales Modal -->
        <div id="hotSalesModal" class="modal">
            <div class="modalContent">
                <span class="closeModal">&times;</span>
                <div class="slideshowContainer">
                    <div class="hotSalesSlide active">
                        <img src="https://images.unsplash.com/photo-1607082350899-7e105aa886ae?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Gaming Laptop Sale">
                        <div class="slideText">
                            <h3>Gaming Laptops Up to 30% Off</h3>
                            <p>High-performance laptops for ultimate gaming experience</p>
                        </div>
                    </div>
                    <div class="hotSalesSlide">
                        <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Smartphone Deal">
                        <div class="slideText">
                            <h3>Latest Smartphones</h3>
                            <p>Get the newest models with exclusive discounts</p>
                        </div>
                    </div>
                    <div class="hotSalesSlide">
                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Headphones Sale">
                        <div class="slideText">
                            <h3>Premium Headphones</h3>
                            <p>Immerse yourself in crystal clear audio</p>
                        </div>
                    </div>
                </div>
                <div class="slideshowDots">
                    <span class="dot active" data-slide="0"></span>
                    <span class="dot" data-slide="1"></span>
                    <span class="dot" data-slide="2"></span>
                </div>
            </div>
        </div>

        <!-- Search Section -->
        <section class="searchSection">
            <div class="container">
                <form action="search.php" method="GET" class="searchBox" id="searchForm">
                    <input type="text" id="searchInput" name="q" placeholder="Search for products, categories, brands..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                    <button type="submit" id="searchButton" class="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </section>

        <!-- Category Tabs -->
        <section class="categoriesSection">
            <div class="container">
                <h2 class="sectionTitle">Browse Categories</h2>
                <div class="categoryTabs" id="categoryTabs">
                    <!-- Categories will be loaded dynamically -->
                </div>
            </div>
        </section>

        <!-- Products Grid -->
        <section class="productsSection">
            <div class="container">
                <div class="productsHeader">
                    <h2 class="sectionTitle" id="currentCategory">All Products</h2>
                    <div class="productsCount" id="productsCount"></div>
                </div>
                <div class="productsGrid" id="productsGrid">
                    <!-- Products will be loaded dynamically -->
                </div>
                <div class="noResults" id="noResults" style="display: none;">
                    <i class="fas fa-search fa-3x"></i>
                    <h3>No products found</h3>
                    <p>Try adjusting your search or filter criteria</p>
                </div>
            </div>
        </section>

        <!-- Quick View Modal -->
        <div id="quickViewModal" class="modal" style="display:none;">
            <div class="modalContent largeModal">
                <span class="closeModal" id="quickViewClose">&times;</span>
                <div class="quickViewBody">
                    <div class="quickViewGrid">
                        <div class="quickViewImageWrapper">
                            <img id="qvImage" src="" alt="Product image" class="productImage"
                                 onerror="this.src='https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'">
                        </div>
                        <div class="quickViewInfo">
                            <div class="productCategory" id="qvCategory"></div>
                            <h3 class="productName" id="qvName"></h3>
                            <div class="productRating">
                                <div class="ratingStars" id="qvStars"></div>
                                <span class="ratingCount" id="qvReviews"></span>
                            </div>
                            <div class="productPrice" id="qvPrice"></div>
                            <p class="productDescription" id="qvDescription"></p>

                            <div class="qvMeta">
                                <div class="metaRow">
                                    <span class="metaLabel">SKU:</span>
                                    <span class="metaValue" id="qvSku"></span>
                                </div>
                                <div class="metaRow">
                                    <span class="metaLabel">Category:</span>
                                    <span class="metaValue" id="qvCategoryText"></span>
                                </div>
                            </div>

                            <div class="qvFeatureBlock">
                                <h4>Key Features</h4>
                                <ul class="qvFeatureList" id="qvFeatures">
                                    <!-- Populated by JS -->
                                </ul>
                            </div>

                            <div class="productActions" style="margin-top:1rem;">
                                <div class="quantitySelector" style="margin-bottom:0.75rem;">
                                    <label for="qvQuantity">Quantity:</label>
                                    <div class="quantityControls">
                                        <button type="button" class="quantityBtn" id="qvDec">-</button>
                                        <input type="number" id="qvQuantity" class="quantityInput" value="1" min="1" max="10">
                                        <button type="button" class="quantityBtn" id="qvInc">+</button>
                                    </div>
                                </div>
                                <button class="addToCartBtn" id="qvAddToCart">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                                <a id="qvFullDetails" href="#" class="secondaryButton" style="margin-left:0.5rem;">
                                    More details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js?v=2"></script>
    <script src="js/search.js"></script>
</body>

</html>