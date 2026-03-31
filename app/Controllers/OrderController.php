<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class OrderController {

//affichage de  la page de confirmation avant paiement
public function checkout() {
    if(!isset($_SESSION['user']) || empty($_SESSION['cart'])){
        header('Location: index.php?page=cart');
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
        $orderModel = new Order();
       

        foreach($_SESSION['cart'] as $item){
            $price = !empty($_POST['final_total_price']) ? $_POST['final_total_price'] : 0;
//Logique de calcul prix/promo/livraison
            $orderData = [
                'order_number' => $groupOrderNumber,
                'user_id' => $_SESSION['user']['id'],
                'menu_id' => $item['menu_id'],
                'number_people' => $item['number_people'],
                'equipment_ready' => $item['equipment_ready'],
                'address' => $_POST['address'],
                'delivery_date' => $_POST['delivery_date'],
                'delivery_time' => $_POST['delivery_time'],
                'total_price' => $price
            ];

            $orderModel->createOrder($orderData);
            $orderModel->updateStock($item['menu_id']);
        }

        $db->commit();

        $userEmail = $_SESSION['user']['email'];
        $this->sendConfirmationEmail($userEmail, $groupOrderNumber, $_POST['final_total_price']);

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

    // appel au modele Order
    $orderModel = new Order();
    $userModel = $orderModel->getByUser($_SESSION['user']['id']);

    //recuperation des commandes via le modele order
    $orderModel = new Order();
    $userOrders = $orderModel->getByUser($_SESSION['user']['id']);

    require_once ROOT . 'app/Views/orders/list.php';
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