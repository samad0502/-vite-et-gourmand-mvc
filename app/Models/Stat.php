<?php

namespace App\Models;

use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;
use Exception;


class Stat {
    private $collection;
    private $dbname = "vite_gourmand";

    public function __construct()
    {
        $uri = $_ENV['MONGODB_URI'] ?? getenv('MONGODB_URI');

        if(!$uri) {
            throw new Exception("L'URI MongoDB n'est pas configurée dans les variables d'environnement.");
        }

        try {
            $client = new Client($uri);
            $this->collection = $client->{$this->dbname}->order_stats;
        } catch(Exception $e) {
            error_log("Erreur de connexion MongoDB :" . $e->getMessage());
        }

    }
}