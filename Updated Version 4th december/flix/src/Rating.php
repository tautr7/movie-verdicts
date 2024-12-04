<?php
class Rating {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addRating($user_id, $movie_id, $rating) {
        $sql = "INSERT INTO ratings (user_id, movie_id, rating, created_at) 
                VALUES (?, ?, ?, NOW()) 
                ON DUPLICATE KEY UPDATE 
                    rating = ?, 
                    updated_at = NOW()";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$user_id, $movie_id, $rating, $rating]);
    }

    public function getRating($movie_id, $user_id) {
        $sql = "SELECT rating FROM ratings WHERE movie_id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$movie_id, $user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['rating'] : null;
    }

    public function getAverageRating($movie_id) {
        $sql = "SELECT AVG(rating) as average_rating FROM ratings WHERE movie_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$movie_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? round($result['average_rating'], 1) : null;
    }

    public function getUserRatings($user_id) {
        $sql = "SELECT r.*, m.title 
                FROM ratings r 
                JOIN movies m ON r.movie_id = m.tmdb_id 
                WHERE r.user_id = ? 
                ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}