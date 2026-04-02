<?php

class AdminController {

public function dashboard() {
    if($_SESSION['user']['role'] !== 'employee'&& $_SESSION['user']['role'] !== 'admin'){
        header('Location: index.php');
        exit;
    }

    $orderModel = new Order();
   // on recupere toutes les commandes
   $orders = $orderModel->getAllOrders();

   require_once ROOT . 'app/Views/admin/dashboard.php';
}
}