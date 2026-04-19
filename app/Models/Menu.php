<?php
require_once __DIR__ . '/../../Config/Database.php';

class Menu {
    private PDO $pdo;
    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

  




    
 
  
  

  public function findAll() {
    $stmt = $this->pdo->query("SELECT * FROM menus ORDER BY title ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


    public function findById($id) {
    $stmt = $this->pdo->prepare("SELECT * FROM menus WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
      

   public function create($data) {
    $sql = "INSERT INTO menus (title,price,description, image, min_people, remaining_quantity, theme_id, diet_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    return $this->pdo->prepare($sql)->execute([
        $data['title'], $data['price'], $data['description'], $data['image'] ?? '', $data['min_people'] ?? 1, $data['remaining_quantity'], $data['theme_id'], $data['diet_id']
    ]);
  }


  public function update($id, $data) {
    $sql = "UPDATE menus SET title = ?, price = ?, description = ?, image = ?, min_people = ?
            WHERE id = ?";
    return $this->pdo->prepare($sql)->execute([
        $data['title'], $data['price'], $data['description'], $data['image'] ?? '', $data['min_people'] ?? 1, (int)$id
    ]);        
  }

  public function delete($id) {
        return $this->pdo->prepare("DELETE FROM menus WHERE id = ?")->execute([$id]);
    }

}
