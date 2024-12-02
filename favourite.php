<?php
require_once __DIR__ . '/bootstrap.php';
require_once 'src/Favorite.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Validate CSRF token
if (!validateCsrfToken($_POST['csrf_token'])) {
    die('Invalid CSRF token');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movieId = $_POST['tmdb_id'];
    $userId = $_SESSION['user_id'];
    
    $favoriteModel = new Favorite($pdo);
    
    // Check if it's already a favorite
    if ($favoriteModel->isFavorite($userId, $movieId)) {
        // Remove from favorites
        $favoriteModel->removeFavorite($userId, $movieId);
        $message = "Removed from favorites";
    } else {
        // Add to favorites
        $favoriteModel->addFavorite($userId, $movieId);
        $message = "Added to favorites";
    }
    
    // Redirect back to movie page
    header("Location: movie.php?tmdb_id=" . $movieId . "&message=" . urlencode($message));
    exit;
}

// If not POST request, redirect to home
header('Location: index.php');
exit;
