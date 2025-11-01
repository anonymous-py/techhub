<?php
session_start();
require_once 'includes/db_connection.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Get product ID from URL
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get product details
$products = getSampleProducts();
$product = null;

foreach ($products as $p) {
    if ($p['id'] == $productId) {
        $product = $p;
        break;
    }
}

// If product not found, redirect to homepage
if (!$product) {
    header("Location: index.php");
    exit();
}

// Add to recently viewed
if (!isset($_SESSION['recently_viewed'])) {
    $_SESSION['recently_viewed'] = [];
}

// Add current product to recently viewed (if not already there)
if (!in_array($productId, $_SESSION['recently_viewed'])) {
    array_unshift($_SESSION['recently_viewed'], $productId);
    // Keep only last 5 products
    $_SESSION['recently_viewed'] = array_slice($_SESSION['recently_viewed'], 0, 5);
}

// Get related products (same category)
$relatedProducts = array_filter($products, function ($p) use ($product) {
    return $p['categoryId'] == $product['categoryId'] && $p['id'] != $product['id'];
});
$relatedProducts = array_slice($relatedProducts, 0, 4);

// Check if user is logged in
$auth = new Auth();
$isLoggedIn = $auth->isLoggedIn();
$currentUser = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Tech-Hub</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animations.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <main class="mainContent">
        <div class="container">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="index.php" class="breadcrumbLink">Home</a>
                <i class="fas fa-chevron-right breadcrumbSeparator"></i>
                <a href="index.php?category=<?php echo $product['categoryId']; ?>" class="breadcrumbLink">
                    <?php echo htmlspecialchars($product['category']); ?>
                </a>
                <i class="fas fa-chevron-right breadcrumbSeparator"></i>
                <span class="breadcrumbCurrent"><?php echo htmlspecialchars($product['name']); ?></span>
            </nav>

            <!-- Product Details -->
            <div class="productDetailSection">
                <div class="productDetailGrid">
                    <!-- Product Images -->
                    <div class="productImageSection">
                        <div class="productMainImage">
                            <img src="<?php echo htmlspecialchars($product['image']); ?>"
                                alt="<?php echo htmlspecialchars($product['name']); ?>"
                                id="mainProductImage"
                                onerror="this.src='https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'">
                        </div>
                        <div class="productThumbnails">
                            <!-- Sample thumbnails - in real app these would be different images -->
                            <div class="productThumbnail active" data-image="<?php echo htmlspecialchars($product['image']); ?>">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Thumbnail 1">
                            </div>
                            <div class="productThumbnail" data-image="https://images.unsplash.com/photo-1541140532154-b024d705b90a?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80">
                                <img src="https://images.unsplash.com/photo-1541140532154-b024d705b90a?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Thumbnail 2">
                            </div>
                            <div class="productThumbnail" data-image="https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80">
                                <img src="https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80" alt="Thumbnail 3">
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="productInfoSection">
                        <div class="productHeader">
                            <h1 class="productTitle"><?php echo htmlspecialchars($product['name']); ?></h1>
                            <div class="productRatingOverview">
                                <div class="ratingStars">
                                    <?php echo getStarRating($product['rating']); ?>
                                </div>
                                <span class="ratingText"><?php echo number_format($product['rating'], 1); ?> (<?php echo $product['reviewCount']; ?> reviews)</span>
                            </div>
                        </div>

                        <div class="productPriceSection">
                            <span class="productPrice"><?php echo htmlspecialchars($product['price']); ?></span>
                            <span class="productStock inStock">In Stock</span>
                        </div>

                        <div class="productDescription">
                            <p><?php echo htmlspecialchars($product['description']); ?></p>
                        </div>

                        <div class="productFeatures">
                            <h3>Key Features</h3>
                            <ul class="featuresList">
                                <li><i class="fas fa-check featureIcon"></i> High-quality materials</li>
                                <li><i class="fas fa-check featureIcon"></i> 1-year manufacturer warranty</li>
                                <li><i class="fas fa-check featureIcon"></i> Free shipping on orders over $50</li>
                                <li><i class="fas fa-check featureIcon"></i> 30-day return policy</li>
                            </ul>
                        </div>

                        <div class="productActions">
                            <div class="quantitySelector">
                                <label for="productQuantity">Quantity:</label>
                                <div class="quantityControls">
                                    <button type="button" class="quantityBtn" id="decreaseQuantity">-</button>
                                    <input type="number" id="productQuantity" class="quantityInput" value="1" min="1" max="10">
                                    <button type="button" class="quantityBtn" id="increaseQuantity">+</button>
                                </div>
                            </div>

                            <button class="addToCartBtn primaryButton largeButton" id="addToCartBtn" data-product-id="<?php echo $product['id']; ?>">
                                <i class="fas fa-shopping-cart"></i>
                                Add to Cart
                            </button>

                            <button class="wishlistBtn secondaryButton">
                                <i class="far fa-heart"></i>
                                Add to Wishlist
                            </button>
                        </div>

                        <div class="productMeta">
                            <div class="metaItem">
                                <span class="metaLabel">Category:</span>
                                <span class="metaValue"><?php echo htmlspecialchars($product['category']); ?></span>
                            </div>
                            <div class="metaItem">
                                <span class="metaLabel">SKU:</span>
                                <span class="metaValue">TH-<?php echo str_pad($product['id'], 4, '0', STR_PAD_LEFT); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Tabs -->
            <div class="productTabsSection">
                <div class="productTabs">
                    <button class="productTab active" data-tab="description">Description</button>
                    <button class="productTab" data-tab="specifications">Specifications</button>
                    <button class="productTab" data-tab="reviews">Reviews (<?php echo $product['reviewCount']; ?>)</button>
                </div>

                <div class="productTabContent">
                    <!-- Description Tab -->
                    <div class="tabPane active" id="descriptionTab">
                        <h3>Product Description</h3>
                        <p><?php echo htmlspecialchars($product['description']); ?></p>
                        <p>This premium product from Tech-Hub offers exceptional quality and performance. Designed with the latest technology and built to last, it's the perfect addition to your tech collection.</p>

                        <div class="featureGrid">
                            <div class="featureItem">
                                <i class="fas fa-shield-alt featureGridIcon"></i>
                                <h4>Premium Quality</h4>
                                <p>Built with high-grade materials for durability and longevity.</p>
                            </div>
                            <div class="featureItem">
                                <i class="fas fa-bolt featureGridIcon"></i>
                                <h4>High Performance</h4>
                                <p>Optimized for speed and efficiency in all conditions.</p>
                            </div>
                            <div class="featureItem">
                                <i class="fas fa-headset featureGridIcon"></i>
                                <h4>24/7 Support</h4>
                                <p>Round-the-clock customer support for all your needs.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Specifications Tab -->
                    <div class="tabPane" id="specificationsTab">
                        <h3>Technical Specifications</h3>
                        <div class="specsTable">
                            <div class="specRow">
                                <span class="specLabel">Model</span>
                                <span class="specValue"><?php echo htmlspecialchars($product['name']); ?> Pro</span>
                            </div>
                            <div class="specRow">
                                <span class="specLabel">Dimensions</span>
                                <span class="specValue">15.6" x 10.2" x 0.8"</span>
                            </div>
                            <div class="specRow">
                                <span class="specLabel">Weight</span>
                                <span class="specValue">2.5 lbs</span>
                            </div>
                            <div class="specRow">
                                <span class="specLabel">Color</span>
                                <span class="specValue">Space Gray</span>
                            </div>
                            <div class="specRow">
                                <span class="specLabel">Warranty</span>
                                <span class="specValue">1 Year Limited</span>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Tab -->
                    <div class="tabPane" id="reviewsTab">
                        <div class="reviewsHeader">
                            <div class="reviewsSummary">
                                <div class="averageRating">
                                    <span class="averageScore"><?php echo number_format($product['rating'], 1); ?></span>
                                    <div class="ratingStars">
                                        <?php echo getStarRating($product['rating']); ?>
                                    </div>
                                    <span class="totalReviews"><?php echo $product['reviewCount']; ?> reviews</span>
                                </div>
                            </div>

                            <?php if ($isLoggedIn): ?>
                                <button class="primaryButton" id="writeReviewBtn">
                                    <i class="fas fa-edit"></i>
                                    Write a Review
                                </button>
                            <?php endif; ?>
                        </div>

                        <!-- Review Form (for logged-in users) -->
                        <?php if ($isLoggedIn): ?>
                            <div class="reviewFormContainer" id="reviewFormContainer" style="display: none;">
                                <h4>Write Your Review</h4>
                                <form id="reviewForm" class="reviewForm">
                                    <div class="formGroup">
                                        <label class="formLabel">Your Rating</label>
                                        <div class="ratingInput">
                                            <div class="starRatingInput">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="far fa-star ratingStar" data-rating="<?php echo $i; ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                            <input type="hidden" id="reviewRating" name="rating" value="5">
                                        </div>
                                    </div>

                                    <div class="formGroup">
                                        <label for="reviewTitle" class="formLabel">Review Title</label>
                                        <input type="text" id="reviewTitle" name="title" class="formInput" placeholder="Summarize your experience" required>
                                    </div>

                                    <div class="formGroup">
                                        <label for="reviewComment" class="formLabel">Your Review</label>
                                        <textarea id="reviewComment" name="comment" class="formTextarea" placeholder="Share your thoughts about this product..." rows="5" required></textarea>
                                    </div>

                                    <div class="formActions">
                                        <button type="submit" class="primaryButton">Submit Review</button>
                                        <button type="button" class="secondaryButton" id="cancelReviewBtn">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="loginPrompt">
                                <p>Please <a href="login.php?redirect=product.php?id=<?php echo $productId; ?>">login</a> to write a review.</p>
                            </div>
                        <?php endif; ?>

                        <!-- Sample Reviews -->
                        <div class="reviewsList">
                            <div class="reviewItem">
                                <div class="reviewHeader">
                                    <div class="reviewerInfo">
                                        <span class="reviewerName">John D.</span>
                                        <div class="reviewRating">
                                            <?php echo getStarRating(5); ?>
                                        </div>
                                    </div>
                                    <span class="reviewDate">2 days ago</span>
                                </div>
                                <h5 class="reviewTitle">Excellent product!</h5>
                                <p class="reviewContent">This product exceeded my expectations. The quality is outstanding and it works perfectly for my needs.</p>
                            </div>

                            <div class="reviewItem">
                                <div class="reviewHeader">
                                    <div class="reviewerInfo">
                                        <span class="reviewerName">Sarah M.</span>
                                        <div class="reviewRating">
                                            <?php echo getStarRating(4); ?>
                                        </div>
                                    </div>
                                    <span class="reviewDate">1 week ago</span>
                                </div>
                                <h5 class="reviewTitle">Great value for money</h5>
                                <p class="reviewContent">Very happy with this purchase. It does everything I need and the price was reasonable.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <?php if (!empty($relatedProducts)): ?>
                <section class="relatedProductsSection">
                    <div class="sectionHeader">
                        <h2 class="sectionTitle">Related Products</h2>
                        <a href="index.php?category=<?php echo $product['categoryId']; ?>" class="viewAllLink">View All</a>
                    </div>
                    <div class="productsGrid compactGrid">
                        <?php foreach ($relatedProducts as $relatedProduct): ?>
                            <div class="productCard fadeIn">
                                <a href="product.php?id=<?php echo $relatedProduct['id']; ?>" class="productLink">
                                    <img src="<?php echo htmlspecialchars($relatedProduct['image']); ?>"
                                        alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>"
                                        class="productImage"
                                        onerror="this.src='https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'">
                                </a>
                                <div class="productInfo">
                                    <div class="productCategory"><?php echo htmlspecialchars($relatedProduct['category']); ?></div>
                                    <h3 class="productName">
                                        <a href="product.php?id=<?php echo $relatedProduct['id']; ?>">
                                            <?php echo htmlspecialchars($relatedProduct['name']); ?>
                                        </a>
                                    </h3>
                                    <div class="productPrice"><?php echo htmlspecialchars($relatedProduct['price']); ?></div>
                                    <div class="productRating">
                                        <div class="ratingStars">
                                            <?php echo getStarRating($relatedProduct['rating']); ?>
                                        </div>
                                        <span class="ratingCount">(<?php echo $relatedProduct['reviewCount']; ?>)</span>
                                    </div>
                                    <button class="addToCartBtn" onclick="addToCart(<?php echo $relatedProduct['id']; ?>)">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </main>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modalContent mediumModal">
            <span class="closeModal">&times;</span>
            <div class="modalHeader">
                <h3>Login Required</h3>
                <p>Please login to add items to your cart</p>
            </div>
            <div class="modalBody">
                <p>You need to be logged in to add products to your shopping cart.</p>
                <div class="modalActions">
                    <a href="login.php?redirect=product.php?id=<?php echo $productId; ?>" class="primaryButton">Login</a>
                    <a href="signup.php?redirect=product.php?id=<?php echo $productId; ?>" class="secondaryButton">Sign Up</a>
                    <button class="secondaryButton" id="continueShoppingBtn">Continue Shopping</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
    <script src="js/product.js"></script>
</body>

</html>