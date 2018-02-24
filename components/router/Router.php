<?php

namespace components\router;


class Router
{
    private $routing = [
        ""      => ['controller' => "MainController", 'method' => 'index'],
        "photo" => ['controller' => "MainController", 'method' => 'photo']
    ];

    public function start(){
        $url_array = explode("/", $_SERVER['REQUEST_URI']);
        $route = $url_array[1];

        if(isset($this->routing[$route])){
            $controller = 'components\\controllers\\' . $this->routing[$route]['controller'];
            $controllerObject = new $controller();
            $method = $this->routing[$route]['method'];
            $controllerObject->$method();
        }
        else{
            $controllerObject = new \components\controllers\MainController();
            $controllerObject->error404();
        }
    }
}