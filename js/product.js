// Product page JavaScript for Tech-Hub
console.log('Tech-Hub product.js loaded successfully');

document.addEventListener('DOMContentLoaded', function() {
    console.log('Product page initialized');
    
    initProductGallery();
    initQuantityControls();
    initProductTabs();
    initReviewSystem();
    initAddToCart();
    initLoginModal();
    
    console.log('All product page components initialized');
});

// Product Image Gallery
function initProductGallery() {
    console.log('Initializing product gallery');
    
    const mainImage = document.getElementById('mainProductImage');
    const thumbnails = document.querySelectorAll('.productThumbnail');
    
    if (!mainImage || thumbnails.length === 0) {
        console.log('Product gallery elements not found');
        return;
    }
    
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            console.log('Thumbnail clicked:', this.dataset.image);
            
            // Remove active class from all thumbnails
            thumbnails.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked thumbnail
            this.classList.add('active');
            
            // Update main image
            const newImageSrc = this.dataset.image;
            mainImage.style.opacity = '0';
            
            setTimeout(() => {
                mainImage.src = newImageSrc;
                mainImage.style.opacity = '1';
            }, 200);
            
            // Add zoom effect
            mainImage.classList.add('zoomIn');
            setTimeout(() => {
                mainImage.classList.remove('zoomIn');
            }, 600);
        });
    });
    
    // Add hover zoom effect to main image
    mainImage.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.05)';
        this.style.transition = 'transform 0.3s ease';
    });
    
    mainImage.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1)';
    });
}

// Quantity Controls
function initQuantityControls() {
    console.log('Initializing quantity controls');
    
    const quantityInput = document.getElementById('productQuantity');
    const decreaseBtn = document.getElementById('decreaseQuantity');
    const increaseBtn = document.getElementById('increaseQuantity');
    
    if (!quantityInput || !decreaseBtn || !increaseBtn) {
        console.log('Quantity control elements not found');
        return;
    }
    
    decreaseBtn.addEventListener('click', function() {
        let currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
            console.log('Quantity decreased to:', quantityInput.value);
        }
    });
    
    increaseBtn.addEventListener('click', function() {
        let currentValue = parseInt(quantityInput.value);
        const maxValue = parseInt(quantityInput.max) || 10;
        
        if (currentValue < maxValue) {
            quantityInput.value = currentValue + 1;
            console.log('Quantity increased to:', quantityInput.value);
        }
    });
    
    // Validate input
    quantityInput.addEventListener('change', function() {
        let value = parseInt(this.value);
        const min = parseInt(this.min) || 1;
        const max = parseInt(this.max) || 10;
        
        if (isNaN(value) || value < min) {
            this.value = min;
        } else if (value > max) {
            this.value = max;
        }
        
        console.log('Quantity validated:', this.value);
    });
}

// Product Tabs
function initProductTabs() {
    console.log('Initializing product tabs');
    
    const tabs = document.querySelectorAll('.productTab');
    const tabPanes = document.querySelectorAll('.tabPane');
    
    if (tabs.length === 0 || tabPanes.length === 0) {
        console.log('Product tab elements not found');
        return;
    }
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            console.log('Tab clicked:', this.dataset.tab);
            
            // Remove active class from all tabs and panes
            tabs.forEach(t => t.classList.remove('active'));
            tabPanes.forEach(p => p.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Show corresponding tab pane
            const tabId = this.dataset.tab;
            const targetPane = document.getElementById(tabId + 'Tab');
            if (targetPane) {
                targetPane.classList.add('active');
                console.log('Showing tab pane:', tabId + 'Tab');
            }
        });
    });
}

