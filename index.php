<?php
session_start();

require_once 'config/database.php';

// definition de la page (par defaut "accueil")
$page = $_GET['page'] ?? 'home';

switch ($page){
    case 'home' :
        require_once 'app/controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;

        case 'orders' :
            require_once 'app/controllers/OrederController.php';
            $controller = new OrederController();
            $controller->list();
            break;

        default:
        header("HTTP/1.0 404 Not Found");
        echo "Page non trouvée";
        break;
}