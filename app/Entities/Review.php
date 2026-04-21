<?php

class Review implements JsonSerializable {
    private $id;
    private $order_id;
    private $user_id;
    private $rating;
    private $comment;
    private $is_published;
    private $created_at;

    // propriétés provenant des jointures SQL
    private $firstname;
    private $menu_title;

    
    public function getId() { return $this->id; }
    public function getRating() { return (int)$this->rating; }
    public function getComment() { return htmlspecialchars($this->comment); }
    public function getIsPublished() { return (bool)$this->is_published; }
    public function getAuthorName() { return ucfirst($this->firstname); }
    public function getMenuTitle() { return $this->menu_title; }
    
    public function getFormattedDate() {
        return (new DateTime($this->created_at))->format('d/m/Y');
    }

    
    public function setRating($rating) { $this->rating = (int)$rating; }
    public function setComment($comment) { $this->comment = $comment; }

   
    public function jsonSerialize(): mixed {
        return [
            'id'      => $this->id,
            'rating'  => $this->rating,
            'comment' => $this->getComment(),
            'author'  => $this->getAuthorName(),
            'date'    => $this->getFormattedDate(),
            'menu'    => $this->menu_title
        ];
    }
}