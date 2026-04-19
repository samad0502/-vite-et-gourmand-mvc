<?php

class OrderRepository {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }    
     // sauvegarde d'une commande et maj du stock
        public function createAndDecrementStock($data){
            $sqlOrder = "INSERT INTO orders (
            order_number, order_status, number_people, equipment_ready,
            user_id, menu_id, delivery_address, delivery_date, 
            delivery_time, tota_price, order_date)
            VALUES(?, 'pending', ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

            $stmtOrder = $this->db->prepare($sqlOrder);
            $stmtOrder->execute([
                $data['order_number'], $data['number_people'], $data['equipment_ready'],
            $data['user_id'], $data['menu_id'], $data['delivery_address'],
            $data['delivery_date'], $data['delivery_time'], $data['total_price']
            ]);
            $sqlStock = "UPDATE menus SET remaining_quantity = remaining_quantity -1 WHERE id = ?";
            return $this->db->prepare($sqlStock)->execute([$data['menu_id']]);

        
        }

        //recuperer les commandes d'un client avec les titres des menus
        public function findByUserId($userId){
            $sql = "SELECT o.*, m.title as menu_title
                FROM orders o
                JOIN menus m ON o.menu_id = m.id
                WHERE o.user_id = ?
                ORDER BY o.order_date DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

      
   
}