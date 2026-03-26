<?php
require_once ROOT . 'app/Models/Menu.php';

class ApiController {
    public function getFiltredMenus() {
        $database = new Database();
        $db = $database->getConnection();
        $menuModel = new Menu($db);

        //recup des filtres envoyés par le JS (GET)
        $filters = [
            'priceMin' => $_GET['priceMin'] ?? null,
            'priceMax' => $_GET['priceMax'] ?? null,
            'minPeople' => $_GET['minPeople'] ?? null,
            'theme' => $_GET['theme'] ?? null,
            'diet' => $_GET['diet'] ?? null
        ];

        $menus = $menuModel->getFiltredMenus($filters);

        //reponse en json
        header('Content-type: application/json');
        echo json_encode($menus);
        exit;

    }
}