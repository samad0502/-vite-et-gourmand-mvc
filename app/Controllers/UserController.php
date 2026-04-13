<?php

class UserController {
    public function updateProfile() {
        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'];

          $data = [
                'firstname' => htmlspecialchars($_POST['firstname']),
                'lastname'  => htmlspecialchars($_POST['lastname']),
                'phone'     => htmlspecialchars($_POST['phone']),
                'address'   => htmlspecialchars($_POST['address'])
            ];
            
            $userModel =new User();

            if($userModel->update($userId, $data)) {
               
            $_SESSION['user']['firstname'] = $data['firstname'];
                $_SESSION['user']['lastname']  = $data['lastname'];
                $_SESSION['user']['phone']     = $data['phone'];
                $_SESSION['user']['address']   = $data['address'];

                header('Location: index/php?page=profile&success=1');
            } else {
                header('Location: index.php?page=profile&error=1');
            }
            exit;
        }
    }
}