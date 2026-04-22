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
            delivery_time, total_price, order_date)
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
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Order');
        }

         public function findByIdAndUser($orderId, $userId) {
        $sql = "SELECT o.*, m.title, m.price, m.min_people
                FROM orders o
                JOIN menus m ON o.menu_id = m.id
                WHERE o.id = ? AND o.user_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$orderId, $userId]);
                $stmt->setFetchMode(PDO::FETCH_CLASS, 'Order');
                return $stmt->fetch();
    }

      public function updateOrder($orderId, $data) {
        $sql = "UPDATE orders SET
                number_people = ?,
                delivery_address = ?,
                delivery_date = ?,
                delivery_time = ?,
                total_price = ?
                WHERE id = ? AND order_status = 'pending'";

       $stmt = $this->db->prepare($sql);
       return $stmt->execute([
        $data['number_people'],
        $data['delivery_address'],
        $data['delivery_date'],
        $data['delivery_time'],
        $data['total_price'],
        $orderId
       ]);         
    }

      // supprimer une commande si elle est encore en attente
        public function deleteOrder($orderId, $userId) {
            $sql = "DELETE FROM orders WHERE id = ? AND user_id = ? AND order_status = 'pending'";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$orderId, $userId]);
}

  public function cancelOrders($orderId, $reason, $contactMode) {
        $sql = "UPDATE orders SET order_status = 'cancelled', cancellation_reason = ?, contact_method = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$reason, $contactMode, $orderId]);        
    }

        public function getDetailsForNotification($orderId) {
            $sql = "SELECT o.order_number, o.number_people, m.title, m.price, u.email, u.firstname, u.lastname 
                    FROM orders o 
                    JOIN menus m ON o.menu_id = m.id 
                    JOIN users u ON o.user_id = u.id 
                    WHERE o.id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$orderId]);
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Order');
            return $stmt->fetch();
    }


       public function findById($orderId) {
        // récupère la commande avec les infos du client et du menu 
            $sql = "SELECT o.*, 
                   m.title as menu_title, 
                   u.firstname as client_firstname, 
                   u.lastname as client_lastname, 
                   u.email, 
                   u.phone
            FROM orders o 
            JOIN menus m ON o.menu_id = m.id 
            JOIN users u ON o.user_id = u.id 
            WHERE o.id = ?";
            
           $stmt = $this->db->prepare($sql);
           $stmt->execute([$orderId]);
    
           $stmt->setFetchMode(PDO::FETCH_CLASS, 'Order');
           return $stmt->fetch();
}


     public function updateStatus($orderId, $newStatus) {
        $sql = "UPDATE orders SET order_status = ? WHERE id = ?";
        return $this->db->prepare($sql)->execute([$newStatus, $orderId]);
    }


     public function getOrdersForEmployee($statusFilter = '', $searchClient = ''){
        $query = "SELECT o.*, u.firstname, u.lastname, u.phone, m.title
                  FROM orders o
                  JOIN users u ON o.user_id = u.id  
                  JOIN menus m ON o.menu_id = m.id WHERE 1=1 ";

        $params = [];
        if($statusFilter){
            $query .= " AND o.order_status = ?";
            $params[] = $statusFilter;
        }
        if($searchClient){
            $query .= "AND (u.lastname LIKE ? OR u.firstname LIKE ? OR o.order_number LIKE ?)";
            $searchParam = "%$searchClient%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        $query .= "ORDER BY o.order_date DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Order');
    }
   
}