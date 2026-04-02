<?php

class EmployeeController {

public function dashboard() {
    if($_SESSION['user']['role'] !== 'employee'&& $_SESSION['user']['role'] !== 'admin'){
        header('Location: index.php?page=login');
        exit;
    }
    
  
    
    //gestion des commandes
    $orderModel = new Order();
    $statusFilter = $_GET['status'] ?? '';
    $searchClient = $_GET['client'] ?? '';
    $orders = $orderModel->getOrdersForEmployee($statusFilter, $searchClient);


    //gestion des menus(onglets Menu et Plats)
    $menuModel = new Menu();
    $menus = $menuModel->findAll();

    //gestion des horaires (onglet Horaires)
    $db = (new Database())->getConnection();
    $hours = $db->query("SELECT * FROM opening_hours ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);


    //gestion des avis (onglet Avis)
    $reviews = $db->query("SELECT r.*, u.firstname FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.is_published = 0")->fetchAll(PDO::FETCH_ASSOC);

    
  
    require_once ROOT . 'app/Views/employee/dashboard.php';
}


public function updateOrderStatus() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $orderId = (int)$_POST['order_id'];
        $newStatus = $_POST['new_status'];

        $orderModel = new Order();
        if($orderModel->updateStatus($orderId, $newStatus)) {
            header('Location: index.php?page=employee_dashboard&success=status_updated');
        } else {
            header('Location: index.php?page=employee_dashboard&error=update_failed');

        }
        exit;
    }
}
}