// Review System
function initReviewSystem() {
    console.log('Initializing review system');
    
    const writeReviewBtn = document.getElementById('writeReviewBtn');
    const reviewFormContainer = document.getElementById('reviewFormContainer');
    const cancelReviewBtn = document.getElementById('cancelReviewBtn');
    const reviewForm = document.getElementById('reviewForm');
    const ratingStars = document.querySelectorAll('.ratingStar');
    const ratingInput = document.getElementById('reviewRating');
    
    // Write Review Button
    if (writeReviewBtn && reviewFormContainer) {
        writeReviewBtn.addEventListener('click', function() {
            console.log('Write review button clicked');
            reviewFormContainer.style.display = 'block';
            writeReviewBtn.style.display = 'none';
        });
    }
    
    // Cancel Review Button
    if (cancelReviewBtn && reviewFormContainer && writeReviewBtn) {
        cancelReviewBtn.addEventListener('click', function() {
            console.log('Cancel review button clicked');
            reviewFormContainer.style.display = 'none';
            writeReviewBtn.style.display = 'block';
        });
    }
    
    // Star Rating Input
    if (ratingStars.length > 0 && ratingInput) {
        ratingStars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                console.log('Rating selected:', rating);
                
                // Update hidden input
                ratingInput.value = rating;
                
                // Update star display
                ratingStars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.remove('far');
                        s.classList.add('fas');
                    } else {
                        s.classList.remove('fas');
                        s.classList.add('far');
                    }
                });
            });
            
            // Hover effects
            star.addEventListener('mouseenter', function() {
                const rating = parseInt(this.dataset.rating);
                ratingStars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.add('hover');
                    }
                });
            });
            
            star.addEventListener('mouseleave', function() {
                ratingStars.forEach(s => s.classList.remove('hover'));
            });
        });
    }
    
    // Review Form Submission
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Review form submitted');
            
            // In a real application, this would send data to the server
            const formData = new FormData(this);
            const reviewData = {
                rating: formData.get('rating'),
                title: formData.get('title'),
                comment: formData.get('comment')
            };
            
            console.log('Review data:', reviewData);
            
            // Show success message
            showNotification('Thank you for your review!', 'success');
            
            // Reset form and hide
            this.reset();
            if (reviewFormContainer) {
                reviewFormContainer.style.display = 'none';
            }
            if (writeReviewBtn) {
                writeReviewBtn.style.display = 'block';
            }
            
            // Reset stars
            if (ratingStars.length > 0 && ratingInput) {
                ratingInput.value = '5';
                ratingStars.forEach((star, index) => {
                    if (index < 5) {
                        star.classList.remove('far');
                        star.classList.add('fas');
                    } else {
                        star.classList.remove('fas');
                        star.classList.add('far');
                    }
                });
            }
        });
    }
}

// Add to Cart Functionality
function initAddToCart() {
    console.log('Initializing add to cart functionality');
    
    const addToCartBtn = document.getElementById('addToCartBtn');
    
    if (!addToCartBtn) {
        console.log('Add to cart button not found');
        return;
    }
    
    addToCartBtn.addEventListener('click', function() {
        const productId = this.dataset.productId;
        const quantity = parseInt(document.getElementById('productQuantity').value) || 1;
        
        console.log('Add to cart clicked - Product ID:', productId, 'Quantity:', quantity);
        
        // Check if user is logged in (using the same method as main.js)
        const isLoggedIn = document.querySelector('.userName') !== null;
        
        if (!isLoggedIn) {
            console.log('User not logged in, showing login modal');
            showLoginModal();
            return;
        }
        
        // Add to cart
        addToCartWithQuantity(productId, quantity);
    });
}

function addToCartWithQuantity(productId, quantity) {
    console.log('Adding to cart with quantity:', productId, quantity);
    
    try {
        // Get current cart from localStorage
        let cart = JSON.parse(localStorage.getItem('techHubCart')) || [];
        
        // Check if product already in cart
        const existingItemIndex = cart.findIndex(item => item.productId == productId);
        
        if (existingItemIndex !== -1) {
            // Update quantity
            cart[existingItemIndex].quantity += quantity;
            console.log('Updated existing item quantity:', cart[existingItemIndex].quantity);
        } else {
            // Add new item
            cart.push({
                productId: parseInt(productId),
                quantity: quantity,
                addedAt: new Date().toISOString()
            });
            console.log('Added new item to cart');
        }
        
        // Save updated cart
        localStorage.setItem('techHubCart', JSON.stringify(cart));
        
        // Update cart count
        updateCartCount(cart.length);
        
        // Show success message
        showNotification(`Added ${quantity} item(s) to cart!`, 'success');
        
    } catch (error) {
        console.error('Error adding to cart:', error);
        showNotification('Error adding product to cart', 'error');
    }
}

// Login Modal
function initLoginModal() {
    console.log('Initializing login modal');
    
    const loginModal = document.getElementById('loginModal');
    const closeModal = document.querySelector('#loginModal .closeModal');
    const continueShoppingBtn = document.getElementById('continueShoppingBtn');
    
    if (!loginModal) {
        console.log('Login modal not found');
        return;
    }
    
    window.showLoginModal = function() {
        console.log('Showing login modal');
        loginModal.style.display = 'block';
    };
    
    // Close modal events
    if (closeModal) {
        closeModal.addEventListener('click', function() {
            loginModal.style.display = 'none';
        });
    }
    
    if (continueShoppingBtn) {
        continueShoppingBtn.addEventListener('click', function() {
            loginModal.style.display = 'none';
        });
    }
    
    // Close when clicking outside
    loginModal.addEventListener('click', function(e) {
        if (e.target === loginModal) {
            loginModal.style.display = 'none';
        }
    });
}

// Make functions available globally
window.addToCartWithQuantity = addToCartWithQuantity;
window.showLoginModal = showLoginModal;