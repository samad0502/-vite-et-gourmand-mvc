<?php

class CartController {
    
//ajoute un menu au panier via fetch

public function add() {
    header('Content-Type: application/json');

    //verification de la connexion
    if(!isset($_SESSION['user'])){
        echo json_encode(['success' => false, 'message' => 'veuillez vous connecter pour commander.']);
        exit;
    }

    $menu_id = $_POST['menu_id'] ?? null;
    $quantity = (int)($_POST['number_people'] ?? 0);
    $equipment = (int)($_POST['equipment_ready'] ?? 0);

    if(!$menu_id || $quantity <= 0) {
        echo json_encode(['success' => false, 'message' => 'Données invalides.']);
        exit;
    }

    //initialisation du panier en session si inexistant
    if(!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // ajout ou maj du produit
    $_SESSION['cart'][$menu_id] = [
        'quantity' => $quantity,
        'equipment' => $equipment,
        'added_at' => date('Y-m-d H:i:s')
    ];

    echo json_encode([
       'success' => true,
       'message' => 'Menu ajouté au panier !',
       'cart_count' => count($_SESSION['cart']) 
    ]);
    exit;
}

   public function index(){
    //recup du panier en session
        $cart = $_SESSION['cart'] ?? [];
        $cartItems = [];
        $totalGeneral = 0;

        //si le panier n'est pas vide, recup des infos en bdd 
                $menuModel = new Menu();

        foreach($cart as $index => $item){
            $menu = $menuModel->getMenuById($item['menu_id']);
            if ($menu) {
                $nbPers = (int)$item['number_people'];
                $price = (float)$menu['price'];
                $subtotal = $price * $nbPers;

                //calcul du sous total avec la promo si +5 convives
                $isPromo = ($nbPers >= ($menu['min_people'] + 5));
                if ($isPromo) {
                    $subtotal *= 0.9;
                }

                $totalGeneral += $subtotal;
                $cartItems[] = [
                    'index' => $index,
                    'menu' => $menu,
                    'quantity' => $nbPers,
                    'equipment' => $item['equipment_ready'],
                    'subtotal' => $subtotal,
                    'isPromo' => $isPromo
                ];
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

