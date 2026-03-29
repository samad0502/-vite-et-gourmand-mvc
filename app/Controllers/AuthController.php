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
                'role' => $user['role_name']
            ];
            // redirection selon le role
            if($user['role-name'] === 'admin') {
                header('Location: index.php?page=admin_dashboard');
            } else {
                header('Location: index.php?page=cart');
            }
            exit;
        }
        $error = "Identifiants incorrects";
        require_once ROOT . 'app/Views/auth/login.php';
    }
}
}