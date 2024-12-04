<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/SqlLoader.php';

class Movie
{
    private $pdo;
    
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllMovies()
    {
        $sql = "SELECT m.*, AVG(r.rating) as average_rating 
                FROM movies m 
                LEFT JOIN ratings r ON m.tmdb_id = r.movie_id 
                GROUP BY m.tmdb_id, m.title, m.overview, m.release_date, m.poster_path 
                ORDER BY m.title";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getMoviesByGenre($genreName, $limit = 10, $offset = 0)
    {
        $sql = "SELECT DISTINCT m.*, AVG(r.rating) as average_rating 
                FROM movies m 
                LEFT JOIN ratings r ON m.tmdb_id = r.movie_id 
                JOIN movie_genres mg ON m.tmdb_id = mg.movie_id 
                JOIN genres g ON mg.genre_id = g.id 
                WHERE g.name = :genre_name 
                GROUP BY m.tmdb_id, m.title, m.overview, m.release_date, m.poster_path 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':genre_name', $genreName);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMovieById($tmdbId)
    {
        $sql = "SELECT m.*, AVG(r.rating) as average_rating 
                FROM movies m 
                LEFT JOIN ratings r ON m.tmdb_id = r.movie_id 
                WHERE m.tmdb_id = :tmdb_id 
                GROUP BY m.tmdb_id, m.title, m.overview, m.release_date, m.poster_path";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':tmdb_id', $tmdbId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllGenres()
    {
        $sql = "SELECT id, name FROM genres ORDER BY name";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMovieWithReviews($tmdbId)
    {
        // Get movie details with average rating
        $movie = $this->getMovieById($tmdbId);
        
        if (!$movie) {
            return null;
        }
        
        // Get reviews with user info and ratings
        $sql = "SELECT r.*, u.email, rt.rating 
                FROM reviews r 
                JOIN users u ON r.user_id = u.id 
                LEFT JOIN ratings rt ON r.user_id = rt.user_id AND r.movie_id = rt.movie_id 
                WHERE r.movie_id = :tmdb_id 
                ORDER BY r.created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':tmdb_id', $tmdbId, PDO::PARAM_INT);
        $stmt->execute();
        
        $movie['reviews'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $movie;
    }

    public function searchMovies($query) {
        $searchTerm = '%' . $query . '%';
        $sql = "SELECT DISTINCT m.*, AVG(r.rating) as average_rating 
                FROM movies m 
                LEFT JOIN ratings r ON m.tmdb_id = r.movie_id 
                LEFT JOIN movie_genres mg ON m.tmdb_id = mg.movie_id 
                LEFT JOIN genres g ON mg.genre_id = g.id 
                WHERE m.title LIKE :query 
                   OR m.overview LIKE :query 
                   OR g.name LIKE :query 
                GROUP BY m.tmdb_id, m.title, m.overview, m.release_date, m.poster_path 
                ORDER BY m.title";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':query', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}