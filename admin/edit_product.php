<?php
require_once 'config.php';
requireLogin();

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$id = $_GET['id'];
$product = getProductById($id);

if (!$product) {
    header('Location: products.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'description' => $_POST['description'] ?? '',
        'genre' => $_POST['genre']
    ];
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $data['image'] = 'uploads/' . $fileName;
        }
    } else {
        $data['image'] = $product['image'];
    }
    
    updateProduct($id, $data);
    header('Location: products.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Jastipdies</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Admin Panel</h2>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="products.php" style="color: inherit; text-decoration: none;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Edit Produk</h1>
        </div>
        
        <div class="card">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Nama Produk</label>
                    <input type="text" id="name" name="name" class="form-control" 
                           value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="price">Harga (Rp)</label>
                    <input type="number" id="price" name="price" class="form-control" 
                           value="<?php echo htmlspecialchars($product['price']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" class="form-control" 
                              rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="genre">Kategori</label>
                    <select id="genre" name="genre" class="form-control" required>
                        <option value="Bags" <?php echo $product['genre'] === 'Bags' ? 'selected' : ''; ?>>Tas</option>
                        <option value="Wallet" <?php echo $product['genre'] === 'Wallet' ? 'selected' : ''; ?>>Dompet</option>
                        <option value="Shoes" <?php echo $product['genre'] === 'Shoes' ? 'selected' : ''; ?>>Sepatu</option>
                        <option value="Accessories" <?php echo $product['genre'] === 'Accessories' ? 'selected' : ''; ?>>Aksesoris</option>
                        <option value="Clothing" <?php echo $product['genre'] === 'Clothing' ? 'selected' : ''; ?>>Pakaian</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="image">Gambar Produk</label>
                    <?php if ($product['image']): ?>
                        <div style="margin-bottom: 1rem;">
                            <img src="../<?php echo htmlspecialchars($product['image']); ?>" 
                                 alt="Gambar saat ini" 
                                 style="max-width: 200px; display: block; margin-bottom: 0.5rem;">
                            <small>Biarkan kosong jika tidak ingin mengubah gambar</small>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="products.php" class="btn">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>