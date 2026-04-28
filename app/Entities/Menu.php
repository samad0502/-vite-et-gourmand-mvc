<?php

class Menu implements JsonSerializable {
    public $conditions;
    private $id;
    private $title;
    private $price;
    private $description;
    private $image; 
    private $min_people;
    private $remaining_quantity;
    private $theme_id;
    private $diet_id;
    private $theme_name;
    private $diet_name;
    private $starter;
    private $main_course;
    private $dessert;

  
    public function getId() { return $this->id; }
    public function getTitle() { return htmlspecialchars($this->title); }
    public function getPrice() { return $this->price; }
    public function getDescription() { return $this->description; }
    public function getImage() { return $this->image; }
    public function getMinPeople() { return $this->min_people; }
    public function getRemainingQuantity() { return $this->remaining_quantity; }
    public function getThemeName() { return $this->theme_name ?? 'Classique'; }
    public function getDietName() { return $this->diet_name ?? 'Standard'; }
    public function getStarter() { return $this->starter; }
    public function getMainCourse() { return $this->main_course; }
    public function getDessert() { return $this->dessert; }

    // on extrait la première image
    public function getMainImage() {
        if (empty($this->image))return 'default.jpg';
        $images = explode(',', $this->image);
        return trim($images[0]);
        
    }

    public function getAllImages() {
    if (empty($this->image)) {
        return ['default.jpg'];
    }
    // transforme "image1.jpg,image2.jpg" en ["image1.jpg", "image2.jpg"]
    return array_map('trim', explode(',', $this->image));
}

   
    public function setId($id) { $this->id = (int)$id; }
    public function setTitle($title) { $this->title = $title; }
    public function setPrice($price) { $this->price = (float)$price; }
    public function setDescription($desc) { $this->description = $desc; }
    public function setImage($img) { $this->image = $img; }
    public function setMinPeople($num) { $this->min_people = (int)$num; }
    public function setRemainingQuantity($qty) { $this->remaining_quantity = (int)$qty; }
    public function setThemeName($name) { $this->theme_name = $name; }
    public function setDietName($name) { $this->diet_name = $name; }
    public function setStarter($starter) { return $this->starter = $starter; }
    public function setMainCourse( $main_course) { return $this->main_course = $main_course; }
    public function setDessert($dessert) { return $this->dessert = $dessert; }

    

    /**
     * Permet à json_encode() de voir les propriétés privées
     */
    public function jsonSerialize(): mixed {
        return [
            'id' => $this->id,
            'title' => $this->getTitle(),
            'price' => $this->price,
            'description' => $this->description,
            'main_image' => $this->getMainImage(),
            'min_people' => $this->min_people,
            'remaining_quantity' => $this->remaining_quantity,
            'theme_name' => $this->theme_name,
            'diet_name' => $this->diet_name
        ];
    }
}