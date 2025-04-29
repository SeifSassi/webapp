<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    try {
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email format']);
            exit;
        }

        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            exit;
        }

        // Hash password and insert user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->execute([$email, $hashedPassword]);

        echo json_encode(['success' => true, 'message' => 'Registration successful']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()]);
    }
}
?>
