<?php

class AdminController {
    public function dashboard() {
        if($_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }

        $db = (new Database())->getConnection();
        $adminModel = new Admin($db);
        $orderModel = new Order();

        // 1. Données pour la gestion des employés
        $employees = $adminModel->getAllEmployees();

        //données pour les stats
        $statsData = $adminModel->getMongoStats($_GET['menu'] ?? '', $_GET['start'] ?? '', $_GET['end'] ?? '');

        // traitement des données pour Chart.js
        $menuStats = [];
        $totalCA = 0;
        foreach($statsData as $doc) {
            $name = $doc['menu_name'];
            $menuStats[$name] = ($menuStats[$name] ?? 0 ) + 1 ;
            $totalCA += $doc['price'];
        }
       
        require_once ROOT . 'app/Views/admin/dashboard.php';

        }


        public function createEmployee() {
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $db =(new Database())->getConnection();
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                //insertion role_id(employé ou 2)
                $stmt = $db->prepare("INSERT INTO users (firstname, lastname, email, password, role_id, is_active) VALUES(?, ?, ?, ?, 2, 1");
                $stmt->execute([$_POST['firstname'], $_POST['lastname'], $email, $password]);

                //envoi du mail d'inscription employé (sans mdp)
                $this->sendEmployeeNotification($email, $_POST['firstname']);

                header('location: index.php?page=admin_dashboard&success=created');
            }
        }

               // active ou desactive un compte utilisateur
        public function toggleUser() {
            if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
                $db = (new Database())->getConnection();
                $userId = (int)$_POST['user_id'];

                $stmt = $db->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?");
                $stmt->execute([$userId]);

                header('Location: index.php?page=admin_dashboard&success=status_updated');
                exit;
            }
        }
}