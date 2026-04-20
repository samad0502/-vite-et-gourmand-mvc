<?php
require_once ROOT . 'app/Models/Review.php';
require_once ROOT . 'app/Models/OpeningHours.php';

class HomeController {
    public function index(){
        $db = (new Database())->getConnection();

        // recup des avis via le modèle
        $reviewRepo = new ReviewRepository($db);
        $reviews = $reviewRepo->getLatestPublished(3);

        // recup des horaires pour le footer
        $hourRepo = new OpeningHoursRepository($db);
        $opening_hours = $hourRepo->findAll();

require_once ROOT . 'app/Views/home.php';
    }
}