<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class OrderController {

//affichage de  la page de confirmation avant paiement
public function checkout() {
    if(!isset($_SESSION['user']) || empty($_SESSION['cart'])){
        header('Location: index.php?page=login');
        exit;
    }

    // recup des infos de l'utilisateur pré remplis
    $userModel = new User();
    $u = $userModel->findById($_SESSION['user']['id']);

    //preparation des données du panier pour la vue
    $menuModel = new Menu();
    $cartDetails= [];
    $totalMenus = 0;

    foreach($_SESSION['cart'] as $item) {
        $menuInfo = $menuModel->getMenuById($item['menu_id']);

        //calcul de la promo 
        $isPromo = ($item['number_people'] >= ($menuInfo['min_people'] + 5));
        $prixLigne = $menuInfo['price'] * $item['number_people'];
        if($isPromo) $prixLigne *= 0.9;

        $totalMenus += $prixLigne;

        $cartDetails[] = [
           'title' => $menuInfo['title'],
            'price_unit' => $menuInfo['price'],
            'total_line' => $prixLigne,
            'nb_pers' => $item['number_people'],
            'is_promo' => $isPromo 
        ];
    }

 require_once ROOT . 'app/Views/orders/checkout.php';   
}

//traitement final de la commande
public function process() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
 $groupOrderNumber = 'ORD-' . strtoupper(uniqid());
    $db = (new Database())->getConnection();
    try {
        $db->beginTransaction();
        
        $orderRepo = new OrderRepository($db);
       

        foreach($_SESSION['cart'] as $item){
           $price = !empty($_POST['final_total_price']) ? (float)$_POST['final_total_price'] : 0.00;
//Logique de calcul prix/promo/livraison
            $orderData = [
                'order_number' => $groupOrderNumber,
                'user_id' => $_SESSION['user']['id'],
                'menu_id' => $item['menu_id'],
                'number_people' => $item['number_people'],
                'equipment_ready' => $item['equipment_ready'],
                'delivery_address' => $_POST['address'],
                'delivery_date' => $_POST['delivery_date'],
                'delivery_time' => $_POST['delivery_time'],
                'total_price' => $price
            ];

           $orderRepo->createAndDecrementStock($orderData);
        }

        $db->commit();

        $userEmail = $_SESSION['user']['email'];
        $this->sendConfirmationEmail($userEmail, $groupOrderNumber, $price);

        unset($_SESSION['cart']);
        header('Location: index.php?page=order_success&order_ref=' . $groupOrderNumber);
        exit;

        
    

    } catch(Exception $e){
        $db->rollBack();
        die("Erreur : " . $e->getMessage());
    }
}

public function remove() {
    if (isset($_GET['index'])) {
        $index = $_GET['index'];

        if(isset($_SESSION['cart'][$index])){
        unset($_SESSION['cart'][$index]);

        $_SESSION['cart '] = array_values($_SESSION['cart']);
    }
    }
    header('Location: index.php?page=cart');
        exit;
}


public function list() {
    if(!isset($_SESSION['user'])){
        header('Location: index.php?page=login');
        exit;
    }

    $db = (new Database())->getConnection();
    //recuperation des commandes via le modele order
    $orderRepo = new OrderRepository($db);
    $userOrders = $orderRepo->findByUserId($_SESSION['user']['id']);

    // appel au modele Order
    $orderRepo = new OrderRepository($db);
    $userModel = $orderRepo->findByUserId($_SESSION['user']['id']);

    
   

    require_once ROOT . 'app/Views/orders/list.php';
}


public function edit($id) {
    if(!isset($_SESSION['user'])){
        header('Location: index.php?page=login');
        exit;
    }
    
    $db = (new Database())->getConnection();
    $orderRepo = new OrderRepository($db);
    $order = $orderRepo->findByIdAndUser($id, $_SESSION['user']['id']);

    if(!$order || $order['order_status'] !== 'pending') {
        header('Location: index.php?page=my_orders&error=not_modifiable');
        exit;
    }

    require_once ROOT . 'app/Views/client/edit_order.php';
}

public function update() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $orderId = (int)$_POST['order_id'];
        $db = (new Database())->getConnection();
        $orderRepo = new OrderRepository($db);
    

        //on recalcule le prix total coté serveur par securite(menu*nb_personnes)
        $orderInfo = $orderRepo->findByIdAndUser($orderId, $_SESSION['user']['id']);
        $totalPrice = $orderInfo['price'] * (int)$_POST['number_people'];

    $data = [
                'number_people'    => (int)$_POST['number_people'],
                'delivery_address' => $_POST['delivery_address'],
                'delivery_date'    => $_POST['delivery_date'],
                'delivery_time'    => $_POST['delivery_time'],
                'total_price'      => $totalPrice
            ];   
            
            if($orderRepo->updateOrder($orderId, $data)) {
                header('Location: index.php?page=orders&success=order_updated');
            } else {
                header('Location: index.php?page=edit_order&id=$orderId&error=update_failed');
            }
            exit;
    }
}


public function cancel() {
    if(!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit;
    }

    if(isset($_GET['id'])) {
       $db = (new Database())->getConnection();
        $orderRepo = new OrderRepository($db);

        if($orderRepo->deleteOrder((int)$_GET['id'], $_SESSION['user']['id'])){
            header('Location: index.php?page=orders&success=order_cancelled');
        } else {
            header('Location: index.php?page=orders&error=cancel_failed');
        }
        exit;
    }
}





private function sendConfirmationEmail($userEmail, $orderRef, $total) {
    $mail = new PHPMailer(true);

    try {
        // Configuration SMTP Mailtrap
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
        $mail->addAddress($userEmail);

        // Contenu du mail
        $mail->isHTML(true);
        $mail->Subject = "Confirmation de votre commande $orderRef";
        $mail->Body    = "
            <h1>Merci pour votre commande !</h1>
            <p>Nous avons bien reçu votre demande de prestation.</p>
            <ul>
                <li><strong>Référence :</strong> $orderRef</li>
                <p>Montant total : <strong>" . number_format((float)$total, 2, ',', ' ') . " €</strong></p>
            </ul>
            <p>Vous pouvez suivre l'avancement dans votre espace 'Mes Commandes'.</p>
        ";

        $mail->send();
    } catch (Exception $e) {
      
        error_log("Erreur mail : " . $mail->ErrorInfo);
        return false;
    }
}

}