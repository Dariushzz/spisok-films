<?php
require_once __DIR__ . '/../config/database.php';

class UserModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function register($nombre, $email, $password) {
        $stmt = $this->conn->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->rowCount() > 0) {
            return false;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare(
            "INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)"
        );
        return $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':password' => $hash
        ]);
    }
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
