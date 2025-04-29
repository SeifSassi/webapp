<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products);
} catch(PDOException $e) {
    echo json_encode(['error' => 'Failed to fetch products']);
}
?> 