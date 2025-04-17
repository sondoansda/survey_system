<?php
require_once './repo/AdminUserRepository.php';

class AdminUserService
{
    private $repo;

    public function __construct()
    {
        $this->repo = new AdminUserRepository();
    }

    public function adminLogin($username, $password)
    {
        return $this->repo->findByEmailandPassword($username, $password);
    }
    public function checkLogin($username, $password)
    {
        return $this->repo->checkLogin($username, $password);
    }
}
