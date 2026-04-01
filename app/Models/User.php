<?php


class User {
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
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

    public function login($email, $password){
        $sql = "SELECT u.*, r.name as role_name
        FROM users u
        JOIN roles r ON u.role_id = r.id
        WHERE u.email = :email AND u.is_active = 1 ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($password, $user['password'])){
            return $user;
        }
        return false;

    }


    public function findById($id) {

    $sql = "SELECT id, firstname, lastname, email, phone, address, zip_code, city
    FROM users
    WHERE id = ?";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}