<?php

class CartController {
    
//ajoute un menu au panier via fetch

public function add() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $menuId = $_POST['menu_id'] ?? null;
        $nbPers = (int)($_POST['number_people'] ?? 0);
        $equipment = (int)($_POST['equipment_ready'] ?? 0);

        if ($menuId && $nbPers > 0) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // On récupère les infos du menu pour vérifier le seuil de promo
            $db = (new Database())->getConnection();
            $menuRepo = new MenuRepository($db);
            $menu = $menuRepo->findById((int)$menuId);

            if ($menu) {
                // Initialisation ou cumul de la quantité
                if (isset($_SESSION['cart'][$menuId])) {
                    $totalQty = $_SESSION['cart'][$menuId]['number_people'] + $nbPers;
                } else {
                    $totalQty = $nbPers;
                }

               
                // Promo si convives >= (minimum + 5)
                $isPromo = ($totalQty >= ($menu->getMinPeople() + 5));

                // Stockage en session
                $_SESSION['cart'][$menuId] = [
                    'menu_id' => $menuId,
                    'number_people' => $totalQty,
                    'equipment_ready' => $equipment,
                    'is_promo' => $isPromo 
                ];
            }

           header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
        }
    }
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout']);
    exit;
}

 public function index() {
    //recup du panier en session
    $cart = $_SESSION['cart'] ?? [];
    $cartItems = [];
    $totalGeneral = 0;
     $db = (new Database())->getConnection();
     $menuRepo = new MenuRepository($db);

    foreach ($cart as $index => $item) {
        // Vérification de sécurité : l'item doit être un tableau et contenir 'menu_id'
        if (is_array($item) && isset($item['menu_id'])) {
            $menu = $menuRepo->findById((int)$item['menu_id']);
            
            if ($menu) {
                $nbPers = (int)$item['number_people'];
                $price = (float)$menu->getPrice();
                $subtotal = $price * $nbPers;

         //calcul du sous total avec la promo si +5 convives
                $isPromo = ($nbPers >= ($menu->getMinPeople()+ 5));
                if ($isPromo) $subtotal *= 0.9;

                $totalGeneral += $subtotal;
                $cartItems[] = [
                    'index' => $index,
                    'menu' => $menu,
                    'quantity' => $nbPers,
                    'equipment' => $item['equipment_ready'] ?? 0,
                    'subtotal' => $subtotal,
                    'isPromo' => $isPromo
                ];
            }
        } 
    }
    require_once ROOT . 'app/Views/cart.php';
}

    // Modifier la quantité (via l'index du tableau)
    public function update() {
        if (isset($_POST['index']) && isset($_POST['quantity'])) {
            $index = (int)$_POST['index'];
            $qty = (int)$_POST['quantity'];
            if ($qty > 0 && isset($_SESSION['cart'][$index])) {
                $_SESSION['cart'][$index]['number_people'] = $qty;
            }
        }
        header('Location: index.php?page=cart');
        exit;
    }

    // Supprimer un article
    public function remove() {
        if (isset($_GET['index'])) {
            $index = (int)$_GET['index'];
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Réindexation importante
        }
        header('Location: index.php?page=cart');
        exit;
    }
}

