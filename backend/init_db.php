<?php
require_once 'config.php';

try {
    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('user', 'admin') DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Create products table
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        price DECIMAL(10,2) NOT NULL,
        image_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Insert default admin user
    $adminEmail = 'saif@test.com';
    $adminPassword = password_hash('saif', PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (email, password, role) VALUES (?, ?, 'admin')");
    $stmt->execute([$adminEmail, $adminPassword]);

    echo "Database initialized successfully!";
} catch(PDOException $e) {
    die("Database initialization failed: " . $e->getMessage());
}
?>
