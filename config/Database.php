<?php

class Database
{
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'survey_system';
    private $conn;
    public function __construct()
    {
        $this->connect();
    }
    public function connect()
    {
        $this->conn = null;

        try {
            $dsn = "mysql:host={$this->host};dbname={$this->database}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            echo "Ket noi that bai: " . $e->getMessage();
        }
        return $this->conn;
    }

    public function closeConnection()
    {
        $this->conn = null;
    }
}
