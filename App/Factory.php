<?php

namespace App;

class Factory {

    private $singletones = [];

    public function __construct()
    {
    }

    public function getInstance(string $class_name) {
        if (!class_exists($class_name)) {
            throw new \Exception('class not exist');
        }

        if(isset($this->singletones[$class_name])) {
            return $this->singletones[$class_name];
        } else {
            return new $class_name();
        }
    }

    public function addSingletone(string $class_name, callable $callback) {
        $this->singletones[$class_name] = $callback();
    }
}