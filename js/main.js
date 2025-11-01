// Main JavaScript file for Tech-Hub website
console.log('Tech-Hub main.js loaded successfully');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded and parsed');
    
    // Initialize all components
    initHotSalesModal();
    initMobileMenu();
    initCategoryTabs();
    initProductGrid();
    initCartCount();
    
    console.log('All components initialized');
});

// Hot Sales Modal functionality - FIXED: Proper event delegation and memory leaks
function initHotSalesModal() {
    const modal = document.getElementById('hotSalesModal');
    const closeBtn = document.querySelector('.closeModal');
    const dots = document.querySelectorAll('.dot');
    
    if (!modal || !closeBtn) {
        console.log('Hot sales modal elements not found');
        return;
    }

    let currentSlide = 0;
    const slides = document.querySelectorAll('.hotSalesSlide');
    let slideInterval;

    console.log('Initializing hot sales modal');

    // Show modal on page load after 2 seconds
    const modalTimer = setTimeout(() => {
        if (modal) {
            modal.style.display = 'block';
            startSlideshow();
            console.log('Hot sales modal displayed');
        }
    }, 2000);

    // Close modal functionality
    const closeModalHandler = () => {
        modal.style.display = 'none';
        stopSlideshow();
        console.log('Hot sales modal closed');
    };

    closeBtn.addEventListener('click', closeModalHandler);

    // Close modal when clicking outside
    const modalClickHandler = (e) => {
        if (e.target === modal) {
            closeModalHandler();
        }
    };
    modal.addEventListener('click', modalClickHandler);

    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            goToSlide(index);
            console.log(`Navigated to slide ${index}`);
        });
    });

    function startSlideshow() {
        if (slides.length > 0) {
            slideInterval = setInterval(() => {
                nextSlide();
            }, 5000);
        }
    }

    function stopSlideshow() {
        if (slideInterval) {
            clearInterval(slideInterval);
        }
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        goToSlide(currentSlide);
    }

    function goToSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        if (slides[index] && dots[index]) {
            slides[index].classList.add('active');
            dots[index].classList.add('active');
            currentSlide = index;
        }
    }

    // Cleanup function
    window.cleanupHotSalesModal = () => {
        clearTimeout(modalTimer);
        stopSlideshow();
        closeBtn.removeEventListener('click', closeModalHandler);
        modal.removeEventListener('click', modalClickHandler);
    };
}

// Mobile Menu functionality - FIXED: Better event handling
function initMobileMenu() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileNav = document.getElementById('mobileNav');

    if (!mobileMenuBtn || !mobileNav) {
        console.log('Mobile menu elements not found');
        return;
    }

    console.log('Initializing mobile menu');

    const toggleMobileMenu = () => {
        mobileNav.classList.toggle('active');
        console.log('Mobile menu toggled:', mobileNav.classList.contains('active'));
    };

    mobileMenuBtn.addEventListener('click', toggleMobileMenu);

    // Close mobile menu when clicking on a link
    const mobileNavLinks = document.querySelectorAll('.mobileNavLink');
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', () => {
            mobileNav.classList.remove('active');
            console.log('Mobile menu closed after link click');
        });
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        if (mobileNav.classList.contains('active') && 
            !mobileNav.contains(e.target) && 
            e.target !== mobileMenuBtn) {
            mobileNav.classList.remove('active');
        }
    });
}

// Category Tabs functionality - FIXED: Proper error handling
function initCategoryTabs() {
    const categoryTabs = document.getElementById('categoryTabs');
    
    if (!categoryTabs) {
        console.log('Category tabs element not found');
        return;
    }

    console.log('Initializing category tabs');

    // Sample categories - in production, this would come from the database
    const categories = [
        { id: 'all', name: 'All Products' },
        { id: 1, name: 'Gaming Devices' },
        { id: 2, name: 'Laptops' },
        { id: 3, name: 'Phones' },
        { id: 4, name: 'Smartwatches' },
        { id: 5, name: 'TVs' },
        { id: 6, name: 'Accessories' }
    ];

    // Render category tabs
    categoryTabs.innerHTML = categories.map(category => `
        <button class="categoryTab ${category.id === 'all' ? 'active' : ''}" 
                data-category-id="${category.id}">
            ${category.name}
        </button>
    `).join('');

    // Add event listeners to category tabs
    const tabs = document.querySelectorAll('.categoryTab');
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            tab.classList.add('active');
            
            const categoryId = tab.getAttribute('data-category-id');
            filterProductsByCategory(categoryId);
            console.log(`Category filter applied: ${categoryId}`);
        });
    });
}

