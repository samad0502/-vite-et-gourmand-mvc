<?php

class OpeningHours {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function getAll() {
        $query = "SELECT * FROM opening_hours ORDER BY id ASC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 
    
    
    public function update($id, $open, $close, $isClosed) {
        $sql = "UPDATE opening_hours 
                SET open_time = ?, close_time = ?, is_closed = ?
                WHERE id = ?";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([$open, $close, $isClosed, (int)$id]);
    }
}