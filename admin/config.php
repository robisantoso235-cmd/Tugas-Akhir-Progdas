<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'jastipdies';

// Create database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");

// Include Category class
require_once __DIR__ . '/../includes/Category.php';
$category = new Category($conn);

// Fungsi untuk memeriksa login
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirect jika belum login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

// Fungsi untuk login
function login($username, $password) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            return true;
        }
    }
    
    return false;
}

// Fungsi untuk logout
function logout() {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Fungsi untuk mendapatkan semua produk
function getAllProducts() {
    global $conn;
    
    $query = "SELECT p.*, c.name as category_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              ORDER BY p.id DESC";
    $result = $conn->query($query);
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fungsi untuk mendapatkan produk berdasarkan ID
function getProductById($id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

// Fungsi untuk menambahkan produk baru
function addProduct($data) {
    global $conn;
    
    $stmt = $conn->prepare("INSERT INTO products (name, price, description, category_id, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sdsis', 
        $data['name'], 
        $data['price'], 
        $data['description'], 
        $data['category_id'],
        $data['image']
    );
    
    if ($stmt->execute()) {
        return $conn->insert_id;
    }
    
    return false;
}

// Fungsi untuk mengupdate produk
function updateProduct($id, $data) {
    global $conn;
    
    $query = "UPDATE products SET 
              name = ?, 
              price = ?, 
              description = ?, 
              category_id = ?";
    
    $params = [
        $data['name'],
        $data['price'],
        $data['description'],
        $data['category_id']
    ];
    
    $types = 'sdsi';
    
    // Add image to query if present
    if (!empty($data['image'])) {
        $query .= ", image = ?";
        $params[] = $data['image'];
        $types .= 's';
    }
    
    $query .= " WHERE id = ?";
    $params[] = $id;
    $types .= 'i';
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    
    return $stmt->execute();
}

// Fungsi untuk menghapus produk
function deleteProduct($id) {
    global $conn;
    
    // First, get the image path to delete the file
    $product = getProductById($id);
    if ($product && !empty($product['image']) && file_exists('../' . $product['image'])) {
        unlink('../' . $product['image']);
    }
    
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param('i', $id);
    
    return $stmt->execute();
}

// Function to get all categories
function getAllCategories() {
    global $category;
    return $category->getAll();
}

// Function to get category by ID
function getCategory($id) {
    global $category;
    return $category->getById($id);
}