// Product Grid functionality - FIXED: Better loading states
function initProductGrid() {
    console.log('Initializing product grid');
    
    // Load initial products only if on homepage
    if (document.getElementById('productsGrid')) {
        filterProductsByCategory('all');
    }
}

function filterProductsByCategory(categoryId) {
    const productsGrid = document.getElementById('productsGrid');
    const currentCategory = document.getElementById('currentCategory');
    const productsCount = document.getElementById('productsCount');
    const noResults = document.getElementById('noResults');

    if (!productsGrid || !currentCategory || !productsCount || !noResults) {
        console.log('Product grid elements not found');
        return;
    }

    console.log(`Filtering products by category: ${categoryId}`);

    // Show loading state
    productsGrid.innerHTML = '<div class="loadingSpinner"></div>';
    productsGrid.style.display = 'block';
    noResults.style.display = 'none';

    // Simulate API call delay
    setTimeout(() => {
        const sampleProducts = getSampleProducts();
        
        let filteredProducts = sampleProducts;
        if (categoryId !== 'all') {
            filteredProducts = sampleProducts.filter(product => product.categoryId == categoryId);
        }

        // Update UI
        if (filteredProducts.length > 0) {
            renderProducts(filteredProducts);
            productsCount.textContent = `${filteredProducts.length} products found`;
            noResults.style.display = 'none';
            productsGrid.style.display = 'grid';
        } else {
            productsGrid.innerHTML = '';
            productsCount.textContent = '0 products found';
            noResults.style.display = 'block';
            productsGrid.style.display = 'none';
        }

        // Update current category title
        const activeTab = document.querySelector('.categoryTab.active');
        const categoryName = activeTab ? activeTab.textContent : 'All Products';
        currentCategory.textContent = categoryName;

        console.log(`Displayed ${filteredProducts.length} products for category ${categoryId}`);
    }, 500);
}

function renderProducts(products) {
    const productsGrid = document.getElementById('productsGrid');
    
    if (!productsGrid) {
        console.error('Products grid element not found');
        return;
    }
    
    productsGrid.innerHTML = products.map(product => `
        <div class="productCard fadeIn" data-product-id="${product.id}">
            <a href="product.php?id=${product.id}" class="productLink">
                <img src="${product.image}" alt="${product.name}" class="productImage" onerror="this.src='https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'">
            </a>
            <div class="productInfo">
                <div class="productCategory">${product.category}</div>
                <h3 class="productName">
                    <a href="product.php?id=${product.id}">${product.name}</a>
                </h3>
                <p class="productDescription">${product.description}</p>
                <div class="productPrice">${product.price}</div>
                <div class="productRating">
                    <div class="ratingStars">
                        ${getStarRating(product.rating)}
                    </div>
                    <span class="ratingCount">(${product.reviewCount})</span>
                </div>
                <a href="product.php?id=${product.id}" class="addToCartBtn quickViewBtn" data-product-id="${product.id}">
                    <i class="fas fa-eye"></i> View Details
                </a>
            </div>
        </div>
    `).join('');

    // Bind quick view buttons
    bindQuickViewButtons(products);
}

function bindQuickViewButtons(products) {
    const btns = document.querySelectorAll('.quickViewBtn');
    btns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const id = parseInt(btn.getAttribute('data-product-id'));
            const product = products.find(p => p.id === id);
            if (product) {
                openQuickView(product);
            }
        });
    });
}

