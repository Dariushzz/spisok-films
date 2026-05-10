<?php
class Database {
    private $conn;

    public function getConnection() {
        $host     = getenv('MYSQLHOST')     ?: 'YOUR_DB_HOST';
        $db_name  = getenv('MYSQLDATABASE') ?: 'YOUR_DB_NAME';
        $username = getenv('MYSQLUSER')     ?: 'YOUR_DB_USER';
        $password = getenv('MYSQLPASSWORD') ?: 'YOUR_DB_PASSWORD';
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