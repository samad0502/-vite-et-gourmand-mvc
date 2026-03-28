<?php
session_start();


// SIMULATION DE CONNEXION 
$_SESSION['user'] = ['id' => 1, 'firstname' => 'Testeur'];
require_once __DIR__ . '/vendor/autoload.php';

// chargement des variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);
require_once ROOT . 'Config/Database.php';
require_once ROOT . 'app/Models/Menu.php';



// definition de la page (par defaut "accueil")
$page = $_GET['page'] ?? 'home';

switch ($page){
    case 'home' :
        require_once ROOT . 'app/Controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;

        case 'menus' :
            require_once ROOT . 'app/Controllers/MenuController.php';
            $controller = new MenuController();
            $controller->index();
            break;

        case 'api_menus' :
            require_once ROOT . 'app/Controllers/ApiController.php';
            $api = new ApiController();
            $api->getFiltredMenus();
            break;    

        case 'menu_detail' :
            require_once ROOT . 'app/Controllers/MenuController.php';
            $controller = new MenuController();
            $controller->detail();
            break; 

        case 'add_to_cart' :
            require_once ROOT . 'app/Controllers/CartController.php';
            $controller = new CartController();
            $controller->add();
            break; 

        case 'cart' :
            require_once ROOT . 'app/Controllers/CartController.php';
            $controller = new CartController();
            $controller->index();
            break;

        case 'update_cart' :
            require_once ROOT . 'app/Controllers/CartController.php';
            $controller = new CartController();
            $controller->update();
            break;

        case 'remove_from_cart' :
            require_once ROOT . 'app/Controllers/CartController.php';
            $controller = new CartController();
            $controller->remove();
            break;
/*
        case 'orders' :
            require_once ROOT . 'app/Controllers/OrderController.php';
            $controller = new OrderController();
            $controller->list();
            break;*/

        default:
        header("HTTP/1.0 404 Not Found");
        echo "Page non trouvée";
        break;
}