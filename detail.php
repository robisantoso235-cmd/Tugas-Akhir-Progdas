<?php
require_once 'admin/config.php';

// Check if product ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$product_id = (int)$_GET['id'];

// Fetch product details
$query = "SELECT p.*, c.name as category_name 
          FROM products p 
          LEFT JOIN categories c ON p.category_id = c.id 
          WHERE p.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Product not found, redirect to home
    header('Location: index.php');
    exit;
}

$product = $result->fetch_assoc();
$page_title = htmlspecialchars($product['name'] . ' - Jastipdies');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.7.0/fonts/remixicon.css" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="./css/detail.css">
    
</head>
<body>
    <!-- Header -->
    <nav class="navbar">
        <div class="container">
            <a href="index.php" class="back-link" aria-label="Kembali">
                <i class="ri-arrow-left-line"></i>
            </a>
            <a href="index.php" class="logo">Jastipdies</a>
        </div>
    </nav>

    <!-- Product Detail -->
    <main class="detail-container">
        <div class="container">
            <div class="product-detail-container">
                <!-- Image Gallery -->
                <div class="image-gallery">
                    <div class="image-gallery-container">
                        <?php
                        $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/TugasAkhir/';
                        $image_path = 'https://via.placeholder.com/600x600?text=No+Image';

                        if (!empty($product['image'])) {
                            $local_path = $_SERVER['DOCUMENT_ROOT'] . '/TugasAkhir/' . ltrim($product['image'], '/');
                            if (file_exists($local_path)) {
                                $image_path = $base_url . ltrim($product['image'], '/');
                            }
                        }
                        ?>
                        <div class="main-image-container">
                            <img src="<?php echo htmlspecialchars($image_path); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                 class="main-image"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/600x600?text=Gambar+Tidak+Ditemukan'"
                                 loading="lazy">
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="product-info-container">
                    <div class="product-basic-info">
                        <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                        <span class="product-category"><?php echo htmlspecialchars($product['category_name']); ?></span>
                        <div class="product-price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></div>
                    </div>

                    <div class="product-description-section">
                        <h3>Deskripsi Produk</h3>
                        <div class="product-description">
                            <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                        </div>
                    </div>

                    <div class="product-actions">
                        <a href="https://wa.me/6285767412586?text=Saya%20tertarik%20dengan%20produk%20<?php echo urlencode($product['name']); ?>%20-%20Rp%20<?php echo number_format($product['price'], 0, ',', '.'); ?>" 
                           class="whatsapp-btn" 
                           target="_blank">
                            <i class="ri-whatsapp-line"></i> Pesan via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Add any necessary JavaScript here
        document.querySelector('.back-link').addEventListener('click', function(e) {
            e.preventDefault();
            window.history.back();
        });
    </script>
</body>
</html>