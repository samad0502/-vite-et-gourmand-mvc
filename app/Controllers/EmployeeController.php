<?php
use App\Services\MailService;
use App\Repositories\StatRepository;
class EmployeeController {
    private $mailService;

public function __construct() {
        $this->mailService = new MailService();
    }

public function dashboard() {
    if($_SESSION['user']['role'] !== 'employee'&& $_SESSION['user']['role'] !== 'admin'){
        header('Location: index.php?page=login');
        exit;
    }
    
    //gestion des commandes
    $db = (new Database())->getConnection();
    $orderRepo = new OrderRepository($db);
    $statusFilter = $_GET['status'] ?? '';
    $searchClient = $_GET['client'] ?? '';
    $orders = $orderRepo->getOrdersForEmployee($statusFilter, $searchClient);


    //gestion des menus(onglets Menu et Plats)
    $db = (new Database())->getConnection();
    $menuRepo = new MenuRepository($db);
    $menus = $menuRepo->findAll();

    //gestion des horaires (onglet Horaires)
    $db = (new Database())->getConnection();
    $hourRepo = new OpeningHoursRepository($db);
    $opening_hours = $hourRepo->findAll();


    //gestion des avis (onglet Avis)
    $db = (new Database())->getConnection();
    $reviewRepo = new ReviewRepository($db);
    $reviews = $reviewRepo->getPending();

    
  
    require_once ROOT . 'app/Views/employee/dashboard.php';
}


public function updateOrderStatus() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $orderId = (int)$_POST['order_id'];
        $newStatus = $_POST['new_status'];

        $db = (new Database())->getConnection();
        $orderRepo = new OrderRepository($db);
        if($orderRepo->updateStatus($orderId, $newStatus)) {

            
        if ($newStatus === 'finished') {
        $orderInfo = $orderRepo->getDetailsForNotification($orderId);
    
        if ($orderInfo) {
        $statRepo = new StatRepository();

        $orderDataArray = [
        'order_number'  => $orderInfo->getOrderNumber(),
        'number_people' => $orderInfo->getNumberPeople(),
        'price'         => $orderInfo->getTotalPrice(),
        'title'         => $orderInfo->getTitle(),
        'firstname'     => $orderInfo->getClientFirstname(),
        'lastname'      => $orderInfo->getClientLastname()
    ];
        $statRepo->logOrder($orderDataArray);

        $this->mailService->notifyOrderFinished($orderInfo);
    }
}
            header('Location: index.php?page=employee_dashboard&success=status_updated');
        } else {
            header('Location: index.php?page=employee_dashboard&error=update_failed');

        }
        exit;
    }
}


public function cancelOrder(){
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $orderId = (int)$_POST['order_id'];
        $reason = htmlspecialchars($_POST['reason']);
        $contactMode = $_POST['contact_method'];

        $db = (new Database())->getConnection();
        $orderRepo = new OrderRepository($db);
        $order = $orderRepo->findById($orderId);
        
        if($order){
        
        if($orderRepo->updateStatus($orderId, 'cancelled')) {

        $statRepo = new StatRepository();
        $statRepo->logCancellation($order->getId(), $reason, $contactMode);
        
        //envoi du mail que si le mode de contact choisi est 'mail'
    
            $this->mailService->sendCancellationEmail($order, $reason);
    

        header('Location: index.php?page=employee_dashboard&success=cancelled');
        } else {
            header('Location: index.php?page=employee_dashboars&error=cancel_failed');
        } 
        exit;
    }
}
}


public function moderateReview() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $reviewId = (int)$_POST['review_id'];
        $action = $_POST['action'];

        //onutilise la connexion existante pour le modele
        $db = (new Database())->getConnection();
        $reviewRepo = new ReviewRepository($db);

        if($reviewRepo->updateStatus($reviewId, $action)) {
            header('Location: index.php?page=employee_dashboard&succes=review_updated#reviews-pane');

        } else {
            header('Location: index.php?page=employee_dashboard&error=review_failed#reviews-pane');
        }
        exit;
    }
}


public function updateHours() {

$userRole = $_SESSION['user']['role'] ?? '';

    if(!isset($_SESSION['user']) || ($userRole !== 'employee' && $userRole !== 'admin')) {
        header('Location: index.php?page=login');
        exit;
    }

   if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = (new Database())->getConnection();
    $hourRepo = new OpeningHoursRepository($db);

    foreach($_POST['open'] as $id => $openTime) {
        $closeTime = $_POST['close'][$id];
        $isClosed = isset($_POST['closed'][$id]) ? 1 : 0;

       
        $hourRepo->update($id, $openTime, $closeTime, $isClosed);
    }

   }

   header('Location: index.php?page=employee_dashboard&succes=hours_updated#hours-pane');
exit;

}


public function manageReviews() {
    $db = (new Database())->getConnection();
    $reviewRepo = new ReviewRepository($db);

    if(isset($_GET['action']) && isset($_GET['id'])) {
        $reviewRepo->updateStatus($_GET['id'], $_GET['action']);
        header('Location: index.php?page=manage_reviews');
        exit;
    }

    $pendingreviews = $reviewRepo->getPending();
    require_once ROOT . 'app/Views/employee/reviews.php';
}


}