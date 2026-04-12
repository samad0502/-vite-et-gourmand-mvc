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

    //enregistre une vente dans MongoDB
    public function logOrder($orderData) {
        try {
            $document = [
                'order_number'  => $orderData['order_number'],
                'menu_name'     => $orderData['title'],
                'price'         => (float)$orderData['price'],
                'customer'      => $orderData['firstname'] . ' ' . $orderData['lastname'],
                'executed_at'   => new UTCDateTime(time() * 1000)
            ];

            return $this->collection->insertOne($document);
        } catch(Exception $e) {
            error_log("Erreur d'écriture NoSQL : " . $e->getMessage());
            return false;
        }
    }
}