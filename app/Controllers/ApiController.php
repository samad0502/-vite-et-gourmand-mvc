<?php
require_once ROOT . 'app/Repositories/MenuRepository.php';

class ApiController {
    public function getFiltredMenus() {
        $db = (new Database())->getConnection();
        $menuRepo = new MenuRepository($db);

        //recup des filtres envoyés par le JS (GET)
        $filters = [
            'priceMin' => $_GET['priceMin'] ?? null,
            'priceMax' => $_GET['priceMax'] ?? null,
            'minPeople' => $_GET['minPeople'] ?? null,
            'theme' => $_GET['theme'] ?? null,
            'diet' => $_GET['diet'] ?? null
        ];

        $result = $menuRepo->findWithFilters($filters);

        //reponse en json
        header('Content-type: application/json');
        echo json_encode($result);
        exit;

    }
}