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
    $grandTotal = 0;

    //si le panier n'est pas vide, recup des infos en bdd 
    if(!empty($cart)) {
        $menuModel = new Menu();

        foreach($cart as $id => $details){
            $menu = $menuModel->getMenuById($id);

            if($menu) {
                $quantity = (int)$details['quantity'];
                $price = (float)$menu['price'];


    //calcul du sous total avec la promo si +5 convives   
    $subtotal = $price * $quantity;
    $isPromo = ($quantity >= ($menu['min_peaople'] + 5));
    
    if($isPromo){
        $subtotal *= 0.9;
    }

    $cartItems[] =[
        'id' => $id,
        'title' =>$menu['title'],
        'price' => $price,
        'quantity' => $quantity,
        'equipment' => $details['equipment'],
        'subtotal' => $subtotal,
        'isPromo' => $isPromo
    ];
    $grandTotal += $subtotal;
            }
        }
       
    }
require_once ROOT . 'app/Views/cart.php';
}

}