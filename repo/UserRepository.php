<?php
require_once "./config/Database.php";
class UserRepository
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    function createUser($email, $ip)
    {
        $sql = "INSERT INTO users (email, ip_address) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $email, PDO::PARAM_STR);
        $stmt->bindParam(2, $ip, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    public function distroy()
    {
        $sql = "DELETE FROM admin_users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$_SESSION['user_id']]);
    }
}
