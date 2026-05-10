<?php
class Database {
    private $conn;

    public function getConnection() {
        $host     = getenv('MYSQLHOST')     ?: 'sql105.infinityfree.com';
        $db_name  = getenv('MYSQLDATABASE') ?: 'if0_41884257_spisok_films';
        $username = getenv('MYSQLUSER')     ?: 'if0_41884257';
        $password = getenv('MYSQLPASSWORD') ?: 'etpxavierlotfi';
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