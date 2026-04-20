<?php

class AdminRepository {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
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

      // basculer le status de l'utilisateur
    public function toggleUserStatus($userId) {
        $stmt = $this->db->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?");
        return $stmt->execute([$userId]);
    }

      //gestion des employés
    public function getAllEmployees() {
        $query = "SELECT u.id, u.firstname, u.lastname, u.email, u.is_active
                  FROM users u
                  JOIN roles r ON u.role_id = r.id
                  WHERE r.name = 'employee'";
         
     return $this->db->query($query)->fetchALL(PDO::FETCH_ASSOC);    

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

    //recupere les avis en attente
    public function getPendingReviews() {
    $query = "SELECT r.*, u.firstname
              FROM reviews r
              JOIN users u ON r.user_id = u.id
              WHERE r.is_published = 0
              ORDER BY r.created_at DESC";
              return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
}

      public function getOpeningHours() {
       return $this->db->query("SELECT * FROM opening_hours ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
    } 


    public function updateOpeningHours($id, $open, $close, $isClosed) {
        $sql = "UPDATE opening_hours 
                SET open_time = ?, close_time = ?, is_closed = ?
                WHERE id = ?";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([$open, $close, $isClosed, (int)$id]);
    }

   //valider ou supprimer un avis
public function updateReviewStatus($reviewId, $action) {
    if($action === 'validate'){
        $stmt = $this->db->prepare("UPDATE reviews SET is_published = 1 WHERE id = ?");
        return $stmt->execute([$reviewId]);
    } elseif($action === 'refuse') {
        $stmt = $this->db->prepare("DELETE FROM reviews WHERE id = ?");
        return $stmt->execute([$reviewId]); 
    }
    return false;
}   

}