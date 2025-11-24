# Jastipdies - Admin Panel

## Setup Instructions

1. **Database Setup**
   - Create a new MySQL database named `jastipdies`
   - Import the `database.sql` file to create the necessary tables and default data
   - Default admin credentials:
     - Username: `admin`
     - Password: `admin123`

2. **Configuration**
   - Update the database connection details in `admin/config.php` if needed:
     ```php
     $db_host = 'localhost';
     $db_user = 'your_username';
     $db_pass = 'your_password';
     $db_name = 'jastipdies';
     ```

3. **Directory Permissions**
   - Make sure the `uploads` directory is writable by the web server:
     ```bash
     chmod -R 755 uploads/
     ```

## Admin Panel Features

1. **Product Management**
   - Add new products with images
   - Edit existing products
   - Delete products
   - View all products in a table

2. **Category Management**
   - Add new categories
   - Edit existing categories
   - Delete categories (only if no products are using them)

## Accessing the Admin Panel

1. Go to `http://your-domain.com/TugasAkhir/admin/`
2. Log in with the admin credentials

## File Structure

```
TugasAkhir/
├── admin/
│   ├── add_product.php     # Add new product
│   ├── category_manager.php # Manage categories
│   ├── config.php          # Database and configuration
│   ├── delete_product.php  # Delete product
│   ├── edit_product.php    # Edit product
│   ├── index.php           # Admin dashboard
│   ├── login.php           # Admin login
│   ├── logout.php          # Logout script
│   └── products.php        # List all products
├── assets/                # Frontend assets
├── includes/
│   └── Category.php       # Category class
├── uploads/               # Uploaded product images
├── database.sql           # Database schema
└── index.php              # Frontend
```

## Security Notes

1. Change the default admin password after first login
2. Keep the admin directory secure with proper .htaccess rules in production
3. Regularly backup your database
4. Keep the application updated
