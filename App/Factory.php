<?php

class Factory {
    public function __construct()
    {
    }

    public function getInstance(string $class_name) {
        if (!class_exists($class_name)) {
            throw new \Exception('class not exist');
        }

        return new $class_name();
    }
}