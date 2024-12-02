<?php

class Rating {
    private $db;
    private $user_id;
    private $movie_id;
    private $rating;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addRating($user_id, $movie_id, $rating) {
        $sql = "INSERT INTO ratings (user_id, movie_id, rating) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE rating = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$user_id, $movie_id, $rating, $rating]);
    }

    public function getRating($movie_id, $user_id) {
        $sql = "SELECT rating FROM ratings WHERE movie_id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$movie_id, $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAverageRating($movie_id) {
        $sql = "SELECT AVG(rating) as average_rating FROM ratings WHERE movie_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$movie_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
