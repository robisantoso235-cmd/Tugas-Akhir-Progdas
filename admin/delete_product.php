<?php
require_once 'config.php';
requireLogin();

// Get product ID from URL
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($productId && deleteProduct($productId)) {
    $_SESSION['success'] = 'Produk berhasil dihapus';
} else {
    $_SESSION['error'] = 'Gagal menghapus produk';
}

header('Location: products.php');
exit();
