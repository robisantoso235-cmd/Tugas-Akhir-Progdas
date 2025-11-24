<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS & JS -->
    <link rel="stylesheet" href="./css/style.css?v=2.0">
    <script src="./js/script.js?v=2.0"></script>

    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.7.0/fonts/remixicon.css" rel="stylesheet">

    <title>Katalog Produk</title>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="container">
        <div class="navbar-box">
            <h1>Jastipdies</h1>
            <div class="logo">
                <ul class="menu nav-menu">
                    <li><a href="#">Beranda</a></li>
                    <li><a href="#layanan-section">Layanan</a></li>
                    <li><a href="#produk-section">Produk Kami</a></li>
                    <li><a href="https://www.instagram.com/jastipdies/" target="_blank">Social media</a></li>                  </ul>
                <i class="ri-menu-3-line ri-2x hamburger"></i>
            </div>
        </div>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <div class="container">
        <div class="hero-box">
            <div class="hero-text">
                <h1>Solusi Jasa Titip Terpercaya</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse ipsa voluptate nesciunt dolore expedita.</p>
                <a href="#produk-section" class="btn">Detail Produk Kami</a>
            </div>
            <div class="hero-img">
                <img src="https://jastipdies.web.app/assets/cropped-Cuplikan%20layar%202025-11-17%20200218.png" alt="foto-jastip" />
            </div>
        </div>
    </div>
</div>

<!-- LAYANAN -->
<div class="layanan" id="layanan-section">
    <div class="container">
        <div class="layanan-box">
            <div class="box">
                <i class="ri-shopping-bag-3-line ri-2x"></i>
                <h2>Beragam Produk</h2>
                <p>Kami menyediakan berbagai produk yang terjamin 100% original.</p>
            </div>
            <div class="box">
                <i class="ri-shield-check-line ri-2x"></i>
                <h2>Aman dan Terpercaya</h2>
                <p>Keamanan dan kepercayaan Anda adalah prioritas utama kami dalam setiap transaksi.</p>
            </div>
            <div class="box">
                <i class="ri-price-tag-line ri-2x"></i>
                <h2>Harga Terjangkau</h2>
                <p>Penawaran harga yang adil dan transparan untuk setiap produk.</p>
            </div>
        </div>
    </div>
</div>

<!-- PRODUK -->
<div class="foto" id="produk-section">
    <div class="container">
        <div class="foto_box">
            <h2>Produk Kami</h2>
            
            <!-- Search Box -->
            <div class="search-container">
                <div class="search-box">
                    <i class="ri-search-line"></i>
                    <input type="text" id="search-input" placeholder="Cari produk..." aria-label="Cari produk">
                    <button id="clear-search" class="clear-btn" style="display: none;" aria-label="Hapus pencarian">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            </div>
            
            <!-- Filter Genre -->
            <!-- Replace your current genre-filter div with this one -->
            <div class="genre-filter">
            <button class="filter-btn active" data-genre="all">
                <i class="ri-grid-line"></i>
                <span>Semua</span>
            </button>
            <button class="filter-btn" data-genre="Bags">
                <i class="ri-shopping-bag-line"></i>
                <span>Tas</span>
            </button>
            <button class="filter-btn" data-genre="Wallet">
                <i class="ri-wallet-line"></i>
                <span>Dompet</span>
            </button>
            <button class="filter-btn" data-genre="Shoes">
                <i class="ri-t-shirt-line"></i>
                <span>Sepatu</span>
            </button>
            <button class="filter-btn" data-genre="Accessories">
                <i class="ri-star-line"></i>
                <span>Aksesoris</span>
            </button>
            <button class="filter-btn" data-genre="Clothing">
                <i class="ri-t-shirt-line"></i>
                <span>Pakaian</span>
            </button>
        </div>
            
            <div id="product-container" class="produk-grid">
                <!-- Products will be loaded here by JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Kontak Modal -->
<div id="kontak-modal" class="modal-overlay" style="display: none;">
    <div class="modal-content">
        <span class="close-btn" id="close-modal-btn">&times;</span>
        <h2>Kontak Kami</h2>
        <p>Hubungi kami melalui:</p>
        <div class="kontak-links">
            <a href="https://www.instagram.com/jastipdies/" target="_blank" class="kontak-link instagram">
                <i class="ri-instagram-line"></i>
                <span>Instagram</span>
            </a>
            <a href="https://wa.me/6285767412586" target="_blank" class="kontak-link whatsapp">
                <i class="ri-whatsapp-line"></i>
                <span>WhatsApp</span>
            </a>
        </div>
    </div>
</div>

<!-- Load produk dari database -->
<script src="./js/frontend-produk.js?v=2.1"></script>

</body>
</html>
