<?php

class Order {
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    //creation de la commande
    public function createOrder($data) {
        $sql = "INSERT INTO orders (
            order_number, order_status, number_people, equipment_ready,
            user_id, menu_id, delivery_address, delivery_date,
            delivery_time, total_price, order_date)
            VALUES (?, 'pending', ?, ?, ?, ?, ?, ?, ?, ?, NOW()) ";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
            $data['order_number'],
            $data['number_people'],
            $data['equipment_ready'],
            $data['user_id'],
            $data['menu_id'],
            $data['delivery_address'],
            $data['delivery_date'],
            $data['delivery_time'],
            $data['total_price']
            

            ]);
    }
//gestion du stock
    public function updateStock($menuId){
        $sql = "UPDATE menus SET remaining_quantity = remaining_quantity - 1 WHERE id = ?";
        return $this->db->prepare($sql)->execute([$menuId]);
    }


    //recuperer les commandes d'un utilisateur
    public function getByUser($userId) {
        $sql = "SELECT o.*, m.title as menu_title
                FROM orders o
                JOIN menus m ON o.menu_id = m.id
                WHERE o.user_id = ?
                ORDER BY o.order_date DESC ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);        
    }
}

