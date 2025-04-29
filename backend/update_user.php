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
if (!isset($data['id']) || !is_numeric($data['id']) || $data['id'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
    exit;
}

// Initialize update fields array
$updateFields = [];
$params = [];

if (isset($data['email']) && !empty($data['email'])) {
    // Validate email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        exit;
    }
    $updateFields[] = 'email = ?';
    $params[] = $data['email'];
}

if (isset($data['role']) && !empty($data['role'])) {
    // Validate role
    if (!in_array($data['role'], ['user', 'admin'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid role']);
        exit;
    }
    $updateFields[] = 'role = ?';
    $params[] = $data['role'];
}

if (isset($data['password']) && !empty($data['password'])) {
    $updateFields[] = 'password = ?';
    $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
}

// Check if there are fields to update
if (empty($updateFields)) {
    echo json_encode(['success' => false, 'message' => 'No fields to update']);
    exit;
}

try {
    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$data['id']]);
    
    if ($stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }
    
    // Update user information
    $sql = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE id = ?";
    $params[] = $data['id'];
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    echo json_encode(['success' => true, 'message' => 'User updated successfully']);
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Failed to update user']);
}
?> 