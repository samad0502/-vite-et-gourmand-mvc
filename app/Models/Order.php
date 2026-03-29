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
            $data['address'],
            $data['delivery_date'],
            $data['delivery_time'],
            $data['total_price']

            ]);
    }
}

