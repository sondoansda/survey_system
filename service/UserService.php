<?php
require_once './config/Database.php';
require_once './repo/UserRepository.php';

require_once './model/UserModel.php';
class UserService
{
    private $repo;
    public function __construct()
    {
        $this->repo = new UserRepository();
    }
    public function createUser($email, $ip)
    {
        return $this->repo->createUser($email, $ip);
    }

    public function distroy()
    {
        return $this->repo->distroy();
    }
}
