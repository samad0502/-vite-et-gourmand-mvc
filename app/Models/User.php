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
}