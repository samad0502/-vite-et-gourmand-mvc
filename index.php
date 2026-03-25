<?php
session_start();
define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);
require_once ROOT . 'config/database.php';



// definition de la page (par defaut "accueil")
$page = $_GET['page'] ?? 'home';

switch ($page){
    case 'home' :
        require_once ROOT . 'app/controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;

        case 'orders' :
            require_once ROOT . 'app/controllers/OrederController.php';
            $controller = new OrderController();
            $controller->list();
            break;

        default:
        header("HTTP/1.0 404 Not Found");
        echo "Page non trouvée";
        break;
}