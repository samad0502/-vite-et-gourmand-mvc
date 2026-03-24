<?php
require_once 'app/models/review.php';

class HomeController {
    public function index(){
        $database = new Database();
        $db = $database->getConnection();

        // recup des avis via le modèle
        $reviewModel = new Review($db);
        $reviews = $reviewModel->getLatestReviews();

require_once 'app/views/home.php';
    }
}