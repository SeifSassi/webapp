<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($product) {
            echo json_encode($product);
        } else {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to retrieve product']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Product ID is required']);
}
?> 