function openQuickView(product) {
    const modal = document.getElementById('quickViewModal');
    const closeBtn = document.getElementById('quickViewClose');
    if (!modal) return;

    // Populate
    document.getElementById('qvImage').src = product.image;
    document.getElementById('qvCategory').textContent = product.category;
    document.getElementById('qvName').textContent = product.name;
    document.getElementById('qvPrice').textContent = product.price;
    document.getElementById('qvDescription').textContent = product.description;
    document.getElementById('qvReviews').textContent = `(${product.reviewCount})`;
    document.getElementById('qvStars').innerHTML = getStarRating(product.rating);
    document.getElementById('qvQuantity').value = 1;

    // Extra meta
    const sku = `TH-${String(product.id).padStart(4, '0')}`;
    const categoryText = product.category;
    const features = [
        'High-quality materials',
        '1-year manufacturer warranty',
        'Free shipping on orders over $50',
        '30-day return policy'
    ];
    const qvSku = document.getElementById('qvSku');
    const qvCategoryText = document.getElementById('qvCategoryText');
    const qvFeatures = document.getElementById('qvFeatures');
    if (qvSku) qvSku.textContent = sku;
    if (qvCategoryText) qvCategoryText.textContent = categoryText;
    if (qvFeatures) {
        qvFeatures.innerHTML = features.map(f => `<li>${f}</li>`).join('');
    }

    // Full details link
    const fullDetails = document.getElementById('qvFullDetails');
    if (fullDetails) {
        fullDetails.href = `product.php?id=${product.id}`;
    }

    // Quantity controls
    const dec = document.getElementById('qvDec');
    const inc = document.getElementById('qvInc');
    const qty = document.getElementById('qvQuantity');
    if (dec && inc && qty) {
        dec.onclick = () => { const v = Math.max(1, (parseInt(qty.value)||1) - 1); qty.value = v; };
        inc.onclick = () => { const v = Math.min(10, (parseInt(qty.value)||1) + 1); qty.value = v; };
    }

    // Add to cart from modal
    const addBtn = document.getElementById('qvAddToCart');
    if (addBtn) {
        addBtn.onclick = async () => {
            const isLoggedIn = document.querySelector('.userName') !== null;
            const quantity = parseInt(document.getElementById('qvQuantity').value) || 1;
            if (!isLoggedIn) {
                // Fallback to localStorage for guests
                try {
                    let cart = JSON.parse(localStorage.getItem('techHubCart')) || [];
                    const existing = cart.find(item => item.productId === product.id);
                    if (existing) {
                        existing.quantity += quantity;
                    } else {
                        cart.push({ productId: product.id, quantity, addedAt: new Date().toISOString() });
                    }
                    localStorage.setItem('techHubCart', JSON.stringify(cart));
                    updateCartCount(cart.length);
                    showNotification('Product added to cart!', 'success');
                    modal.style.display = 'none';
                } catch (e) {
                    console.error(e);
                    showNotification('Error adding to cart', 'error');
                }
                return;
            }

            // Logged-in: persist to server
            try {
                const form = new FormData();
                form.append('action', 'add');
                form.append('productId', String(product.id));
                form.append('quantity', String(quantity));
                const res = await fetch('api/cart.php', { method: 'POST', body: form });
                let json = { success: false };
                try { json = await res.json(); } catch(_) {}
                if (!res.ok || !json.success) {
                    // If not authorized or server failed, fallback to local storage so UX still works
                    let reason = json.message || `HTTP ${res.status}`;
                    console.warn('Server cart add failed, falling back:', reason);
                    let cart = JSON.parse(localStorage.getItem('techHubCart')) || [];
                    const existing = cart.find(item => item.productId === product.id);
                    if (existing) {
                        existing.quantity += quantity;
                    } else {
                        cart.push({ productId: product.id, quantity, addedAt: new Date().toISOString() });
                    }
                    localStorage.setItem('techHubCart', JSON.stringify(cart));
                    updateCartCount(cart.length);
                } else {
                    // Server succeeded
                    updateCartCountFromServer();
                }
                showNotification('Product added to cart!', 'success');
                modal.style.display = 'none';
            } catch (e) {
                console.error(e);
                // Last-resort fallback
                let cart = JSON.parse(localStorage.getItem('techHubCart')) || [];
                const existing = cart.find(item => item.productId === product.id);
                if (existing) existing.quantity += quantity; else cart.push({ productId: product.id, quantity, addedAt: new Date().toISOString() });
                localStorage.setItem('techHubCart', JSON.stringify(cart));
                updateCartCount(cart.length);
                showNotification('Product added to cart!', 'success');
                modal.style.display = 'none';
            }
        };
    }

    // Open and close logic
    modal.style.display = 'block';
    function close() { modal.style.display = 'none'; }
    if (closeBtn) closeBtn.onclick = close;
    modal.onclick = (e) => { if (e.target === modal) close(); };
}

