<?php
// src/Movie.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/SqlLoader.php';

class Movie
{
    private $pdo;

    // Constructor to initialize PDO instance
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get movies by genre with pagination
    public function getMoviesByGenre($genreName, $limit = 10, $offset = 0)
    {
        $sql = SqlLoader::load('select_movies_by_genre.sql');
        $stmt = $this->pdo->prepare($sql);
        // Bind parameters to prevent SQL injection
        $stmt->bindParam(':genre_name', $genreName);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get movie details by TMDB ID
    public function getMovieById($tmdbId)
    {
        $sql = "SELECT tmdb_id, title, overview, release_date, poster_path FROM movies WHERE tmdb_id = :tmdb_id";
        $stmt = $this->pdo->prepare($sql);
        // Bind parameter to prevent SQL injection
        $stmt->bindParam(':tmdb_id', $tmdbId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get all genres
    public function getAllGenres()
    {
        $sql = SqlLoader::load('select_genres.sql');
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
public function getMovieWithReviews($tmdbId)
{
    // Get movie details
    $movie = $this->getMovieById($tmdbId);
    
    if (!$movie) {
        return null;
    }
    
    // Get reviews with user info and ratings
    $sql = "SELECT r.review_text, r.created_at, u.email, rt.rating 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            LEFT JOIN ratings rt ON r.user_id = rt.user_id AND r.movie_id = rt.movie_id 
            WHERE r.movie_id = :tmdb_id 
            ORDER BY r.created_at DESC";
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':tmdb_id', $tmdbId, PDO::PARAM_INT);
    $stmt->execute();
    
    $movie['reviews'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get average rating
    $sql = "SELECT AVG(rating) as average_rating FROM ratings WHERE movie_id = :tmdb_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':tmdb_id', $tmdbId, PDO::PARAM_INT);
    $stmt->execute();
    $movie['average_rating'] = $stmt->fetch(PDO::FETCH_ASSOC)['average_rating'];
    
    return $movie;
}
    public function searchMovies($query) {
    $searchTerm = '%' . $query . '%';
    $sql = "SELECT DISTINCT m.* 
            FROM movies m 
            LEFT JOIN movie_genres mg ON m.tmdb_id = mg.movie_id 
            LEFT JOIN genres g ON mg.genre_id = g.id 
            WHERE m.title LIKE :query 
            OR m.overview LIKE :query 
            OR g.name LIKE :query 
            ORDER BY m.title";
            
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':query', $searchTerm);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
?>
