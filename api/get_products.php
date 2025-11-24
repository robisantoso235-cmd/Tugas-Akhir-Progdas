<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../admin/config.php';

// Ambil parameter filter dari query string
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';

try {
    // Query dasar untuk mengambil produk
    $query = "SELECT p.*, c.name as category_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE 1=1";
    
    $params = [];
    $types = '';
    
    // Filter berdasarkan kategori jika dipilih
    if ($category !== 'all') {
        $query .= " AND c.name = ?";
        $params[] = $category;
        $types .= 's';
    }
    
    // Filter berdasarkan pencarian
    if (!empty($search)) {
        $searchTerm = "%$search%";
        $query .= " AND (LOWER(p.name) LIKE ? OR LOWER(p.description) LIKE ? OR LOWER(c.name) LIKE ?)";
        $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
        $types .= 'sss';
    }
    
    $query .= " ORDER BY p.id DESC";
    
    $stmt = $conn->prepare($query);
    
    // Bind parameter jika ada
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $products = $result->fetch_all(MYSQLI_ASSOC);
    
    // Format data untuk frontend
    $formattedProducts = array_map(function($product) {
        return [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => (float)$product['price'],
            'description' => $product['description'],
            'genre' => $product['category_name'],
            'image' => !empty($product['image']) ? $product['image'] : 'https://via.placeholder.com/300x200?text=No+Image'
        ];
    }, $products);
    
    echo json_encode([
        'success' => true,
        'data' => $formattedProducts
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan saat mengambil data produk',
        'error' => $e->getMessage()
    ]);
}

$conn->close();
