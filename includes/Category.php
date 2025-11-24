<?php
class Category {
    private $db;
    private $table = 'categories';

    public function __construct($db) {
        $this->db = $db;
        $this->createTableIfNotExists();
    }

    private function createTableIfNotExists() {
        $query = "CREATE TABLE IF NOT EXISTS {$this->table} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            slug VARCHAR(100) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        $this->db->query($query);
        $this->seedInitialCategories();
    }

    private function seedInitialCategories() {
        $categories = [
            ['name' => 'Tas', 'slug' => 'tas'],
            ['name' => 'Dompet', 'slug' => 'dompet'],
            ['name' => 'Sepatu', 'slug' => 'sepatu'],
            ['name' => 'Aksesoris', 'slug' => 'aksesoris'],
            ['name' => 'Pakaian', 'slug' => 'pakaian']
        ];

        $stmt = $this->db->prepare("INSERT IGNORE INTO {$this->table} (name, slug) VALUES (?, ?)");
        
        foreach ($categories as $category) {
            $stmt->bind_param('ss', $category['name'], $category['slug']);
            $stmt->execute();
        }
    }

    public function getAll() {
        $result = $this->db->query("SELECT * FROM {$this->table} ORDER BY name ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($name) {
        $slug = $this->createSlug($name);
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (name, slug) VALUES (?, ?)");
        $stmt->bind_param('ss', $name, $slug);
        return $stmt->execute();
    }

    public function update($id, $name) {
        $slug = $this->createSlug($name);
        $stmt = $this->db->prepare("UPDATE {$this->table} SET name = ?, slug = ? WHERE id = ?");
        $stmt->bind_param('ssi', $name, $slug, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        // Check if any products are using this category
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM products WHERE category_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if ($result['count'] > 0) {
            throw new Exception("Tidak dapat menghapus kategori karena masih digunakan oleh produk");
        }
        
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    private function createSlug($string) {
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9-]/', '-', $string);
        $string = preg_replace('/-+/', '-', $string);
        return trim($string, '-');
    }
}
?>
