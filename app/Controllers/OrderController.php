<?php

class OrderController {

//affichage de  la page de confirmation avant paiement
public function checkout() {
    if(!isset($_SESSION['user']) || empty($_SESSION['cart'])){
        header('Location: index.php?page=cart');
        exit;
    }

    // recup des infos de l'utilisateur pré remplis
    $userModel = new User();
    $u = $userModel->findById($_SESSION['user']['id']);

 require_once ROOT . 'app/Views/orders/checkout.php';   
}
}