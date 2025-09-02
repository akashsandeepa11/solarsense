<?php
require_once '../libraries/Database.php';
class UserModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function register($email, $password, $role = "user") {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (email, password, role) VALUES (:email, :password, :role)");
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hash);
        $stmt->bindParam(":role", $role);
        return $stmt->execute();
    }
}
?>