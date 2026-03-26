<?php

class Menu {
    private $db;
    public function __construct($database)
    {
        $this->db = $database;
    }

    public function getAll() {
        $query = "SELECT * FROM menus ORDER BY price ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}