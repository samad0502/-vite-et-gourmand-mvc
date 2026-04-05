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


public function moderateReview() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $reviewId = (int)$_POST['review_id'];
        $action = $_POST['action'];

        //onutilise la connexion existante pour le modele
        $database = (new Database())->getConnection();
        $reviewModel = new Review($database);

        if($reviewModel->updateStatus($reviewId, $action)) {
            header('Location: index.php?page=employee_dashboard&succes=review_updated#reviews-pane');

        } else {
            header('Location: index.php?page=employee_dashboard&error=review_failed#reviews-pane');
        }
        exit;
    }
}


public function updateHours() {

$userRole = $_SESSION['user']['role'] ?? $_SESSION['user']['role'] ?? '';

    if(!isset($_SESSION['user']) || ($userRole !== 'employee' && $userRole !== 'admin')) {
        header('Location: index.php?page=login');
        exit;
    }

   if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = (new Database())->getConnection();
    

    foreach($_POST['open'] as $id => $openTime) {
        $closeTime = $_POST['close'][$id];
        $isClosed = isset($_POST['closed'][$id]) ? 1 : 0;

        $stmt = $db->prepare("UPDATE opening_hours SET open_time = ?, close_time = ?, is_closed = ? WHERE id = ?");
                $stmt->execute([$openTime, $closeTime, $isClosed, $id]);
    }


   }

   header('Location: index.php?page=employee_dashboard&succes=hours_updated#hours-pane');
exit;

}


public function manageReviews() {
    $db = (new Database())->getConnection();
    $reviewModel = new Review($db);

    if(isset($_GET['action']) && isset($_GET['id'])) {
        $reviewModel->updateStatus($_GET['id'], $_GET['action']);
        header('Location: index.php?page=manage_reviews');
        exit;
    }

    $pendingreviews = $reviewModel->getPendingReviews();
    require_once ROOT . 'app/Views/employee/reviews.php';
}
}