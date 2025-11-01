// Search Page JavaScript
console.log('Search page JavaScript loaded');

// Store all products data
let allProducts = [];

document.addEventListener('DOMContentLoaded', function() {
    initSearchPage();
});

function initSearchPage() {
    console.log('Initializing search page functionality');
    
    // Initialize products data from PHP or sample data
    const productsDataScript = document.getElementById('productsData');
    if (productsDataScript && productsDataScript.textContent) {
        try {
            allProducts = JSON.parse(productsDataScript.textContent);
            console.log('Loaded products from PHP data:', allProducts.length);
        } catch (e) {
            console.error('Error parsing products data:', e);
            allProducts = getSampleProducts();
        }
    } else if (typeof getSampleProducts === 'function') {
        allProducts = getSampleProducts();
        console.log('Using sample products data:', allProducts.length);
    } else {
        allProducts = [];
        console.log('No products data available');
    }
    
    initViewToggle();
    initFilters();
    initSorting();
    
    // Apply filters on page load
    applyFiltersOnLoad();
    
    console.log('Search page components initialized');
}

// View Toggle (Grid/List)
function initViewToggle() {
    const viewBtns = document.querySelectorAll('.viewBtn');
    const productsContainer = document.getElementById('productsContainer');
    
    if (!viewBtns.length || !productsContainer) {
        console.log('View toggle elements not found');
        return;
    }
    
    viewBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class from all buttons
            viewBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            btn.classList.add('active');
            
            const viewType = btn.getAttribute('data-view');
            productsContainer.className = 'productsContainer view-' + viewType;
            
            console.log('View changed to:', viewType);
        });
    });
}

// Filters Functionality
function initFilters() {
    const clearFiltersBtn = document.querySelector('.clearFilters');
    const applyFiltersBtn = document.querySelector('.applyFiltersBtn');
    
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', clearAllFilters);
    }
    
    if (applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', applyFilters);
    }
    
    // Auto-apply filters when changed
    const filterInputs = document.querySelectorAll('input[name="category"], input[name="price"], input[name="rating"], input[name="availability"]');
    filterInputs.forEach(input => {
        input.addEventListener('change', () => {
            applyFilters(true);
        });
    });
    
    console.log('Filters initialized');
}

function clearAllFilters() {
    // Reset all filter inputs
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const radios = document.querySelectorAll('input[type="radio"]');
    
    // Reset category checkboxes
    checkboxes.forEach(checkbox => {
        if (checkbox.name === 'category') {
            checkbox.checked = (checkbox.value === 'all');
        } else if (checkbox.name === 'availability') {
            checkbox.checked = (checkbox.value === 'in_stock');
        } else {
            checkbox.checked = false;
        }
    });
    
    // Reset radio buttons
    radios.forEach(radio => {
        if (radio.value === 'all') {
            radio.checked = true;
        }
    });
    
    console.log('All filters cleared');
    applyFilters(true);
    if (typeof window.showNotification === 'function') {
        window.showNotification('Filters cleared', 'info');
    }
}

function applyFiltersOnLoad() {
    // Apply initial filters based on URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const categoryFilter = urlParams.get('category');
    const priceFilter = urlParams.get('price');
    
    // Set filter inputs based on URL parameters
    if (categoryFilter) {
        const categoryInput = document.querySelector(`input[name="category"][value="${categoryFilter}"]`);
        if (categoryInput) {
            document.querySelector('input[name="category"][value="all"]').checked = false;
            categoryInput.checked = true;
        }
    }
    
    if (priceFilter) {
        const priceInput = document.querySelector(`input[name="price"][value="${priceFilter}"]`);
        if (priceInput) {
            document.querySelector('input[name="price"][value="all"]').checked = false;
            priceInput.checked = true;
        }
    }
    
    // Apply filters without showing notification on initial load
    applyFilters(false);
}

