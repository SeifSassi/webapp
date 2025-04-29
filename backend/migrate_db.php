<?php
// SQLite connection
$sqlite = new SQLite3('beats.db');

// MySQL connection
require_once 'config.php';

try {
    // Start transaction
    $pdo->beginTransaction();

    // Migrate users table
    $sqliteUsers = $sqlite->query("SELECT * FROM users");
    while ($user = $sqliteUsers->fetchArray(SQLITE3_ASSOC)) {
        $stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, 'user')");
        $stmt->execute([$user['email'], $user['password']]);
    }

    // Migrate products table if it exists in SQLite
    $sqliteProducts = $sqlite->query("SELECT * FROM products");
    if ($sqliteProducts) {
        while ($product = $sqliteProducts->fetchArray(SQLITE3_ASSOC)) {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image_url) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $product['name'],
                $product['description'],
                $product['price'],
                $product['image_url']
            ]);
        }
    }

    // Commit transaction
    $pdo->commit();
    echo "Migration completed successfully!";

} catch (Exception $e) {
    // Rollback transaction on error
    $pdo->rollBack();
    echo "Migration failed: " . $e->getMessage();
}

// Close SQLite connection
$sqlite->close();
?> 