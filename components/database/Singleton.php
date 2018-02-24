<?php

namespace components\database;

trait Singleton
{
    private function __construct() {}
    private function __clone()    {}

    static public function getInstance()
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new static();
        }
        return $instance;
    }
}