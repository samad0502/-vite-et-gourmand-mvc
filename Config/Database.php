<?php 

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    public $conn;

    public function __construct() {
        $jawsdb_url = $_ENV['JAWSDB_URL'];

        if($jawsdb_url){
            $url = parse_url($jawsdb_url);

            $this->host =$url['host'];
            $this->username =$url['user'];
            $this->password =$url['pass'];
            $this->db_name =substr($url['path'], 1);
            $this->port =isset($url['port']) ? $url['port'] : 3306;
        } else {
            $this->host = $_ENV['DB_HOST'];
            $this->username = $_ENV['DB_USER'];
            $this->password = $_ENV['DB_PASS'];
            $this->db_name = $_ENV['DB_NAME'];
            $this->port = 3306;
        }
        
    }


    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8mb4" ;
           
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $exeption) {
                echo "Erreur de connexion : " . $exeption->getMessage();
            }
            return $this->conn;
    }
}