<?php
require_once ROOT . 'app/models/review.php';
require_once ROOT . 'app/models/OpeningHours.php';

class HomeController {
    public function index(){
        $database = new Database();
        $db = $database->getConnection();

        // recup des avis via le modèle
        $reviewModel = new Review($db);
        $reviews = $reviewModel->getLatestPublished();

        // recup des horaires pour le footer
        $hourModel = new OpeningHours($db);
        $opening_hours = $hourModel->getAll();

require_once ROOT . 'app/views/home.php';
    }
}