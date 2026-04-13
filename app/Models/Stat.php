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
            $quantity = (int)($orderData['number_people'] ?? 1);
            $unitPrice = (float)($orderData['price'] ?? 0);

           return $this->collection->insertOne([
            'order_number' => $orderData['order_number'],
            'menu_name'    => $orderData['title'] ?? 'Menu inconnu',
            'unit_price'   => $unitPrice,
            'quantity'     => $quantity,
            'price'        => $unitPrice * $quantity, 
            'customer'     => ($orderData['firstname'] ?? '') . ' ' . ($orderData['lastname'] ?? ''),
            'executed_at'  => new \MongoDB\BSON\UTCDateTime(time() * 1000)
        ]);
        } catch(Exception $e) {
            error_log("Erreur d'écriture NoSQL : " . $e->getMessage());
            return false;
        }
    }


    //recupere les statistiques pour le dashboard admin
    public function getStats($menuFilter = '') {
        $filter = [];
        if($menuFilter) {
            $filter['menu_name'] = $menuFilter;
        }

        return $this->collection->find($filter, ['sort' => ['executed_at' => -1]]);
    }


    
   //recupere les statistiques avec filtres (lecture)
public function getFilteredStats($menuFilter = '', $dateStart = '', $dateEnd = '') {
    $filter = [];
    
    // Filtre par nom de menu
    if ($menuFilter) {
        $filter['menu_name'] = $menuFilter;
    }

    // Filtre par date
    if ($dateStart && $dateEnd) {
        $start = new \MongoDB\BSON\UTCDateTime(strtotime($dateStart) * 1000);
        $end = new \MongoDB\BSON\UTCDateTime(strtotime($dateEnd . ' +1 day') * 1000);
        $filter['executed_at'] = ['$gte' => $start, '$lte' => $end];
    }

    return $this->collection->find($filter, ['sort' => ['executed_at' => -1]]);
}

}