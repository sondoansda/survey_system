<?php
class UserModel
{
    private $id;
    private $email;
    private $ip_address;
    private $created_at;

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function getUserIP()
    {
        $hostname = gethostname();
        $ip = gethostbyname($hostname);
        return $ip;
    }
}
