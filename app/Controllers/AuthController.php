<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController {

public function showLogin() {
    require_once ROOT . 'app/Views/auth/login.php';
}

public function login() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
            $error = "Erreur de sécurité : session invalide. ";
            require_once ROOT . 'Views/auth/login.php';
            exit;
        }
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $db = (new Database())->getConnection();
        $userRepo = new UserRepository($db);
        $user = $userRepo->login($email, $password);

        if($user) {
            $_SESSION['user'] =[
                'id' => $user->getId(),
                'firstname' => $user->getFirstname(),
                'lastname'  => $user->getLastname(),
                'email'     => $user->getEmail(),
                'role'      => $user->getRoleName()
            ];

// ajout pour ajax (Si c'est la modale qui appelle)
            if (isset($_GET['ajax'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            }

            if(isset($_SESSION['redirect-url'])){
                $destination = $_SESSION['redirect_url'];
                unset($_SESSION['redirect_url']);
                header("Location: " . $destination);
                exit;
            }

            // redirection selon le role
            $role = $user->getRoleName();
            if($role === 'admin') {
                header('Location: index.php?page=admin_dashboard');
            } elseif ($role === 'employee') {
                header('Location: index.php?page=employee_dashboard');
            } else {
                header('Location: index.php?page=menus');
            }
            exit;
        }

// gestion d'erreur pour ajax
        if (isset($_GET['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Identifiants incorrects']);
            exit;
        }

        $error = "Identifiants incorrects";
        require_once ROOT . 'app/Views/auth/login.php';
    }
}

public function logout() {
    session_start();
    $_SESSION = array();
    session_destroy();
    header("Location: index.php?page=login");
    exit();
}

public function showRegister() {
    require_once ROOT . 'app/Views/auth/register.php';
}

public function register() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
                header('Location: index.php?page=login&error=csrf');
                exit;
    }
        $db = (new Database())->getConnection();
        $userRepo = new UserRepository($db);
        $errors = [];

        // validation regex mot de passe
        $password = $_POST['password'];
        if (strlen($password) < 10 || 
           !preg_match("/[A-Z]/", $password) || 
           !preg_match("/[a-z]/", $password) || 
           !preg_match("/[0-9]/", $password) ||
           !preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $password)) {
            $errors[] = "Le mot de passe doit contenir 10 caractères, une majuscule et un chiffre.";
        }

        if ($password !== $_POST['password_confirm']) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }

    if (empty($errors)) {
        try {
        if ($userRepo->register($_POST)) {

        // ENVOI DU MAIL 
                    $this->sendWelcomeEmail($_POST['email'], $_POST['firstname']);
                    
            header('Location: index.php?page=login&msg=success_register');
            exit;
        } 
    } catch (Exception $e){
            $error[] = "Erreur lors de l'enregistrement en base de données.";
        } 
    }
        
        require_once ROOT . 'app/Views/auth/register.php';
   
    }
}


private function sendWelcomeEmail($email, $firstname) {
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

        $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($email, $firstname);

        $mail->isHTML(true);
        $mail->Subject = "Bienvenue chez Vite & Gourmand !";
        $mail->Body    = "<h1>Bonjour $firstname !</h1><p>Compte créé avec succès.</p>";

        $mail->send();
    } catch (Exception $e) {
       
        die("Erreur Mailer : " . $mail->ErrorInfo);
    }
}
}