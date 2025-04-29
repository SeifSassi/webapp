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

try {
    // Get all users without password
    $stmt = $pdo->prepare("SELECT id, email, role, created_at FROM users ORDER BY id");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!$users) {
        echo json_encode([]);
    } else {
        echo json_encode($users);
    }
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['error' => 'Failed to retrieve users']);
}
?> 