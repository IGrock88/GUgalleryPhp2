<?php

namespace components\controllers;


use components\model\Model;
use components\view\View;

class MainController
{

    private $model;
    private $view;

    public function __construct()
    {
        $this->model = new Model();
        $this->view = new View();
    }

    public function index(){
        $content['images'] = $this->model->getImages();
        $content['page'] = 'index.tmpl';
        $this->view->generate($content);
    }

    public function photo(){
        $url_array = explode("/", $_SERVER['REQUEST_URI']);
        if($url_array[2] == null){
            $this->error404();
        }else{
            $content['page'] = 'photo.tmpl';
            $content['images'] = $this->model->getImageById($url_array[2]);
            $this->view->generate($content);
        }
    }

    public function error404(){
         echo "Error 404 PAGE";
    }
}