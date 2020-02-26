<?php

namespace App;


class Router {

    private $factory;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }


    public function route() {

        $request_uri = $_SERVER['REQUEST_URI'];

        $request_data = explode('?', $request_uri);

        $request_url = $request_data[0];

        $routers = require_once APP_DIR . '/config/routing.php';

        $route = $routers[$request_url] ?? null;

        if (is_null($route)) {
            die('404');
        }

        $class = $route[0];
        $method = $route[1];

        $reflectionClassController = new \ReflectionClass($class);

        if (!$reflectionClassController->hasMethod($method)) {
            die ('503 method does not exist');
        }

        $reflectionMethod = $reflectionClassController->getMethod($method);

        $arguments = [];

        foreach ($reflectionMethod->getParameters() as $parameter) {

            $reflectionParameterClass = $parameter->getClass();

            $className = $reflectionParameterClass->getName();
            
//            echo '<pre>'; var_dump($className); echo '</pre>';

            $arguments[] = $this->factory->getInstance($className);

        }

        call_user_func_array($route, $arguments);

    }
}


