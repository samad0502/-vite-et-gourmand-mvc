<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Repositories\StatRepository;
class EmployeeController {

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
        $statRepo->logOrder((array)$orderInfo);

        $this->notifyOrderFinished($orderId);
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
    
            $this->sendCancellationEmail($order, $reason);
    

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



public function notifyOrderFinished($orderId) {
    $db = (new Database())->getConnection();
    $orderRepo = new OrderRepository($db);
    $data = $orderRepo->getDetailsForNotification($orderId); 
    
    if($data) {
        $mail = new PHPMailer(true);

        try {
            
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USER'];
        $mail->Password   = $_ENV['MAIL_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port       = $_ENV['MAIL_PORT'];
        $mail->CharSet    = 'UTF-8';
        // Destinataires
        $mail->setFrom('no-reply@vitegourmand.fr', 'ViteGourmand');
        $mail->addAddress($data->getEmail(), $data->getClientFirstname());

        // Contenu du mail
        $mail->isHTML(true);
        $mail->Subject = "Votre commande " . $data->getOrderNumber() . " est prete !";

        $reviewLink = "http://localhost:3000/index.php?page=add_review&id=" . $orderId;
       
        $mail->Body    = "
                <h1>Bonne nouvelle " . $data->getClientFirstname() . " !</h1>
                <p>Votre commande est désormais terminée. Nous espérons que vous avez apprécié votre expérience.</p>
                <p>Votre avis est précieux pour nous. Pourriez-vous nous laisser une note ?</p>
                <a href='{$reviewLink}' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>
                    Donner mon avis
                </a>
                <p>À bientôt chez <strong>Vite & Gourmand</strong> !</p>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Erreur Mailtrap : {$mail->ErrorInfo}");
            return false;
        }

        }
    }


    // Fonction dédiée à l'envoi du mail d'annulation
private function sendCancellationEmail($order, $reason) {

   

    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USER'];
        $mail->Password   = $_ENV['MAIL_PASS'];
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $_ENV['MAIL_PORT'];
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('service-client@vitegourmand.fr', 'Vite & Gourmand');
        $mail->addAddress($order->getEmail(), $order->getClientFirstname());

        $mail->isHTML(true);
        $mail->Subject = "Annulation de votre commande " . $order->getOrderNumber();

        $mail->Body = "
            <h2>Bonjour " . $order->getClientFirstname(). "</h2>
            <p>Nous vous informons que votre commande <strong>#" . $order->getOrderNumber() . "</strong> a dû être annulée.</p>
            <p><strong>Motif de l'annulation :</strong><br><em>{$reason}</em></p>
            <p>Si vous avez des questions, n'hésitez pas à nous recontacter.</p>
            <p>Cordialement,<br>L'équipe Vite & Gourmand</p>
        ";

        $mail->send();
    } catch (\Exception $e) {
        error_log("Le mail d'annulation n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}");
    }
}
}