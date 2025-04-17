<?php

class Controller
{
    public function model($model)
    {
        require_once 'models/' . $model . '.php';
        return new $model;
    }

    public function view($view, $data = [])
    {
        extract($data);
        require_once 'view/' . $view . '.php';
    }
    public function view1($view)
    {
        require_once 'view/' . $view . '.php';
    }
}
