<?php
class Database {
    private $conn;

    public function getConnection() {
        $host     = getenv('MYSQLHOST')     ?: 'localhost';
        $db_name  = getenv('MYSQLDATABASE') ?: 'spisok_films';
        $username = getenv('MYSQLUSER')     ?: 'root';
        $password = getenv('MYSQLPASSWORD') ?: '';
        $port     = getenv('MYSQLPORT')     ?: '3306';

        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4",
                $username,
                $password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
        return $this->conn;
    }
}