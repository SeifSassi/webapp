<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Validate user ID
if (!isset($data['id']) || !is_numeric($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
    exit;
}

$userId = $data['id'];

// Prevent admin from deleting themselves
if ($userId == $_SESSION['user_id']) {
    echo json_encode(['success' => false, 'message' => 'Cannot delete your own account']);
    exit;
}

try {
    // Check if user exists
    $stmt = $pdo->prepare("SELECT id, role FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }
    
    // Delete the user
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    
    echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
}
?> 