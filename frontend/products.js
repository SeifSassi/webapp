// Enhanced product data with additional properties
const products = [
    { 
        id: 1,
        name: "Wireless Headphones", 
        price: 199.99,
        oldPrice: 249.99,
        category: "headphones",
        rating: 4,
        reviews: 128,
        badge: "Sale",
        image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e"
    },
    { 
        id: 2,
        name: "Noise Canceling Earphones", 
        price: 149.99,
        category: "earphones",
        rating: 5,
        reviews: 234,
        badge: "Popular",
        image: "https://images.unsplash.com/photo-1583394838336-acd977736f90"
    },
    { 
        id: 3,
        name: "Premium Bluetooth Speaker", 
        price: 299.99,
        oldPrice: 349.99,
        category: "speakers",
        rating: 4,
        reviews: 89,
        badge: "New",
        image: "https://images.unsplash.com/photo-1578319439584-104c94d37305"
    },
    { 
        id: 4,
        name: "Studio Monitor Headphones", 
        price: 399.99,
        category: "headphones",
        rating: 5,
        reviews: 167,
        image: "https://images.unsplash.com/photo-1578319439584-104c94d37305"
    }
];

// Initialize products with enhanced features
function initProducts() {
    const container = document.getElementById('productsContainer');
    const noResults = document.getElementById('noResults');
    
    container.innerHTML = products.map(product => `
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4" 
             data-price="${product.price}" 
             data-name="${product.name.toLowerCase()}"
             data-category="${product.category}"
             data-rating="${product.rating}">
            <div class="product-card">
                ${product.badge ? 
                    `<span class="product-badge">${product.badge}</span>` : ''}
                <img src="${product.image}" class="product-img" alt="${product.name}">
                <button class="quick-view-btn">Quick View</button>
                <div class="product-info">
                    <div class="product-rating">
                        <div class="rating-stars">
                            ${'<i class="fas fa-star"></i>'.repeat(product.rating)}
                        </div>
                        <span class="rating-count">(${product.reviews})</span>
                    </div>
                    <h4 class="product-title">${product.name}</h4>
                    ${product.oldPrice ? `
                        <div class="d-flex align-items-center">
                            <span class="price-comparison">$${product.oldPrice.toFixed(2)}</span>
                            <span class="product-price">$${product.price.toFixed(2)}</span>
                        </div>
                    ` : `<span class="product-price">$${product.price.toFixed(2)}</span>`}
                    <a href="#" class="product-link">View Details <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    `).join('');

    // Initialize quick view buttons
    document.querySelectorAll('.quick-view-btn').forEach(button => {
        button.addEventListener('click', showQuickView);
    });

    noResults.style.display = 'none';
}

// Enhanced search functionality
function handleSearch() {
    const searchTerm = this.value.toLowerCase().trim();
    const productCards = document.querySelectorAll('[data-name]');
    const noResults = document.getElementById('noResults');
    let visibleCount = 0;

    productCards.forEach(card => {
        const searchData = [
            card.dataset.name,
            card.dataset.category,
            card.querySelector('.product-title').textContent.toLowerCase()
        ].join(' ');
        
        const isVisible = searchData.includes(searchTerm);
        card.style.display = isVisible ? 'block' : 'none';
        if(isVisible) visibleCount++;
    });

    noResults.style.display = visibleCount === 0 ? 'block' : 'none';
}

// Enhanced filter functionality
function handleFilter() {
    const container = document.getElementById('productsContainer');
    const cards = Array.from(container.children);
    const filterValue = this.value;

    cards.sort((a, b) => {
        const priceA = parseFloat(a.dataset.price);
        const priceB = parseFloat(b.dataset.price);
        const nameA = a.dataset.name;
        const nameB = b.dataset.name;
        const ratingA = parseInt(a.dataset.rating);
        const ratingB = parseInt(b.dataset.rating);

        switch(filterValue) {
            case 'price-low': return priceA - priceB;
            case 'price-high': return priceB - priceA;
            case 'name': return nameA.localeCompare(nameB);
            case 'rating': return ratingB - ratingA;
            default: return 0;
        }
    });

    container.innerHTML = '';
    cards.forEach(card => container.appendChild(card));
}

// Quick view functionality
function showQuickView(event) {
    event.preventDefault();
    // Add your quick view implementation here
    console.log('Quick view for product:', 
        event.target.closest('.product-card').querySelector('.product-title').textContent);
    // You can implement a modal or expandable view here
}

// Initialize on load
window.onload = () => {
    initProducts();
    
    // Event listeners
    document.getElementById('searchInput').addEventListener('input', handleSearch);
    document.getElementById('filterSelect').addEventListener('change', handleFilter);
    
    // Add loading animation simulation
    const loading = document.createElement('div');
    loading.className = 'loading-spinner';
    document.body.appendChild(loading);
    
    setTimeout(() => {
        loading.style.display = 'none';
    }, 1000);
};

// No results element creation
const noResults = document.createElement('div');
noResults.id = 'noResults';
noResults.className = 'no-results';
noResults.textContent = 'No products found matching your search.';
document.querySelector('.products-grid-section .container').appendChild(noResults);