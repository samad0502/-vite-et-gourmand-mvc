<?php
session_start();

require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/vendor/autoload.php';

// chargement des variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);
require_once ROOT . 'app/Helpers/functions.php';


$db = (new Database())->getConnection();
$hourRepo = new OpeningHoursRepository($db);
$GLOBALS['opening_hours'] = $hourRepo->findAll();







// definition de la page (par defaut "accueil")
$page = $_GET['page'] ?? 'home';

switch ($page){
    case 'home' :
        $controller = new HomeController();
        $controller->index();
        break;

        case 'menus' :
            $controller = new MenuController();
            $controller->index();
            break;

        case 'api_menus' :
            $controller = new MenuController();
            $controller->apiMenus();
            break;    

        case 'menu_detail' :
            $controller = new MenuController();
            $controller->detail();
            break; 

        case 'add_to_cart' :
            $controller = new CartController();
            $controller->add();
            break; 

        case 'cart' :
            $controller = new CartController();
            $controller->index();
            break;

        case 'update_cart' :
            $controller = new CartController();
            $controller->update();
            break;

        case 'remove_from_cart' :
            $controller = new CartController();
            $controller->remove();
            break;

        case 'login':
           $controller = new AuthController();
           $controller->showLogin();
           break;    
        
        case 'auth_login':
           $controller = new AuthController();
           $controller->login();
           break;
    
        case 'logout':
           $controller = new AuthController();
           $controller->logout();
           break;   

        case 'register':
           $controller = new AuthController();
           $controller->showRegister();
           break;


        case 'auth_register':
           $controller = new AuthController();
           $controller->register();
           break;    

        case 'forgot_password':
           $controller = new AuthController();
           $controller->forgotPassword();
           break;

        case 'reset_password':
           $controller = new AuthController();
           $controller->resetPassword();
           break;   
          
        case 'checkout':
           $controller = new OrderController();
           $controller->checkout();
           break;

        case 'process_checkout':
           $controller = new OrderController();
           $controller->process();
           break;   

        case 'order_success':
           require_once ROOT . 'app/Views/orders/order_success.php';
           break;   

        case 'orders' :
           $controller = new OrderController();
           $controller->list();
           break;

        case 'edit_order':
           $controller = new OrderController();
           $controller->edit($_GET['id']);
           break;
        case 'update_client_order':
           $controller = new OrderController();
           $controller->update();
           break;   

        case 'cancel_order':
           $controller = new OrderController();
           $controller->cancel();
           break;   

        case 'employee_dashboard':
           $controller = new EmployeeController();
           $controller->dashboard();
           break;

        case 'update_order_status':
           $controller = new EmployeeController();
           $controller->updateOrderStatus();
           break;    

        case 'cancel_order_employee':
           $controller = new EmployeeController();
           $controller->cancelOrder();
           break;     

        case 'add_menu':
           $controller = new MenuController();
           $controller->add();
           break;

        case 'save_menu':
           $controller = new MenuController();
           $controller->store();
           break;

        case 'edit_menu':
           $controller = new MenuController();
           $controller->edit($_GET['id']);
           break;   

        case 'update_menu':
           $controller = new MenuController();
           $controller->update();
           break;   

        case 'delete_menu':
           $controller = new MenuController();
           $controller->deleteAddedMenu();
           break;    

        case 'moderate_review':
           $controller = new EmployeeController();
           $controller->moderateReview();
           break;   

        case 'update_hours':
           $controller = new EmployeeController();
           $controller->updateHours();
           break;   

        case 'add_review':
           $controller = new ReviewController();
           $controller->add($_GET['id']);
           break;
        case 'store_review':
           $controller = new ReviewController();
           $controller->store();
           break;

        case 'manage_reviews':
           $controller = new EmployeeController();
           $controller->manageReviews();
           break;   

        case 'admin_dashboard':
           $controller = new AdminController();
           $controller->dashboard();
           break;   
           
        case 'admin_users':
           $controller = new AdminController();
           $controller->users();
           break; 
           
        case 'admin_stats':
           $controller = new AdminController();
           $controller->stats();
           break;    

        case 'create_employee':
           $controller = new AdminController();
           $controller->createEmployee();
           break;    
      
        case 'toggle_user':
           $controller = new AdminController();
           $controller->toggleUser();
           break;  

        case 'update_profile':
           $controller = new UserController();
           $controller->updateProfile();
           break;   

        case 'process_contact':
          $controller = new UserController();
          $controller->sendContactMessage();
          break;   

        case 'set_cookie':
          $controller = new CookieController();
          $controller->setConsent();
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