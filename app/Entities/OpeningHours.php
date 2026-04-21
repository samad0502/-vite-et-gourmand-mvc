<?php

class OpeningHours {
    private $id;
    private $day_name;
    private $open_time;
    private $close_time;
    private $is_closed;

   
    public function getId() { return $this->id; }
    public function getDayName() { return $this->day_name; }
    
    // formate l'heure pour ne pas afficher les secondes 
    public function getOpenTime() { 
        return $this->open_time ? (new DateTime($this->open_time))->format('H:i') : ''; 
    }
    
    public function getCloseTime() { 
        return $this->close_time ? (new DateTime($this->close_time))->format('H:i') : ''; 
    }

    public function isClosed() { 
        return (bool)$this->is_closed; 
    }

    // pour simplifier l'affichage dans le footer
    public function getDisplayRange() {
        if ($this->isClosed()) {
            return "Fermé";
        }
        return $this->getOpenTime() . ' - ' . $this->getCloseTime();
    }

  
    public function setId($id) { $this->id = (int)$id; }
    public function setDayName($name) { $this->day_name = $name; }
    public function setOpenTime($time) { $this->open_time = $time; }
    public function setCloseTime($time) { $this->close_time = $time; }
    public function setIsClosed($status) { $this->is_closed = (int)$status; }
}