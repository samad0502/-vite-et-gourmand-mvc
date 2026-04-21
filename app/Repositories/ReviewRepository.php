<?php

class ReviewRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createReview($orderId, $userId, $rating, $comment) {
    $sql = "INSERT INTO reviews (order_id, user_id, rating, comment, created_at)
            VALUES(?, ?, ?, ?, NOW() )";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$orderId, $userId, $rating, $comment]);
}



        //recupere les avis en attente
    public function getPending() {
    $query = "SELECT r.*, u.firstname
              FROM reviews r
              JOIN users u ON r.user_id = u.id
              WHERE r.is_published = 0
              ORDER BY r.created_at DESC";
              return $this->db->query($query)->fetchAll(PDO::FETCH_CLASS, 'Review');
}


       //valider ou supprimer un avis
    public function updateStatus($reviewId, $action) {
    if($action === 'validate'){
        $stmt = $this->db->prepare("UPDATE reviews SET is_published = 1 WHERE id = ?");
        return $stmt->execute([$reviewId]);
    } elseif($action === 'refuse') {
        $stmt = $this->db->prepare("DELETE FROM reviews WHERE id = ?");
        return $stmt->execute([$reviewId]); 
    }
    return false;
}   


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

return $stmt->fetchAll(PDO::FETCH_CLASS, 'Review');

}

   

}