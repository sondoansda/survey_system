<?php
require_once './config/database.php';
require_once  './model/AdminUser.php';

class AdminUserRepository
{
    private $conn;
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function findByEmailandPassword($email, $password)
    {
        $sql = "SELECT * FROM admin_users WHERE email = ? AND password = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];  // Store user ID in session
            $_SESSION['email'] = $user['email']; // Store email in session
            $_SESSION['is_logged_in'] = true;    // Flag for logged-in status
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function checkLogin($username, $password)
    {

        $stmt = $this->conn->prepare("SELECT * FROM admin_users WHERE email = ? AND password = ?");
        $stmt->execute([$username, $password]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
