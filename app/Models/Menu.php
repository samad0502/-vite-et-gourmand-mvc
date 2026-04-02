<?php
require_once __DIR__ . '/../../Config/Database.php';

class Menu {
    private PDO $pdo;
    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    // recup les noms uniques pour les filtre
      public function getUniqueValues(string $type):array 
      {
    if($type === 'theme') {
        $sql = "SELECT DISTINCT t.name
        FROM themes t
        JOIN menus m ON t.id = m.theme_id
        WHERE t.name IS NOT NULL AND t.name != '' ";
    } elseif ($type === 'diet') {
        $sql = "SELECT DISTINCT d.name
        FROM diets d
        JOIN menus m on d.id = m.diet_id
        WHERE d.name IS NOT NULL AND d.name != '' ";
    } else {
        return [];
    }
    
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
        
    
}

// recup de tous les menus avec jointure pour avoir les noms des themes/regimes
    public function getAllMenus(): array 
    {
        $sql = "SELECT m.*, t.name as theme_name, d.name as diet_name 
        FROM menus m
        LEFT JOIN themes t ON m.theme_id = t.id
        LEFT JOIN diets d ON m.diet_id = d.id
        ORDER BY m.created_at DESC ";
        $stmt = $this->pdo->query($sql);
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($menus as &$menu) {
            $images = explode (',', $menu['image'] ?? '');
            $menu['main_image'] = trim($images[0]);
        }
        return $menus;
    }

    public function getFiltredMenus(array $filters): array 
    {
        $sql = "SELECT m.*, t.name as theme_name, d.name as diet_name
        FROM menus m
        LEFT JOIN themes t ON m.theme_id = t.id
        LEFT JOIN diets d ON m.diet_id = d.id
        WHERE 1=1 ";
        $params = [];

        
        // Filtre prix minimum
        if (!empty($filters['priceMin'])) {
            $sql .= " AND m.price >= ?";
            $params[] = $filters['priceMin'];
        }

        // Filtre prix maximum
        if (!empty($filters['priceMax'])) {
            $sql .= " AND m.price <= ?";
            $params[] = $filters['priceMax'];
        }

        // Filtre nombre minimum de personnes
        if (!empty($filters['minPeople'])) {
            $sql .= " AND m.min_people >= ?";
            $params[] = $filters['minPeople'];
        }

        // Filtre thème
        if (!empty($filters['theme'])) {
            $sql .= " AND t.name = ?";
            $params[] = $filters['theme'];
        }

        // Filtre régime
        if (!empty($filters['diet'])) {
            $sql .= " AND d.name = ?";
            $params[] = $filters['diet'];
        }
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($menus as &$menu) {
            $images = explode(',', $menu['image'] ?? '');
            $menu['main_image'] = trim($images[0]);
        }
        return $menus;
    }

    
  public function getMenuById(int $id): ?array
  {
    $sql = "SELECT m.*, t.name as theme_name, d.name as diet_name
    FROM menus m
    LEFT JOIN themes t ON m.theme_id = t.id
    LEFT JOIN diets d ON m.diet_id = d.id
    WHERE m.id = ?"; 

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$id]);
    $menu = $stmt->fetch(PDO::FETCH_ASSOC);

    if($menu){
// preparation des images pour le carousel
    $menu['all_images'] = !empty($menu['image']) ? explode(',', $menu['image']) : [];
      
    }

    return $menu ?: null;
  } 
  
  
  public function create($data) {
    $sql = "INSERT INTO menus (title,price,description, image, min_people) VALUES (?, ?, ?, ?, ?)";
    return $this->pdo->prepare($sql)->execute([
        $data['title'], $data['price'], $data['description'], $data['image'], $data['min_people']
    ]);
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
      
}
