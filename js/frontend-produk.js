// Variabel global
let products = [];
let currentFilter = "all";
let currentSearch = "";
let isLoading = false;

// Fungsi untuk membuat elemen produk
function createProductElement(product) {
    const defaultImage = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'200\'%3E%3Crect fill=\'%23ddd\' width=\'300\' height=\'200\'/%3E%3Ctext fill=\'%23999\' font-family=\'sans-serif\' font-size=\'14\' x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\'%3ENo Image%3C/text%3E%3C/svg%3E';
    
    const productElement = document.createElement('div');
    productElement.className = 'produk-card';
    productElement.dataset.id = product.id;
    productElement.style.cursor = 'pointer';
    
    productElement.innerHTML = `
        <div class="produk-img-container">
            <img src="${product.image || defaultImage}" 
                 alt="${product.name}" 
                 class="produk-img" 
                 loading="lazy"
                 onerror="this.src='${defaultImage}'">
        </div>
        <div class="produk-info">
            <h3 class="produk-judul">${product.name}</h3>
            <p class="produk-harga">Rp ${product.price.toLocaleString('id-ID')}</p>
        </div>
    `;
    
    // Add click event to the entire product card
    productElement.addEventListener('click', (e) => {
        console.log('Product card clicked, navigating to detail page for product ID:', product.id);
        window.location.href = `detail.php?id=${product.id}`;
    });
    
    // Make sure all child elements are clickable
    const childElements = productElement.querySelectorAll('*');
    childElements.forEach(el => {
        el.style.pointerEvents = 'none'; // Allow click to pass through to parent
    });
    
    // Make sure images are still interactive
    const images = productElement.querySelectorAll('img');
    images.forEach(img => {
        img.style.pointerEvents = 'auto';
        img.addEventListener('click', (e) => {
            e.stopPropagation();
            console.log('Image clicked, navigating to detail page for product ID:', product.id);
            window.location.href = `detail.php?id=${product.id}`;
        });
    });
    
    // Prevent price click from triggering the parent click
    const priceElement = productElement.querySelector('.produk-harga');
    if (priceElement) {
        priceElement.style.pointerEvents = 'auto';
        priceElement.addEventListener('click', (e) => {
            e.stopPropagation();
            console.log('Price clicked, preventing navigation');
            // Don't navigate, just stop the event
        });
    }
    
    return productElement;
}

// Fungsi untuk memuat produk dari API
async function fetchProducts() {
    try {
        isLoading = true;
        const apiUrl = `/TugasAkhir/api/get_products.php?category=${currentFilter === 'all' ? 'all' : encodeURIComponent(currentFilter)}&search=${encodeURIComponent(currentSearch)}`;
        const response = await fetch(apiUrl, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data && data.success) {
            products = data.data || [];
            return products;
        } else {
            console.error('Gagal memuat produk:', data?.message || 'Unknown error');
            return [];
        }
    } catch (error) {
        console.error('Error fetching products:', error);
        return [];
    } finally {
        isLoading = false;
    }
}

// Fungsi untuk merender produk ke dalam container
async function renderProducts() {
    const productContainer = document.getElementById("product-container");
    if (!productContainer) return;

    // Tampilkan loading state
    productContainer.innerHTML = '<div class="loading">Memuat produk...</div>';
    
    // Ambil produk dari API
    const filteredProducts = await fetchProducts();
    
    // Kosongkan container
    productContainer.innerHTML = '';

    if (filteredProducts.length === 0) {
        const message = currentSearch 
            ? "Tidak ada produk yang cocok dengan pencarian Anda." 
            : "Tidak ada produk di kategori ini.";
        productContainer.innerHTML = `<p style='text-align: center; padding: 40px; color: #666;'>${message}</p>`;
        return;
    }

    // Kosongkan container
    productContainer.innerHTML = '';

    // Tambahkan setiap produk ke container
    filteredProducts.forEach(product => {
        const productElement = createProductElement(product);
        productElement.addEventListener('click', () => {
            window.location.href = `detail.html?id=${product.id}`;
        });
        productContainer.appendChild(productElement);
    });
}

// Fungsi untuk memuat dan menampilkan produk
function loadAndRenderProducts() {
    const productContainer = document.getElementById("product-container");
    if (!productContainer) return;

    // Tampilkan loading
    productContainer.innerHTML = "<p style='text-align: center; padding: 40px;'>Memuat produk...</p>";

    // Beri sedikit delay untuk simulasi loading
    setTimeout(() => {
        try {
            // Render produk
            renderProducts();
        } catch (error) {
            console.error("Error loading products:", error);
            productContainer.innerHTML = "<p style='text-align: center; padding: 40px; color: #e44;'>Terjadi kesalahan saat memuat produk.</p>";
        }
    }, 500);
}

// Filter button click handler
function setupFilterButtons() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', async () => {
            if (isLoading) return;
            
            // Hapus class active dari semua tombol
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Tambah class active ke tombol yang diklik
            button.classList.add('active');
            
            // Update filter
            currentFilter = button.dataset.genre;
            
            // Render ulang produk
            await loadAndRenderProducts();
        });
    });
}

// Setup search functionality
function setupSearch() {
    const searchInput = document.getElementById('search-input');
    const clearSearchBtn = document.getElementById('clear-search');
    
    if (!searchInput) return;
    
    let searchTimeout;
    
    searchInput.addEventListener('input', (e) => {
        if (isLoading) return;
        
        clearTimeout(searchTimeout);
        
        searchTimeout = setTimeout(async () => {
            currentSearch = e.target.value.toLowerCase().trim();
            
            // Tampilkan tombol clear jika ada input
            if (clearSearchBtn) {
                clearSearchBtn.style.display = currentSearch ? 'flex' : 'none';
            }
            
            // Render ulang produk
            await updateProducts();
        }, 500); // Delay 500ms setelah user berhenti mengetik
    });
    
    // Handle clear search
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', async () => {
            if (isLoading) return;
            
            searchInput.value = '';
            currentSearch = '';
            clearSearchBtn.style.display = 'none';
            
            // Render ulang produk
            await updateProducts();
        });
    }
    
    // Enter key untuk search (optional)
    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            currentSearch = e.target.value.toLowerCase().trim();
            renderProducts();
        }
    });
}

// Load produk saat halaman dimuat
document.addEventListener('DOMContentLoaded', async () => {
    // Inisialisasi filter dan pencarian
    setupFilterButtons();
    setupSearch();
    
    // Load produk pertama kali
    await loadAndRenderProducts();
    
    // Inisialisasi hamburger menu
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });
    }
});
