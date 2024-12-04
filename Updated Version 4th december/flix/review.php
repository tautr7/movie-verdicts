<?php
require_once __DIR__ . '/bootstrap.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie_id = $_POST['tmdb_id'];
    $user_id = $_SESSION['user']['id'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];
    
    try {
        // Start transaction
        $pdo->beginTransaction();
        
        // Add rating
        $ratingModel = new Rating($pdo);
        $ratingModel->addRating($user_id, $movie_id, $rating);
        
        // Add review
        $sql = "INSERT INTO reviews (user_id, movie_id, review_text, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $movie_id, $review_text]);
        
        // Commit transaction
        $pdo->commit();
        
        $_SESSION['success'] = "Your review has been added successfully.";
    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        $_SESSION['error'] = "Error adding review. Please try again.";
    }
    
    // Redirect back to movie page
    header("Location: movie.php?tmdb_id=" . $movie_id);
    exit;
}

// If not POST request, redirect to home
header('Location: index.php');
exit;