<?php

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

    $db = (new Database())->getConnection();
    try {
        $db->beginTransaction();
        $orderModel = new Order();
        $groupOrderNumber = 'ORD-' . strtoupper(uniqid());

        foreach($_SESSION['cart'] as $item){
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
                'total_price' => $_POST['final_total_price']
            ];

            $orderModel->createOrder($orderData);
            $orderModel->updateStock($item['menu_id']);

            //mise a jour du stock
            $db->prepare("UPDATE menus SET remaining_quantity = remaining_quantity - 1 WHERE id = ?")
            ->execute([$item['menu_id']]);
        }

        $db->commit();
        unset($_SESSION['cart']);
        header('Location: index.php?page=order_success&ref=' . $groupOrderNumber);

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

    require_once ROOT . 'app/Views/orders/list.php';
}


}