<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get user ID from request
$userId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Validate user ID
if ($userId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
    exit;
}

try {
    // Get user details
    $stmt = $pdo->prepare("SELECT id, email, role, created_at FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }
    
    echo json_encode(['success' => true, 'user' => $user]);
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Failed to retrieve user']);
}
?> 