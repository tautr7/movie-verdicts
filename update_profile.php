<?php
require_once __DIR__ . '/bootstrap.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Validate CSRF token
if (!validateCsrfToken($_POST['csrf_token'])) {
    die('Invalid CSRF token');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Verify current password
    $sql = "SELECT password_hash FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['user']['id']]);
    $user = $stmt->fetch();
    
    if (!password_verify($current_password, $user['password_hash'])) {
        $_SESSION['error'] = "Current password is incorrect.";
        header('Location: profile.php');
        exit;
    }
    
    // Update email if changed
    if ($new_email !== $_SESSION['user']['email']) {
        $sql = "UPDATE users SET email = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$new_email, $_SESSION['user']['id']]);
        $_SESSION['user']['email'] = $new_email;
    }
    
    // Update password if provided
    if (!empty($new_password)) {
        if ($new_password !== $confirm_password) {
            $_SESSION['error'] = "New passwords do not match.";
            header('Location: profile.php');
            exit;
        }
        
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password_hash = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$password_hash, $_SESSION['user']['id']]);
    }
    
    $_SESSION['success'] = "Profile updated successfully.";
    header('Location: profile.php');
    exit;
}

header('Location: profile.php');
exit;
