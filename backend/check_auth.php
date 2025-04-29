<?php
session_start();
require_once 'config.php';

$response = ['isLoggedIn' => false];

if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        if ($stmt->fetch()) {
            $response['isLoggedIn'] = true;
        }
    } catch(PDOException $e) {
        // Handle error silently
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?> 