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
}
?>