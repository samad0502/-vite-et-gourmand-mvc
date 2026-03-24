<?php 

class Database {
    private $host = 'db_host';
    private $db_name = 'db_name';
    private $username = 'db_username';
    private $password = 'db_password';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            } catch(PDOException $e) {
                echo "Erreur de connexion : " . $e->getMessage();
            }
            return $this->conn;
    }
}