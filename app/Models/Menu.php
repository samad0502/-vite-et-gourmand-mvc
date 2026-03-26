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

    public function getFiltred() {
        $sql = "SELECT * FROM menus WHERE 1=1";
        $params = [];

        
        // Filtre prix minimum
        if (!empty($filters['priceMin'])) {
            $sql .= " AND price >= ?";
            $params[] = $filters['priceMin'];
        }

        // Filtre prix maximum
        if (!empty($filters['priceMax'])) {
            $sql .= " AND price <= ?";
            $params[] = $filters['priceMax'];
        }

        // Filtre nombre minimum de personnes
        if (!empty($filters['minPeople'])) {
            $sql .= " AND min_people >= ?";
            $params[] = $filters['minPeople'];
        }

        // Filtre thème
        if (!empty($filters['theme'])) {
            $sql .= " AND theme_id = ?";
            $params[] = $filters['theme'];
        }

        // Filtre régime
        if (!empty($filters['diet'])) {
            $sql .= " AND diet_id = ?";
            $params[] = $filters['diet'];
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUniqueValues($column) {
        // recup uniquement les valeurs distinctes qui ne sont pas nulles
        $query = "SELECT DISTINCT" . $column . "FROM menus WHERE" . $column . "IS NOT NULL AND" . $column . " != ''";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}