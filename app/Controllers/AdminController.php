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
}