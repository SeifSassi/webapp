<?php
session_start();
require_once 'config.php';

$response = ['isAdmin' => false];

if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        
        if ($user && $user['role'] === 'admin') {
            $response['isAdmin'] = true;
        }
    } catch(PDOException $e) {
        // Handle error silently
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?> 