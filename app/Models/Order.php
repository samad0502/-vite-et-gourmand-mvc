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
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function findByIdAndUser($orderId, $userId) {
        $sql = "SELECT o.*, m.title, m.price, m.min_people
                FROM orders o
                JOIN menus m ON o.menu_id = m.id
                WHERE o.id = ? AND o.user_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$orderId, $userId]);
                return $stmt->fetch(PDO::FETCH_ASSOC);
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


    public function deleteOrder($orderId, $userId) {
        $sql = "DELETE FROM orders WHERE id = ? AND user_id =? AND order_status = 'pending'";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$orderId, $userId]);
    }


    public function getOrderDetailForNotification($orderId) {
        $sql = "SELECT o.order_number, o.number_people, m.title, m.price,  u.email, u.firstname, u.lastname 
            FROM orders o 
            JOIN menus m ON o.menu_id = m.id 
            JOIN users u ON o.user_id = u.id 
            WHERE o.id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$orderId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

