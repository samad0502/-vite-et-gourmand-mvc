<?php

class ReviewController {

private function getRepo() {
        $db = (new Database())->getConnection();
        return new ReviewRepository($db);
    }

public function add($orderId) {
    if(!isset($_SESSION['user'])) {
        $_SESSION['redirect_url'] = "index.php?page=add-review&id=" .$orderId;
        header('Location: index.php?page=login');
        exit;
    }

    //on recupere les infos de la commande pour la vue
    $db = (new Database())->getConnection();
        $orderRepo = new OrderRepository($db);
        $order = $orderRepo->findByIdAndUser($orderId, $_SESSION['user']['id']);

    if(!$order || $order['order_status'] !== 'finished') {
        header('Location: index.php?page=orders&error=not_allowed');
        exit;
    }

    require_once ROOT . 'app/Views/client/add_review.php';
}

public function store() {
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        
       $repo = $this->getRepo();
        $orderId = $_POST['order_id'];
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];
        $userId = $_SESSION['user']['id'];
  
        if($repo->createReview($orderId, $userId, $rating, $comment)){
          
            header('Location: index.php?page=orders&success=review_sent');
        } else {
            header("Location: index.php?page=add_review&id=$orderId&error=failed");
        }
        exit;
    }
}
}