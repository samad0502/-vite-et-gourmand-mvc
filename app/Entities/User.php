<?php

class User {
    
    private $id;
    private $firstname;
    private $lastname;
    private $email;
    private $password; 
    private $phone;
    private $address;
    private $zip_code;
    private $city;
    private $role_id;
    private $role_name;
    public $created_at;
    public $is_active;
    public $reset_token;
    public $reset_expires_at;
    
    public function getId() { return $this->id; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getPhone() { return $this->phone; }
    public function getAddress() { return $this->address; }
    public function getZipCode() { return $this->zip_code; }
    public function getCity() { return $this->city; }
    public function getRoleId() { return $this->role_id; }
    public function getRoleName() { return $this->role_name; }
    
    public function getFirstname() { 
        return ucfirst($this->firstname); 
    }

    public function getLastname() { 
        return strtoupper($this->lastname); 
    }

    public function getFullName() {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }

   
    public function setId($id) { $this->id = (int)$id; }
    public function setRoleId($role_id) { $this->role_id = (int)$role_id; }
    public function setRoleName($name) { $this->role_name = $name; }
    
    public function setEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        }
    }

    public function setFirstname($name) {
        $this->firstname = htmlspecialchars(trim($name));
    }

    public function setLastname($name) {
        $this->lastname = htmlspecialchars(trim($name));
    }

    public function setPassword($password) {
        $this->password = $password; 
    }
    
    public function setPhone($phone) { $this->phone = htmlspecialchars($phone); }
    public function setAddress($addr) { $this->address = htmlspecialchars($addr); }
    public function setZipCode($zip) { $this->zip_code = htmlspecialchars($zip); }
    public function setCity($city) { $this->city = htmlspecialchars($city); }
}