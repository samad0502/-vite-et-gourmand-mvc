<?php

class OpeningHoursRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    
     // Récupère tous les horaires rangés par ID (lundi à dimanche)
     
    public function findAll() {
        $query = "SELECT * FROM opening_hours ORDER BY id ASC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


     // Met à jour une plage horaire spécifique
    public function update($id, $open, $close, $isClosed) {
        $sql = "UPDATE opening_hours 
                SET open_time = ?, close_time = ?, is_closed = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$open, $close, $isClosed, (int)$id]);
    }
}