<?php
class Review {
private $db;
public function __construct($database)
{
    $this->db = $database;
}

// recupération des derniers avis avec les details utilisateur et menu

public function getLatestPublished($limit = 3){
    $query = "SELECT r.*, u.firstname, m.title as menu_title
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    JOIN orders o ON r.order_id = o.id
    JOIN menus m ON o.menu_id = m.id
    WHERE r.is_published = 1
    ORDER BY r.created_at DESC
    LIMIT :limit";


$stmt = $this->db->prepare($query);
//on lie la limite pour eviter les injection
$stmt->bindValue(':limit' , (int)$limit, PDO::PARAM_INT);
$stmt->execute();

return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

//recupere les avis en attente
public function getPendingReviews() {
    $query = "SELECT r.*, u.firstname
              FROM reviews r
              JOIN users u ON r.user_id = u.id
              WHERE r.ispublished = 0
              ORDER BY r.created_at DESC";
              return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
}

}