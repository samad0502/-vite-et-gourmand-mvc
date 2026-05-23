<?php

class UserRepository {
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findById($id) {
    $sql = "SELECT id, firstname, lastname, email, phone, address, zip_code, city
    FROM users
    WHERE id = ?";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
    return $stmt->fetch();
    }

    // fonction chercher par email
    public function findByEmail($email) {
        $sql = "SELECT id, firstname, lastname, email 
                FROM users 
                WHERE email = :email AND is_active = 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        return $stmt->fetch(); 
    }


     public function login($email, $password){
        $sql = "SELECT u.*, r.name as role_name
        FROM users u
        JOIN roles r ON u.role_id = r.id
        WHERE u.email = :email AND u.is_active = 1 ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        $user = $stmt->fetch();

        if($user && password_verify($password, $user->getPassword())){
            return $user;
        }
        return false;

    }


     public function update($id, $data) {
        $sql = "UPDATE users SET
                firstname = ?,
                lastname = ?,
                phone = ?,
                address = ?
                WHERE id = ?";

       $stmt = $this->db->prepare($sql);
       return $stmt->execute([
        $data['firstname'],
        $data['lastname'],
        $data['phone'],
        $data['address'],
        $id
       ]);         
    }


    public function register(array $data): bool {
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("
        INSERT INTO users
        (firstname, lastname, address, city, zip_code, phone, email, password, role_id, is_active)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ");

        return $stmt->execute([
            $data['firstname'], $data['lastname'], $data['address'],
            $data['city'], $data['zip_code'], $data['phone'],
            $data['email'], $passwordHash, $data['role_id'] ?? 3, 1
        ]);
    }

    // enregistre le token pour un email donné
    public function saveResetToken($email, $token, $expiresAt) {
        $query = "UPDATE FROM users SET reset_token = :tokrn, resest_exprires_ar = :expires WHERE email = :email";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'token' => $token,
            'expirex' => $expiresAt,
            'email' => $email
        ]);
    }

    // verifie si un token est valide et non expiré
    public function getUsersByToken($token) {
        $query = "SELECT * FROM users WHERE reset_token = :token AND reset_expires > NOW()";
        $stmt = $this->db->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        $stmt->execute(['token' => $token]);
        return $stmt->fetch();
        

    }

    // met a jour le MDP et detruit le token utilisé
    public function updatePassword($userId, $hashedPassword) {
        $query = "UPDATE users SET password = :password, reset_token = NULL, reset_expires_at = NULL WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            'password' => $hashedPassword,
            'id' => $userId
        ]);
    }
}