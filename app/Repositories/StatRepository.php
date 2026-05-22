<?php

namespace App\Repositories;

use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;
use Exception;


class StatRepository {
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

        if(is_object($orderData)){
            $orderData = (array) $orderData;
        }
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
    if (!empty($menuFilter)) {
        $filter['menu_name'] = $menuFilter;
    }

    // Filtre par plage date
    if (!empty($dateStart) || !empty($dateEnd)) {
        $dateCondition = [];
        
        if(!empty($dateStart)){
        // Debut de journée
        $start = strtotime($dateStart . '00:00:00') * 1000;
        $dateCondition['$gte'] = new \MongoDB\BSON\UTCDateTime($start);
    }
    // Fin de journéé pour inclure les ventes du jour
        if(!empty($dateEnd)){
        $end = strtotime($dateEnd . '23:59:59') * 1000;
        $dateCondition['$lte'] = new \MongoDB\BSON\UTCDateTime($end);
        }
         $filter['executed_at'] = $dateCondition;
    }
    
    // Extraction et tri par date décroissante
    return $this->collection->find($filter, ['sort' => ['executed_at' => -1]]);
}

public function logCancellation($orderId, $reason, $contactMode) {
    try {
        return $this->collection->insertOne([
            'event'               => 'order_cancelled',
            'order_id'            => $orderId,
            'cancellation_reason' => $reason,
            'contact_method'      => $contactMode,
            'cancelled_at'        => new \MongoDB\BSON\UTCDateTime(time() * 1000)
        ]);
    } catch(Exception $e) {
        return false;
    }
}
}