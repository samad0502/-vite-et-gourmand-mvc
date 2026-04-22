<?php

class Order implements JsonSerializable {
    private $id;
    private $order_number;
    private $order_status;
    private $number_people;
    private $equipment_ready;
    private $delivery_address;
    private $delivery_date;
    private $delivery_time;
    private $total_price;
    private $order_date;
    private $user_id;
    private $menu_id;
    
    // propriétés venant des jointures SQL
    private $menu_title;
    private $client_firstname;
    private $client_lastname;
    private $phone;
    private $email; 
    private $menu_name;



   
    public function getId() { return $this->id; }
    public function getOrderNumber() { return $this->order_number; }
    public function getStatus() { return $this->order_status; }
    public function getNumberPeople() { return $this->number_people; }
    public function getTotalPrice() { return $this->total_price; }
    public function getDeliveryDate() { return $this->delivery_date; }
     public function getDeliveryTime() { return $this->delivery_time; }
     public function getDeliveryAddress() { return $this->delivery_address; }
    public function getOrderDate() { return $this->order_date; }
    public function getMenuTitle() { return $this->menu_title; }
    public function getClientFirstname() { return $this->client_firstname; }
    public function getClientLastname() { return $this->client_lastname; }
    public function getPhone() { return $this->phone; }
    public function getEmail() { return $this->email; }
    public function getMenuName() { return $this->menu_name; }

    // libellé du statut en français
    public function getStatusLabel() {
        $labels = [
            'pending'   => 'En attente',
            'confirmed' => 'Confirmée',
            'cancelled' => 'Annulée',
            'delivered' => 'Livrée'
        ];
        return $labels[$this->order_status] ?? $this->order_status;
    }

   
    public function setId($id) { $this->id = (int)$id; }
    public function setStatus($status) { $this->order_status = $status; }
    public function setTotalPrice($price) { $this->total_price = (float)$price; }

    // pour l'API ou le JS 
    public function jsonSerialize(): mixed {
        return [
            'id' => $this->id,
            'number' => $this->order_number,
            'status' => $this->getStatusLabel(),
            'total' => $this->total_price,
            'date' => $this->order_date
        ];
    }
}