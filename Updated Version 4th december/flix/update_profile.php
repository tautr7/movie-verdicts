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
    $new_email = trim($_POST['email']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    try {
        // Verify current password
        $sql = "SELECT password_hash FROM users WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_SESSION['user']['id']]);
        $user = $stmt->fetch();
        
        if (!$user || !password_verify($current_password, $user['password_hash'])) {
            $_SESSION['error'] = "Current password is incorrect.";
            header('Location: profile.php');
            exit;
        }
        
        // Start transaction
        $pdo->beginTransaction();
        
        // Update email if changed
        if ($new_email !== $_SESSION['user']['email']) {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$new_email, $_SESSION['user']['id']]);
            if ($stmt->fetchColumn() > 0) {
                $_SESSION['error'] = "Email address is already in use.";
                header('Location: profile.php');
                exit;
            }
            
            $sql = "UPDATE users SET email = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$new_email, $_SESSION['user']['id']]);
            $_SESSION['user']['email'] = $new_email;
        }
        
        // Update password if provided
        if (!empty($new_password)) {
            if (strlen($new_password) < 8) {
                throw new Exception("Password must be at least 8 characters long.");
            }
            
            if ($new_password !== $confirm_password) {
                throw new Exception("New passwords do not match.");
            }
            
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password_hash = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$password_hash, $_SESSION['user']['id']]);
        }
        
        // Commit transaction
        $pdo->commit();
        $_SESSION['success'] = "Profile updated successfully.";
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        $_SESSION['error'] = $e->getMessage();
    }
    
    header('Location: profile.php');
    exit;
}

header('Location: profile.php');
exit;