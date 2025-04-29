<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $image_url = $_POST['image_url'];

        // Validate inputs
        if (empty($name) || empty($description) || empty($price) || empty($image_url)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            exit;
        }

        if (!is_numeric($price) || $price < 0) {
            echo json_encode(['success' => false, 'message' => 'Price must be a positive number']);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, image_url = ? WHERE id = ?");
        $success = $stmt->execute([$name, $description, $price, $image_url, $id]);

        echo json_encode(['success' => $success]);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Failed to update product']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?> 