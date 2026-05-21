<?php
require_once __DIR__ . '/../../Config/Database.php';
class MenuRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Récupérer tous les menus avec leurs thèmes et régimes
    public function findAll() {
        $sql = "SELECT m.*, t.name as theme_name, d.name as diet_name 
                FROM menus m
                LEFT JOIN themes t ON m.theme_id = t.id
                LEFT JOIN diets d ON m.diet_id = d.id
                ORDER BY m.created_at DESC";

                $stmt = $this->db->query($sql);
                return $stmt->fetchAll(PDO::FETCH_CLASS, 'Menu');
    }

    // Récupérer un menu par son ID
    public function findById($id) {
        $sql = "SELECT m.*, t.name as theme_name, d.name as diet_name
                FROM menus m
                LEFT JOIN themes t ON m.theme_id = t.id
                LEFT JOIN diets d ON m.diet_id = d.id
                WHERE m.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Menu');
        return $stmt->fetch();
    }

    // La logique des filtres des menus
    public function findWithFilters($filters) {
        $sql = "SELECT m.*, t.name as theme_name, d.name as diet_name
                FROM menus m
                LEFT JOIN themes t ON m.theme_id = t.id
                LEFT JOIN diets d ON m.diet_id = d.id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['priceMin']))      { $sql .= " AND m.price >= ?"; $params[] = $filters['priceMin']; }
        if (!empty($filters['priceMax']))      { $sql .= " AND m.price <= ?"; $params[] = $filters['priceMax']; }
        if (!empty($filters['theme']))         { $sql .= " AND t.name = ?"; $params[] = $filters['theme']; }
        if (!empty($filters['diet']))          { $sql .= " AND d.name = ?"; $params[] = $filters['diet']; }
        if (!empty($filters['minPeople']))     { $sql .= " AND m.minPeople >= ?"; $params[] = $filters['diet']; }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Menu');
       
    }

    // Récupérer les valeurs uniques pour les filtres (thèmes/régimes)
    public function getUniqueAttributes($type) {
        $table = ($type === 'theme') ? 'themes' : 'diets';
        $column = ($type === 'theme') ? 'theme_id' : 'diet_id';
        
        $sql = "SELECT DISTINCT t.name FROM $table t 
                JOIN menus m ON t.id = m.$column 
                WHERE t.name IS NOT NULL AND t.name != ''";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    }

    //  créer un menu (store() du contrôleur)
public function create($data) {
    $sql = "INSERT INTO menus (title, price, description, image, min_people, remaining_quantity, theme_id, diet_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    return $this->db->prepare($sql)->execute([
        $data['title'], $data['price'], $data['description'], $data['image'] ?? '', 
        $data['min_people'] ?? 1, $data['remaining_quantity'], $data['theme_id'], $data['diet_id']
    ]);
}

//  mettre à jour un menu (update() du contrôleur)
public function update($id, $data) {
    $sql = "UPDATE menus SET title = ?, price = ?, description = ?, image = ?, min_people = ? 
            WHERE id = ?";
    return $this->db->prepare($sql)->execute([
        $data['title'], $data['price'], $data['description'], $data['image'], 
        $data['min_people'], $id
    ]);
}


    public function deleteById(int $menuId) {
    try{
       $this->db->exec("SET FOREIGN_KEY_CHECKS = 0;");

            $query = "DELETE from menus WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $menuId, PDO::PARAM_INT);
            $result = $stmt->execute();

            // 2. On réactive la sécurité juste après
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 1;");
            return $result;
    } catch (PDOException $e){

    echo "<h1>Erreur PDO en production :</h1>" . $e->getMessage();
            die();
    }
}
}