<?php
class Employee {
    private $id;
    private $firstname;
    private $lastname;
    private $email;
    private $is_active;

    public function getId() { return $this->id; }
    public function getEmail() { return $this->email; }
    public function getFirstname() { return ucfirst($this->firstname); }
    public function getLastname() { return strtoupper($this->lastname); }
    public function getFullName() { return $this->getFirstname() . ' ' . $this->getLastname(); }
    public function isActive() { return (bool)$this->is_active; }

    public function setId($id) { $this->id = (int)$id; }
    public function setIsActive($status) { $this->is_active = (int)$status; }
}