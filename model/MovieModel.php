<?php
require_once __DIR__ . '/../config/database.php';

class MovieModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function addFavorito($user_id, $tmdb_movie_id) {
        $stmt = $this->conn->prepare(
            "INSERT IGNORE INTO favoritos (user_id, tmdb_movie_id) VALUES (:user_id, :movie_id)"
        );
        return $stmt->execute([':user_id' => $user_id, ':movie_id' => $tmdb_movie_id]);
    }

    public function removeFavorito($user_id, $tmdb_movie_id) {
        $stmt = $this->conn->prepare(
            "DELETE FROM favoritos WHERE user_id = :user_id AND tmdb_movie_id = :movie_id"
        );
        return $stmt->execute([':user_id' => $user_id, ':movie_id' => $tmdb_movie_id]);
    }

    public function getFavoritos($user_id) {
        $stmt = $this->conn->prepare("SELECT tmdb_movie_id FROM favoritos WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function isFavorito($user_id, $tmdb_movie_id) {
        $stmt = $this->conn->prepare(
            "SELECT id FROM favoritos WHERE user_id = :user_id AND tmdb_movie_id = :movie_id"
        );
        $stmt->execute([':user_id' => $user_id, ':movie_id' => $tmdb_movie_id]);
        return $stmt->rowCount() > 0;
    }

    public function addPendiente($user_id, $tmdb_movie_id) {
        $stmt = $this->conn->prepare(
            "INSERT IGNORE INTO pendientes (user_id, tmdb_movie_id) VALUES (:user_id, :movie_id)"
        );
        return $stmt->execute([':user_id' => $user_id, ':movie_id' => $tmdb_movie_id]);
    }

    public function removePendiente($user_id, $tmdb_movie_id) {
        $stmt = $this->conn->prepare(
            "DELETE FROM pendientes WHERE user_id = :user_id AND tmdb_movie_id = :movie_id"
        );
        return $stmt->execute([':user_id' => $user_id, ':movie_id' => $tmdb_movie_id]);
    }

    public function getPendientes($user_id) {
        $stmt = $this->conn->prepare("SELECT tmdb_movie_id FROM pendientes WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function isPendiente($user_id, $tmdb_movie_id) {
        $stmt = $this->conn->prepare(
            "SELECT id FROM pendientes WHERE user_id = :user_id AND tmdb_movie_id = :movie_id"
        );
        $stmt->execute([':user_id' => $user_id, ':movie_id' => $tmdb_movie_id]);
        return $stmt->rowCount() > 0;
    }

    // --- RESEÑAS ---
    public function addResena($user_id, $tmdb_movie_id, $puntuacion, $comentario) {
        $stmt = $this->conn->prepare(
            "INSERT INTO resenas (user_id, tmdb_movie_id, puntuacion, comentario) 
             VALUES (:user_id, :movie_id, :puntuacion, :comentario)
             ON DUPLICATE KEY UPDATE puntuacion = :puntuacion2, comentario = :comentario2"
        );
        return $stmt->execute([
            ':user_id' => $user_id,
            ':movie_id' => $tmdb_movie_id,
            ':puntuacion' => $puntuacion,
            ':comentario' => $comentario,
            ':puntuacion2' => $puntuacion,
            ':comentario2' => $comentario
        ]);
    }

    public function getResena($user_id, $tmdb_movie_id) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM resenas WHERE user_id = :user_id AND tmdb_movie_id = :movie_id"
        );
        $stmt->execute([':user_id' => $user_id, ':movie_id' => $tmdb_movie_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAllResenas($user_id) {
    $stmt = $this->conn->prepare(
        "SELECT tmdb_movie_id as movie_id, puntuacion FROM resenas WHERE user_id = :user_id"
    );
    $stmt->execute([':user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
