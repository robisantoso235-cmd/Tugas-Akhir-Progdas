<?php
require_once 'config.php';
requireLogin();

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        try {
            switch ($_POST['action']) {
                case 'add':
                    if (empty(trim($_POST['name']))) {
                        throw new Exception("Nama kategori tidak boleh kosong");
                    }
                    
                    $name = trim($_POST['name']);
                    
                    // Check if category already exists
                    $stmt = $conn->prepare("SELECT id FROM categories WHERE name = ?");
                    $stmt->bind_param('s', $name);
                    $stmt->execute();
                    
                    if ($stmt->get_result()->num_rows > 0) {
                        throw new Exception("Kategori sudah ada");
                    }
                    
                    // Add new category
                    $category = new Category($conn);
                    if ($category->create($name)) {
                        $success = "Kategori berhasil ditambahkan";
                    } else {
                        throw new Exception("Gagal menambahkan kategori");
                    }
                    break;
                    
                case 'edit':
                    if (empty($_POST['id']) || empty(trim($_POST['name']))) {
                        throw new Exception("Data tidak valid");
                    }
                    
                    $id = (int)$_POST['id'];
                    $name = trim($_POST['name']);
                    
                    // Check if category exists and get current name
                    $stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
                    $stmt->bind_param('i', $id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows === 0) {
                        throw new Exception("Kategori tidak ditemukan");
                    }
                    
                    $current = $result->fetch_assoc();
                    
                    // Only update if name has changed
                    if ($current['name'] !== $name) {
                        // Check if new name already exists
                        $stmt = $conn->prepare("SELECT id FROM categories WHERE name = ? AND id != ?");
                        $stmt->bind_param('si', $name, $id);
                        $stmt->execute();
                        
                        if ($stmt->get_result()->num_rows > 0) {
                            throw new Exception("Nama kategori sudah digunakan");
                        }
                        
                        // Update category
                        $category = new Category($conn);
                        if (!$category->update($id, $name)) {
                            throw new Exception("Gagal memperbarui kategori");
                        }
                    }
                    
                    $success = "Kategori berhasil diperbarui";
                    break;
                    
                case 'delete':
                    if (empty($_POST['id'])) {
                        throw new Exception("ID kategori tidak valid");
                    }
                    
                    $id = (int)$_POST['id'];
                    $category = new Category($conn);
                    
                    if ($category->delete($id)) {
                        $success = "Kategori berhasil dihapus";
                    } else {
                        throw new Exception("Gagal menghapus kategori");
                    }
                    break;
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Get all categories
$category = new Category($conn);
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
        .category-form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .category-list {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .category-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
        }
        .category-item:last-child {
            border-bottom: none;
        }
        .category-actions {
            display: flex;
            gap: 10px;
        }
        .btn-edit, .btn-delete {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-edit {
            background-color: #4CAF50;
            color: white;
        }
        .btn-delete {
            background-color: #f44336;
            color: white;
        }
        .form-inline {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .form-inline input[type="text"] {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-inline button {
            padding: 8px 16px;
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
                <a href="index.php" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="products.php" class="nav-link">
                    <i class="fas fa-box"></i> Produk
                </a>
            </li>
            <li class="nav-item active">
                <i class="fas fa-tags"></i> Kategori
            </li>
            <li class="nav-item">
                <a href="logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Kelola Kategori Produk</h1>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <div class="card">
            <h2>Tambah Kategori Baru</h2>
            <form method="POST" class="form-inline">
                <input type="hidden" name="action" value="add">
                <input type="text" name="name" placeholder="Nama Kategori" required>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </form>
        </div>
        
        <div class="card">
            <h2>Daftar Kategori</h2>
            <?php if (count($categories) > 0): ?>
                <div class="category-list">
                    <?php foreach ($categories as $cat): ?>
                        <div class="category-item">
                            <span class="category-name"><?php echo htmlspecialchars($cat['name']); ?></span>
                            <div class="category-actions">
                                <button type="button" class="btn-edit" onclick="editCategory(<?php echo $cat['id']; ?>, '<?php echo addslashes($cat['name']); ?>')">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Produk dalam kategori ini TIDAK akan dihapus, tapi akan kehilangan kategorinya.');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                                    <button type="submit" class="btn-delete">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Belum ada kategori. Silakan tambahkan kategori baru.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Edit Category Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Kategori</h2>
            <form id="editForm" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="editCategoryId">
                <div class="form-group">
                    <label for="editCategoryName">Nama Kategori</label>
                    <input type="text" id="editCategoryName" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>
    
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            position: relative;
        }
        
        .close {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
    
    <script>
        // Get the modal
        const modal = document.getElementById('editModal');
        
        // Function to open the modal
        function editCategory(id, name) {
            document.getElementById('editCategoryId').value = id;
            document.getElementById('editCategoryName').value = name;
            modal.style.display = 'block';
        }
        
        // Function to close the modal
        function closeModal() {
            modal.style.display = 'none';
        }
        
        // Close the modal when clicking the X
        document.querySelector('.close').addEventListener('click', closeModal);
        
        // Close the modal when clicking outside of it
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });
        
        // Auto-hide success/error messages after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.display = 'none';
            });
        }, 5000);
    </script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>
