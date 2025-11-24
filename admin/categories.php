<?php
require_once 'config.php';
requireLogin();

$category = new Category($conn);
$error = '';
$success = '';

// Handle category addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    try {
        $name = trim($_POST['name']);
        
        if (empty($name)) {
            throw new Exception("Nama kategori tidak boleh kosong");
        }
        
        if ($category->create(['name' => $name])) {
            $success = 'Kategori berhasil ditambahkan';
        } else {
            throw new Exception("Gagal menambahkan kategori");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Handle category deletion
if (isset($_GET['delete'])) {
    try {
        if ($category->delete($_GET['delete'])) {
            $success = 'Kategori berhasil dihapus';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
    
    // Redirect to remove the delete parameter from URL
    header('Location: categories.php');
    exit();
}

// Get all categories
$categories = $category->getAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori - Jastipdies</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .category-actions {
            display: flex;
            gap: 5px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Admin Panel</h2>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="products.php"><i class="fas fa-box"></i> Produk</a>
            </li>
            <li class="nav-item active">
                <a href="categories.php"><i class="fas fa-tags"></i> Kategori</a>
            </li>
            <li class="nav-item">
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Kelola Kategori</h1>
        </div>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="card">
            <h3>Tambah Kategori Baru</h3>
            <form method="POST" class="form-inline" style="margin-bottom: 20px;">
                <div class="form-group" style="flex: 1; margin-right: 10px;">
                    <input type="text" name="name" class="form-control" placeholder="Nama Kategori" required>
                </div>
                <button type="submit" name="add_category" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </form>
            
            <h3>Daftar Kategori</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Slug</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($categories) > 0): ?>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cat['name']); ?></td>
                                    <td><?php echo htmlspecialchars($cat['slug']); ?></td>
                                    <td><?php echo date('d M Y', strtotime($cat['created_at'])); ?></td>
                                    <td class="category-actions">
                                        <a href="edit_category.php?id=<?php echo $cat['id']; ?>" class="btn btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="categories.php?delete=<?php echo $cat['id']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Yakin ingin menghapus kategori ini? Produk dalam kategori ini tidak akan dihapus, tetapi akan kehilangan kategorinya.')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Belum ada kategori</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.display = 'none';
            });
        }, 5000);
    </script>
</body>
</html>
