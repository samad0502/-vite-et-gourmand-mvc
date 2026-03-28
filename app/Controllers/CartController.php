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

}