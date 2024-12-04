<?php
class Favorite {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addFavorite($userId, $movieId) {
        $sql = "INSERT IGNORE INTO favorites (user_id, movie_id) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId, $movieId]);
    }

    public function removeFavorite($userId, $movieId) {
        $sql = "DELETE FROM favorites WHERE user_id = ? AND movie_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId, $movieId]);
    }

    public function isFavorite($userId, $movieId) {
        $sql = "SELECT 1 FROM favorites WHERE user_id = ? AND movie_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId, $movieId]);
        return $stmt->fetchColumn() !== false;
    }

    public function getUserFavorites($userId) {
        $sql = "SELECT m.* FROM movies m 
                JOIN favorites f ON m.tmdb_id = f.movie_id 
                WHERE f.user_id = ? 
                ORDER BY f.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
