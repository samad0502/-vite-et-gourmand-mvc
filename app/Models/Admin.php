<?php 


class Admin {
    private $db;

    public function __construct($database)
    {
        $this->db = $database;
    }

  


    // basculer le status de l'utilisateur
    public function toggleUserStatus($userId) {
        $stmt = $this->db->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?");
        return $stmt->execute([$userId]);
    }



    

    // recuperation des commande en cours
    public function getPendingOrders() {
        $sql = "SELECT o.*, u.firstname, m.title as menu_name 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                JOIN menus m ON o.menu_id = m.id 
                WHERE o.order_status NOT IN ('finished', 'cancelled')";

                return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    }


    //compter les employés
    public function countEmployees() {
        return $this->db->query("SELECT COUNT(*) FROM users WHERE role_id = 2")->fetchColumn(); 
    }

    //compter touted les lignes de la table orders
    public function countTotalOrders() {
    return $this->db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
}
}