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

public function store() {
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        
        $db = (new Database())->getConnection();
        $reviewModel = new Review($db);
        $orderId = $_POST['order_id'];
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];
        $userId = $_SESSION['user']['id'];
  
        if($reviewModel->createReview($orderId, $userId, $rating, $comment)){
          
            header('Location: index.php?page=orders&success=review_sent');
        } else {
            header("Location: index.php?page=add_review&id=$orderId&error=failed");
        }
        exit;
    }
}
}