<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Log the received data
        error_log("Received product data: " . print_r($_POST, true));
        
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $price = isset($_POST['price']) ? $_POST['price'] : '';
        $image_url = isset($_POST['image_url']) ? $_POST['image_url'] : '';

        // Validate inputs
        if (empty($name) || empty($description) || empty($price) || empty($image_url)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            exit;
        }

        if (!is_numeric($price) || $price < 0) {
            echo json_encode(['success' => false, 'message' => 'Price must be a positive number']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image_url) VALUES (?, ?, ?, ?)");
        $success = $stmt->execute([$name, $description, $price, $image_url]);

        if ($success) {
            $productId = $pdo->lastInsertId();
            echo json_encode(['success' => true, 'message' => 'Product added successfully', 'id' => $productId]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add product']);
        }
    } catch(PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } catch(Exception $e) {
        error_log("General error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'General error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?> 