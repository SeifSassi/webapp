<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get form data (either from $_POST or from JSON input)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    $data = $_POST;
} else {
    // Fallback to JSON data
    $data = json_decode(file_get_contents('php://input'), true);
}

// Validate required fields
if (!isset($data['email']) || !isset($data['password']) || !isset($data['role'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Basic validation
if (empty($data['email']) || empty($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Email and password are required']);
    exit;
}

// Validate email
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

// Validate role
if (!in_array($data['role'], ['user', 'admin'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid role']);
    exit;
}

try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
        exit;
    }
    
    // Hash password
    $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
    
    // Insert new user
    $stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$data['email'], $hashed_password, $data['role']]);
    
    echo json_encode(['success' => true, 'message' => 'User added successfully']);
} catch(PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Failed to add user']);
}
?> 