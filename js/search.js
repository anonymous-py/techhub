// Search functionality for Tech-Hub
console.log('Tech-Hub search.js loaded successfully');

document.addEventListener('DOMContentLoaded', function() {
    initSearch();
});

function initSearch() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const searchResults = document.getElementById('searchResults');

    console.log('Initializing search functionality');

    // Search on button click
    searchButton.addEventListener('click', performSearch);

    // Search on Enter key
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    // Real-time search with debounce
    let searchTimeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (searchInput.value.length >= 2) {
                performSearch();
            } else {
                hideSearchResults();
            }
        }, 300);
    });

    // Hide search results when clicking outside
    document.addEventListener('click', (e) => {
        if (!searchResults.contains(e.target) && e.target !== searchInput && e.target !== searchButton) {
            hideSearchResults();
        }
    });
}

function performSearch() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const query = searchInput.value.trim();

    console.log('Performing search for:', query);

    if (query.length < 2) {
        hideSearchResults();
        return;
    }

    // Show loading state
    searchResults.innerHTML = '<div class="loadingSpinner" style="margin: 1rem auto;"></div>';
    searchResults.style.display = 'block';

    // Simulate API call
    setTimeout(() => {
        const results = searchProducts(query);
        displaySearchResults(results, query);
    }, 500);
}

function searchProducts(query) {
    const sampleProducts = getSampleProducts();
    const searchTerm = query.toLowerCase();

    return sampleProducts.filter(product => {
        const searchableText = `
            ${product.name} 
            ${product.description} 
            ${product.category}
        `.toLowerCase();

        return searchableText.includes(searchTerm);
    });
}

function displaySearchResults(results, query) {
    const searchResults = document.getElementById('searchResults');
    
    if (results.length === 0) {
        searchResults.innerHTML = `
            <div class="noSearchResults">
                <i class="fas fa-search fa-2x"></i>
                <h4>No results found for "${query}"</h4>
                <p>Try different keywords or browse categories</p>
            </div>
        `;
    } else {
        searchResults.innerHTML = `
            <div class="searchResultsHeader">
                <h4>${results.length} results for "${query}"</h4>
            </div>
            <div class="searchResultsList">
                ${results.map(product => `
                    <div class="searchResultItem" onclick="redirectToProduct(${product.id})">
                        <img src="${product.image}" alt="${product.name}" class="searchResultImage">
                        <div class="searchResultInfo">
                            <h5 class="searchResultName">${product.name}</h5>
                            <p class="searchResultCategory">${product.category}</p>
                            <div class="searchResultPrice">${product.price}</div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    // Add styles for search results if not already added
    if (!document.querySelector('#searchResultsStyles')) {
        const styles = document.createElement('style');
        styles.id = 'searchResultsStyles';
        styles.textContent = `
            .searchResultsHeader {
                padding: 1rem;
                border-bottom: 1px solid var(--light-gray);
                background: var(--off-white);
            }
            .searchResultsHeader h4 {
                margin: 0;
                color: var(--dark-blue);
            }
            .searchResultsList {
                max-height: 300px;
                overflow-y: auto;
            }
            .searchResultItem {
                display: flex;
                align-items: center;
                padding: 1rem;
                border-bottom: 1px solid var(--light-gray);
                cursor: pointer;
                transition: var(--transition);
            }
            .searchResultItem:hover {
                background: var(--light-gray);
            }
            .searchResultImage {
                width: 50px;
                height: 50px;
                object-fit: cover;
                border-radius: var(--border-radius);
                margin-right: 1rem;
            }
            .searchResultInfo {
                flex: 1;
            }
            .searchResultName {
                margin: 0 0 0.25rem 0;
                color: var(--dark-blue);
                font-weight: 600;
            }
            .searchResultCategory {
                margin: 0 0 0.25rem 0;
                color: var(--gold);
                font-size: 0.8rem;
                font-weight: 600;
                text-transform: uppercase;
            }
            .searchResultPrice {
                color: var(--dark-blue);
                font-weight: 700;
            }
            .noSearchResults {
                text-align: center;
                padding: 2rem;
                color: var(--charcoal);
            }
            .noSearchResults i {
                color: var(--light-gray);
                margin-bottom: 1rem;
            }
            .noSearchResults h4 {
                margin-bottom: 0.5rem;
            }
        `;
        document.head.appendChild(styles);
    }
}

function hideSearchResults() {
    const searchResults = document.getElementById('searchResults');
    searchResults.style.display = 'none';
}

function redirectToProduct(productId) {
    console.log('Redirecting to product:', productId);
    // In production, this would redirect to product detail page
    // window.location.href = `product.php?id=${productId}`;
    
    // For now, show a notification
    showNotification('Redirecting to product details...', 'info');
    hideSearchResults();
}

// Make functions available globally
window.performSearch = performSearch;
window.hideSearchResults = hideSearchResults;
window.redirectToProduct = redirectToProduct;