function applyFilters(showNotification = true) {
    const selectedCategories = Array.from(document.querySelectorAll('input[name="category"]:checked'))
        .map(cb => cb.value);
    const selectedPrice = document.querySelector('input[name="price"]:checked')?.value;
    const selectedRating = document.querySelector('input[name="rating"]:checked')?.value;
    const selectedAvailability = Array.from(document.querySelectorAll('input[name="availability"]:checked'))
        .map(cb => cb.value);
    
    console.log('Applying filters:', {
        categories: selectedCategories,
        price: selectedPrice,
        rating: selectedRating,
        availability: selectedAvailability
    });
    
    // Filter products
    let filteredProducts = [...allProducts];
    
    // Filter by category
    const categoryFilterActive = selectedCategories.length > 0 && selectedCategories[0] !== 'all' 
        || (selectedCategories.length > 1); // More than just "all" selected
    
    if (categoryFilterActive) {
        // Remove 'all' from the array if present
        const categoriesToFilter = selectedCategories.filter(cat => cat !== 'all');
        if (categoriesToFilter.length > 0) {
            filteredProducts = filteredProducts.filter(product => {
                return categoriesToFilter.includes(product.categoryId.toString());
            });
        }
    }
    
    // Filter by price
    if (selectedPrice && selectedPrice !== 'all') {
        filteredProducts = filteredProducts.filter(product => {
            const price = parseFloat(product.price.replace(/[^0-9.]/g, ''));
            
            if (selectedPrice === '0-100') {
                return price < 100;
            } else if (selectedPrice === '100-500') {
                return price >= 100 && price < 500;
            } else if (selectedPrice === '500-1000') {
                return price >= 500 && price < 1000;
            } else if (selectedPrice === '1000+') {
                return price >= 1000;
            }
            
            return true;
        });
    }
    
    // Filter by rating
    if (selectedRating && selectedRating !== 'all') {
        const minRating = parseFloat(selectedRating);
        filteredProducts = filteredProducts.filter(product => {
            return product.rating >= minRating;
        });
    }
    
    // Filter by availability
    if (selectedAvailability.length > 0 && !selectedAvailability.includes('in_stock')) {
        // Show only out of stock items
        if (selectedAvailability.includes('out_of_stock')) {
            filteredProducts = filteredProducts.filter(product => {
                return product.stockStatus === 'out_of_stock';
            });
        }
    } else if (selectedAvailability.includes('in_stock') && selectedAvailability.includes('out_of_stock')) {
        // Show both - no filter
    } else if (selectedAvailability.includes('in_stock')) {
        // Show only in stock items
        filteredProducts = filteredProducts.filter(product => {
            return product.stockStatus !== 'out_of_stock';
        });
    }
    
    // Apply sorting
    const sortValue = document.getElementById('sortSelect')?.value || 'relevant';
    filteredProducts = sortProducts(filteredProducts, sortValue);
    
    // Display filtered results
    displayFilteredResults(filteredProducts);
    
    // Only show notification if explicitly requested
    if (showNotification && typeof window.showNotification === 'function') {
        window.showNotification(`Showing ${filteredProducts.length} products`, 'success');
    }
}

// Sorting Functionality
function initSorting() {
    const sortSelect = document.getElementById('sortSelect');
    
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const sortValue = this.value;
            console.log('Sorting by:', sortValue);
            applyFilters(true);
            
            if (typeof window.showNotification === 'function') {
                window.showNotification(`Sorted by ${this.options[this.selectedIndex].text}`, 'info');
            }
        });
    }
}

function sortProducts(products, sortBy) {
    const sortedProducts = [...products];
    
    switch(sortBy) {
        case 'price_low':
            sortedProducts.sort((a, b) => {
                const priceA = parseFloat(a.price.replace(/[^0-9.]/g, ''));
                const priceB = parseFloat(b.price.replace(/[^0-9.]/g, ''));
                return priceA - priceB;
            });
            break;
            
        case 'price_high':
            sortedProducts.sort((a, b) => {
                const priceA = parseFloat(a.price.replace(/[^0-9.]/g, ''));
                const priceB = parseFloat(b.price.replace(/[^0-9.]/g, ''));
                return priceB - priceA;
            });
            break;
            
        case 'rating':
            sortedProducts.sort((a, b) => b.rating - a.rating);
            break;
            
        case 'newest':
            // For now, just reverse the array (assuming newer items are added later)
            sortedProducts.reverse();
            break;
            
        case 'relevant':
        default:
            // Keep original order
            break;
    }
    
    return sortedProducts;
}

function displayFilteredResults(products) {
    const productsGrid = document.getElementById('productsGrid');
    const resultsCount = document.getElementById('resultsCount');
    
    if (!productsGrid) {
        console.error('Products grid element not found');
        return;
    }
    
    // Update results count
    if (resultsCount) {
        resultsCount.textContent = `${products.length} products found`;
    }
    
    // Display products
    if (products.length === 0) {
        productsGrid.innerHTML = `
            <div class="noResults">
                <i class="fas fa-search fa-3x"></i>
                <h3>No products found</h3>
                <p>Try adjusting your search or filter criteria</p>
            </div>
        `;
    } else {
        productsGrid.innerHTML = products.map(product => {
            const ratingStars = getStarRatingHTML(product.rating);
            return `
                <div class="productCard fadeIn" data-product-id="${product.id}">
                    <a href="product.php?id=${product.id}" class="productLink">
                        <img src="${product.image}" alt="${product.name}" class="productImage" 
                             onerror="this.src='https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'">
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
                                ${ratingStars}
                            </div>
                            <span class="ratingCount">(${product.reviewCount})</span>
                        </div>
                        <a href="product.php?id=${product.id}" class="addToCartBtn">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            `;
        }).join('');
    }
}

function getStarRatingHTML(rating) {
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

// Make functions available globally
window.clearAllFilters = clearAllFilters;
window.applyFilters = applyFilters;