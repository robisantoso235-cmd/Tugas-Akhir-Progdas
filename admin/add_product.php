<?php
require_once 'config.php';
requireLogin();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = [
            'name' => trim($_POST['name']),
            'price' => (float)$_POST['price'],
            'description' => trim($_POST['description'] ?? ''),
            'category_id' => (int)$_POST['category_id']
        ];
        
        // Validate required fields
        if (empty($data['name']) || $data['price'] <= 0 || empty($data['category_id'])) {
            throw new Exception("Semua field wajib diisi dan harga harus lebih dari 0");
        }
        
        // Handle image upload
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Gambar produk wajib diunggah");
        }
        
        $uploadDir = '../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Validate image
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileMime = finfo_file($fileInfo, $_FILES['image']['tmp_name']);
        
        if (!in_array($fileMime, $allowedTypes)) {
            throw new Exception("Format file tidak didukung. Gunakan format JPG, PNG, atau WebP");
        }
        
        // Generate unique filename
        $fileExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('product_') . '.' . $fileExt;
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $data['image'] = 'uploads/' . $fileName;
            
            // Add product to database
            if (addProduct($data)) {
                $_SESSION['success'] = 'Produk berhasil ditambahkan';
                header('Location: products.php');
                exit();
            } else {
                // If database insert fails, delete the uploaded image
                if (file_exists($targetPath)) {
                    unlink($targetPath);
                }
                throw new Exception("Gagal menambahkan produk. Silakan coba lagi.");
            }
        } else {
            throw new Exception("Gagal mengunggah gambar. Pastikan folder upload memiliki izin yang tepat.");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Get all categories for the dropdown
$categories = getAllCategories();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Jastipdies</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
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
            <h1>Tambah Produk Baru</h1>
        </div>
        
        <div class="card">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Nama Produk</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="price">Harga (Rp)</label>
                    <input type="number" id="price" name="price" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" class="form-control" rows="4"></textarea>
                </div>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="category_id">Kategori</label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="image">Gambar Produk</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Simpan Produk</button>
                    <a href="products.php" class="btn">Batal</a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>