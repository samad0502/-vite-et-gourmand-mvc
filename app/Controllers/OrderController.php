<?php

use App\Services\MailService;
use App\Repositories\StatRepository;

class OrderController {
    private $mailService;

    public function __construct() {
        $this->mailService = new MailService();
    }

private function getRepo() {
        $db = (new Database())->getConnection();
        return new OrderRepository($db);
    }

//affichage de  la page de confirmation avant paiement
public function checkout() {
    if(!isset($_SESSION['user']) || empty($_SESSION['cart'])){
        header('Location: index.php?page=login');
        exit;
    }

    // recup des infos de l'utilisateur pré remplis
    $db = (new Database())->getConnection();
    $userRepo = new UserRepository($db);
    $u = $userRepo->findById($_SESSION['user']['id']);

    //preparation des données du panier pour la vue
    $db = (new Database())->getConnection();
    $menuRepo = new MenuRepository($db);
    
    $cartDetails= [];
    $totalMenus = 0;

    foreach($_SESSION['cart'] as $item) {
        $menuInfo = $menuRepo->findById($item['menu_id']);

        //calcul de la promo 
        $isPromo = ($item['number_people'] >= ($menuInfo->getMinPeople() + 5));
        $prixLigne = $menuInfo->getPrice() * $item['number_people'];
        if($isPromo) $prixLigne *= 0.9;

        $totalMenus += $prixLigne;

        $cartDetails[] = [
           'title' => $menuInfo->getTitle(),
            'price_unit' => $menuInfo->getPrice(),
            'total_line' => $prixLigne,
            'nb_pers' => $item['number_people'],
            'is_promo' => $isPromo 
        ];
    }

 require_once ROOT . 'app/Views/orders/checkout.php';   
}

//traitement final de la commande
public function process() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
              if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
                header('Location: index.php?page=login&error=csrf');
                exit;
    }
            $db = (new Database())->getConnection();
            $orderRepo = new OrderRepository($db);
            $groupOrderNumber = 'ORD-' . strtoupper(uniqid());

            try {
                $db->beginTransaction();
                foreach($_SESSION['cart'] as $item) {
                    // On récupère le prix final envoyé par le formulaire
                    $price = (float)$_POST['final_total_price'];
//Logique de calcul prix/promo/livraison
                    $orderRepo->createAndDecrementStock([
                        'order_number' => $groupOrderNumber,
                        'user_id' => $_SESSION['user']['id'],
                        'menu_id' => $item['menu_id'],
                        'number_people' => $item['number_people'],
                        'equipment_ready' => $item['equipment_ready'],
                        'delivery_address' => $_POST['address'],
                        'delivery_date' => $_POST['delivery_date'],
                        'delivery_time' => $_POST['delivery_time'],
                        'total_price' => $price
                    ]);
                }
                $db->commit();

        $userEmail = $_SESSION['user']['email'];
        $this->mailService->sendConfirmationEmail($userEmail, $groupOrderNumber, $price);

        unset($_SESSION['cart']);
        header('Location: index.php?page=order_success&order_ref=' . $groupOrderNumber);
        exit;
    } catch(Exception $e){
        $db->rollBack();
        die("Erreur : " . $e->getMessage());
    }
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
    //recuperation des commandes via le modele order
   $userOrders = $this->getRepo()->findByUserId($_SESSION['user']['id']);
    
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
          if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
                header('Location: index.php?page=login&error=csrf');
                exit;
    }
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
        $orderId = (int)$_GET['id'];
        $userId = $_SESSION['user']['id'];
        $orderRepo = $this->getRepo();

        if($orderRepo->deleteOrder($orderId, $userId)){
        
        // Si SQL a réussi on log l'événement dans MongoDB
            $statRepo = new StatRepository();
            $statRepo->logCancellation(
                $orderId, 
                $_POST['reason'] ?? 'Annulation client', 
                'Interface Web'
            );

            header('Location: index.php?page=orders&success=order_cancelled');
        } else {
            header('Location: index.php?page=orders&error=cancel_failed');
        }
        exit;
    }
}

}