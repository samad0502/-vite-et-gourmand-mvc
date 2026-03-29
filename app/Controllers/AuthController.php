<?php

class AuthController {

public function showLogin() {
    require_once ROOT . 'app/Views/auth/login.php';
}

public function login() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $userModel = new User();
        $user = $userModel->login($email, $password);

        if($user) {
            $_SESSION['user'] =[
                'id' => $user['id'],
                'firstname' => $user['firstname'],
                'lastname'  => $user['lastname'],
                'email'     => $user['email'],
                'role'      => $user['role_name']
            ];
            // redirection selon le role
            if($user['role-name'] === 'admin') {
                header('Location: index.php?page=admin_dashboard');
            } elseif ($user['role_name'] === 'employee') {
                header('Location: index.php?page=employee_dashboard');
            } else {
                header('Location: index.php?page=menus');
            }
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
        $userModel = new User();
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
        if ($userModel->register($_POST)) {
                    
            header('Location: index.php?page=login&msg=success_register');
            exit;
        } else{
            $error[] = "Erreur lors de l'enregistrement en base de données.";
        } 
    }
        
        require_once ROOT . 'app/Views/auth/register.php';
   
    }
}
}