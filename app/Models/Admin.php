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
                  JOIN roles r ON u.role_id r.id
                  WHERE r.name = 'employee'";
         
     return $this->db->query($query)->fetchALL(PDO::FETCH_ASSOC);    
    }


    // basculer le status de l'utilisateur
    public function toggleUserStatus($userId) {
        $stmt = $this->db->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?");
        return $stmt->execute([$userId]);
    }
}