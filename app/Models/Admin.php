<?php 

use MongoDb\client;

class Admin {
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

    //gestion des employés
    public function getAllEmployees() {
        $query = "SELECT u.id, u.firstname, u.lastname, u.email, u.is_active
                  FROM users u
                  JOIN roles r ON u.role_id = r.id
                  WHERE r.name = 'employee'";
         
     return $this->db->query($query)->fetchALL(PDO::FETCH_ASSOC);    
    }


    // basculer le status de l'utilisateur
    public function toggleUserStatus($userId) {
        $stmt = $this->db->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?");
        return $stmt->execute([$userId]);
    }

    
    // statistiques nosql mongodb
    public function getMongoStats($menusFilter = '', $dateStart = '', $dateEnd = '' ) {
      $user = "prof_correction";
      $pass = urlencode("ViteGourmand2026");
      $cluster = "cluster0.ziybmvg.mongodb.net";
      $dbname = "vite_gourmand";
      $uri = "mongodb+srv://$user:$pass@$cluster/$dbname?retryWrites=true&w=majority";
      
      $client = new MongoDB\Client($uri);
      $collection = $client->$dbname->order_stats;

      $filter = [];
      if($menusFilter) $filter['menu_name'] = $menusFilter;

      if($dateStart || $dateEnd) {
        $dateRange = [];
        if($dateStart)$dateRange['$gte'] = new MongoDB\BSON\UTCDateTime(strtotime($dateStart) * 1000);
        if($dateEnd) $dateRange['$lte'] = new MongoDB\BSON\UTCDateTime(strtotime($dateEnd . ' +1 day') * 1000);
        $filter['executed_at'] = $dateRange;
        }
        return $collection->find($filter);
    }


    public function addEmployee($data) {
        $password = password_hash($data['password'], PASSWORD_DEFAULT);

        //preparation de la requete avec tous les champs nécessaires pour éviter les érreurs
        $sql = "INSERT INTO users (firstname, lastname, email, password, role_id, is_active, address, city, phone, zip_code)
         VALUES (?, ?, ?, ?, 2, 1, '', '', '', '')";

         $stmt = $this->db->prepare($sql);
         return $stmt->execute([
            $data['firstname'],
            $data['lastname'],
            $data['email'],
            $password
         ]);
    }

    // recuperation des commande en cours
    public function getPendingOrders() {
        $sql = "SELECT o.*, u.firstname, m.title as menu_name 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                JOIN menus m ON o.menu_id = m.id 
                WHERE o.order_status NOT IN ('finished', 'cancelled')
                ORDER BY o.created_at DESC";

                return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    }


    //compter les employés
    public function countEmployees() {
        return $this->db->query("SELECT COUNT(*) FROM users WHERE role_id = 2")->fetchColumn(); 
    }
}