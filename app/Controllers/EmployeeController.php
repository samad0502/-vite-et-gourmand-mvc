<?php

class EmployeeController {

public function dashboard() {
    if($_SESSION['user']['role'] !== 'employee'&& $_SESSION['user']['role'] !== 'admin'){
        header('Location: index.php?page=login');
        exit;
    }
    
  
    
    
    $orderModel = new Order();
    $statusFilter = $_GET['status'] ?? '';
    $searchClient = $_GET['client'] ?? '';
    $orders = $orderModel->getOrdersForEmployee($statusFilter, $searchClient);
    
  
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