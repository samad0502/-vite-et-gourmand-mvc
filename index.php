<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';

// chargement des variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);
require_once ROOT . 'Config/Database.php';
require_once ROOT . 'app/Repositories/MenuRepository.php';
require_once ROOT . 'app/Repositories/UserRepository.php';
require_once ROOT . 'app/Repositories/OrderRepository.php';
require_once ROOT . 'app/Models/Review.php';
require_once ROOT . 'app/Repositories/AdminRepository.php';
require_once ROOT . 'app/Models/OpeningHours.php';
require_once ROOT . 'app/Helpers/functions.php';


$db = (new Database())->getConnection();
$hourRepo = new OpeningHoursRepository($db);
$GLOBALS['opening_hours'] = $hourRepo->findAll();







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

        case 'login':
           require_once ROOT . 'app/Controllers/AuthController.php';
           $controller = new AuthController();
           $controller->showLogin();
           break;    
        
        case 'auth_login':
           require_once ROOT . 'app/Controllers/AuthController.php';
           $controller = new AuthController();
           $controller->login();
           break;
    
        case 'logout':
           require_once ROOT . 'app/Controllers/AuthController.php';
           $controller = new AuthController();
           $controller->logout();
           break;   

        case 'register':
           require_once ROOT . 'app/Controllers/AuthController.php';
           $controller = new AuthController();
           $controller->showRegister();
           break;


        case 'auth_register':
           require_once ROOT . 'app/Controllers/AuthController.php';
           $controller = new AuthController();
           $controller->register();
           break;    
          
        case 'checkout':
           require_once ROOT . 'app/Controllers/OrderController.php';
           $controller = new OrderController();
           $controller->checkout();
           break;

        case 'process_checkout':
           require_once ROOT . 'app/Controllers/OrderController.php';
           $controller = new OrderController();
           $controller->process();
           break;   

        case 'order_success':
           require_once ROOT . 'app/Views/orders/order_success.php';
           break;   

        case 'orders' :
           require_once ROOT . 'app/Controllers/OrderController.php';
           $controller = new OrderController();
           $controller->list();
           break;

        case 'edit_order':
           require_once ROOT . 'app/Controllers/OrderController.php';
           $controller = new OrderController();
           $controller->edit($_GET['id']);
           break;
        case 'update_client_order':
           require_once ROOT . 'app/Controllers/OrderController.php';
           $controller = new OrderController();
           $controller->update();
           break;   

        case 'cancel_order':
           require_once ROOT . 'app/Controllers/OrderController.php';
           $controller = new OrderController();
           $controller->cancel();
           break;   

        case 'employee_dashboard':
           require_once ROOT . 'app/Controllers/EmployeeController.php';
           $controller = new EmployeeController();
           $controller->dashboard();
           break;

        case 'update_order_status':
           require_once ROOT . 'app/Controllers/EmployeeController.php';
           $controller = new EmployeeController();
           $controller->updateOrderStatus();
           break;    

        case 'cancel_order_employee':
           require_once ROOT . 'app/Controllers/EmployeeController.php';
           $controller = new EmployeeController();
           $controller->cancelOrder();
           break;     

        case 'add_menu':
           require_once ROOT . 'app/Controllers/MenuController.php';
           $controller = new MenuController();
           $controller->add();
           break;

        case 'save_menu':
           require_once ROOT . 'app/Controllers/MenuController.php';
           $controller = new MenuController();
           $controller->store();
           break;

        case 'edit_menu':
           require_once ROOT . 'app/Controllers/MenuController.php';
           $controller = new MenuController();
           $controller->edit($_GET['id']);
           break;   

        case 'update_menu':
           require_once ROOT . 'app/Controllers/MenuController.php';
           $controller = new MenuController();
           $controller->update();
           break;   

        case 'moderate_review':
           require_once ROOT . 'app/Controllers/EmployeeController.php';
           $controller = new EmployeeController();
           $controller->moderateReview();
           break;   

        case 'update_hours':
           require_once ROOT . 'app/Controllers/EmployeeController.php';
           $controller = new EmployeeController();
           $controller->updateHours();
           break;   

        case 'add_review':
           require_once ROOT . 'app/Controllers/ReviewController.php';
           $controller = new ReviewController();
           $controller->add($_GET['id']);
           break;
        case 'store_review':
           require_once ROOT . 'app/Controllers/ReviewController.php';
           $controller = new ReviewController();
           $controller->store();
           break;

        case 'manage_reviews':
           require_once ROOT . 'app/Controllers/EmployeeController.php';
           $controller = new EmployeeController();
           $controller->manageReviews();
           break;   

        case 'admin_dashboard':
           require_once ROOT . 'app/Controllers/AdminController.php';
           $controller = new AdminController();
           $controller->dashboard();
           break;   
           
        case 'admin_users':
           require_once ROOT . 'app/Controllers/AdminController.php';
           $controller = new AdminController();
           $controller->users();
           break; 
           
        case 'admin_stats':
           require_once ROOT . 'app/Controllers/AdminController.php';
           $controller = new AdminController();
           $controller->stats();
           break;    

        case 'create_employee':
           require_once ROOT . 'app/Controllers/AdminController.php';
           $controller = new AdminController();
           $controller->createEmployee();
           break;    
      
        case 'toggle_user':
           require_once ROOT . 'app/Controllers/AdminController.php';
           $controller = new AdminController();
           $controller->toggleUser();
           break;  

        case 'update_profile':
           require_once ROOT . 'app/Controllers/UserController.php';
           $controller = new UserController();
           $controller->updateProfile();
           break;   

        case 'process_contact':
          require_once ROOT . 'app/Controllers/UserController.php';
          $controller = new UserController();
          $controller->sendContactMessage();
          break;   

        case 'profile':
           require_once ROOT . 'app/Views/user/profile.php'; 
           break;

        case 'contact':
           require_once ROOT . 'app/Views/public/contact.php';
           break;

        case 'cgv':
           require_once ROOT . 'app/Views/public/cgv.php';
           break;

        case 'mentions-legales':
           require_once ROOT . 'app/Views/public/mentions-legales.php';
           break;   

        default:
        header("HTTP/1.0 404 Not Found");
        echo "Page non trouvée";
        break;
}