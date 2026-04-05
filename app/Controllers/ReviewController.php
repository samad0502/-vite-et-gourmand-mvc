<?php

class ReviewController {

public function add($orderId) {
    if(!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit;
    }

    //on recupere les infos de la commande pour la vue
    $orderModel = new Order();
    $order = $orderModel->findByIdAndUser($orderId, $_SESSION['user']['id']);

    if(!$order || $order['order_status'] !== 'finished') {
        header('Location: index.php?page=orders&error=not_allowed');
        exit;
    }

    require_once ROOT . 'app/Views/client/add_review.php';
}


}