function getStarRating(rating) {
    if (typeof rating !== 'number' || rating < 0 || rating > 5) {
        rating = 0;
    }
    
    const fullStars = Math.floor(rating);
    const halfStar = rating % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);
    
    let stars = '';
    
    // Full stars
    for (let i = 0; i < fullStars; i++) {
        stars += '<i class="fas fa-star"></i>';
    }
    
    // Half star
    if (halfStar) {
        stars += '<i class="fas fa-star-half-alt"></i>';
    }
    
    // Empty stars
    for (let i = 0; i < emptyStars; i++) {
        stars += '<i class="far fa-star"></i>';
    }
    
    return stars;
}

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
            reviewCount: 128
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
            reviewCount: 89
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
            reviewCount: 256
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
            reviewCount: 67
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
            reviewCount: 142
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
            reviewCount: 203
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
            reviewCount: 94
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
            reviewCount: 56
        }
    ];
}

// Cart functionality
function initCartCount() {
    const isLoggedIn = document.querySelector('.userName') !== null;
    
    // If logged in, fetch from server; else hide cart count or clear localStorage
    if (isLoggedIn) {
        updateCartCountFromServer();
    } else {
        // Not logged in - clear any cached cart count
        const cartCount = document.getElementById('cartCount');
        if (cartCount) {
            cartCount.textContent = '0';
            // Optionally hide the cart icon entirely
            // cartCount.style.display = 'none';
        }
        // Clear localStorage cart on logout (done in header or can be done here)
        // localStorage.removeItem('techHubCart');
    }
}

function updateCartCount(count) {
    const cartCount = document.getElementById('cartCount');
    if (cartCount) {
        cartCount.textContent = count;
        cartCount.classList.add('bounceIn');
        
        // Remove animation class after animation completes
        setTimeout(() => {
            cartCount.classList.remove('bounceIn');
        }, 600);
    }
}

async function updateCartCountFromServer() {
    try {
        const res = await fetch('api/cart.php');
        const json = await res.json();
        if (json.success && Array.isArray(json.items)) {
            updateCartCount(json.items.length);
        }
    } catch (e) {
        console.warn('Falling back to local cart count');
        const cart = JSON.parse(localStorage.getItem('techHubCart')) || [];
        updateCartCount(cart.length);
    }
}

function addToCart(productId) {
    console.log('Attempting to add product to cart:', productId);
    
    // Check if user is logged in
    const isLoggedIn = document.querySelector('.userName') !== null;
    
    if (!isLoggedIn) {
        showLoginPrompt();
        console.log('User not logged in, showing login prompt');
        return;
    }

    try {
        // Get current cart from localStorage
        let cart = JSON.parse(localStorage.getItem('techHubCart')) || [];
        
        // Check if product already in cart
        const existingItem = cart.find(item => item.productId === productId);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({
                productId: productId,
                quantity: 1,
                addedAt: new Date().toISOString()
            });
        }
        
        // Save updated cart
        localStorage.setItem('techHubCart', JSON.stringify(cart));
        
        // Update cart count
        updateCartCount(cart.length);
        
        // Show success message
        showNotification('Product added to cart!', 'success');
        console.log('Product added to cart successfully');
    } catch (error) {
        console.error('Error adding to cart:', error);
        showNotification('Error adding product to cart', 'error');
    }
}

function showLoginPrompt() {
    // Create a simple notification - in production, this would be a proper modal
    showNotification('Please login to add items to cart', 'warning');
    
    // You could redirect to login page or show login modal here
    setTimeout(() => {
        if (confirm('Would you like to login now?')) {
            window.location.href = 'login.php';
        }
    }, 1000);
}

// FIXED: Better notification system with type handling
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()">&times;</button>
    `;
    
    // Add styles if not already added
    if (!document.querySelector('#notificationStyles')) {
        const styles = document.createElement('style');
        styles.id = 'notificationStyles';
        styles.textContent = `
            .notification {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 1.5rem;
                border-radius: var(--border-radius);
                color: white;
                z-index: 3000;
                animation: slideInDown 0.3s ease-out;
                display: flex;
                align-items: center;
                gap: 1rem;
                max-width: 300px;
                box-shadow: var(--shadow-hover);
            }
            .notification.success { background: #28a745; }
            .notification.warning { background: #ffc107; color: #212529; }
            .notification.info { background: #17a2b8; }
            .notification.error { background: #dc3545; }
            .notification button {
                background: none;
                border: none;
                color: inherit;
                font-size: 1.2rem;
                cursor: pointer;
                padding: 0;
                width: 20px;
                height: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        `;
        document.head.appendChild(styles);
    }
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Make functions available globally
window.addToCart = addToCart;
window.showNotification = showNotification;