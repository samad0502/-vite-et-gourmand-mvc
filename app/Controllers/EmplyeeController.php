<?php

class AdminController {

public function dashboard() {
    if($_SESSION['user']['role'] !== 'employee'&& $_SESSION['user']['role'] !== 'admin'){
        header('Location: index.php');
        exit;
    }
    
    // logique de filtrage 
    $statusFilter = $_GET['status'] ?? '';
    $search = $_GET['client'] ?? '';
    
    $orderModel = new Order();
    $orders = $orderModel->getFilteredOrders($statusFilter, $search);
    
  
    require_once ROOT . 'app/Views/employee/dashboard.php';
}
}