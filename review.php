<?php
require_once __DIR__ . '/bootstrap.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Validate CSRF token
if (!validateCsrfToken($_POST['csrf_token'])) {
    die('Invalid CSRF token');
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'src/Rating.php';
    
    $movie_id = $_POST['tmdb_id'];
    $user_id = $_SESSION['user_id'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];
    
    // Add rating
    $ratingModel = new Rating($pdo);
    $ratingModel->addRating($user_id, $movie_id, $rating);
    
    // Add review
    $sql = "INSERT INTO reviews (user_id, movie_id, review_text) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $movie_id, $review_text]);
    
    // Redirect back to movie page
    header("Location: movie.php?tmdb_id=" . $movie_id);
    exit;
}

// If not POST request, redirect to home
header('Location: index.php');
exit;
