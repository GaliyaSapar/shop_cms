<?php

namespace App;

class Router {

    private static $main = '/product/list';

    public static function route() {

        $path = $_SERVER['DOCUMENT_ROOT'] . '/../Router/';

        $request_uri = $_SERVER['REQUEST_URI'];

        $request_data = explode('?', $request_uri);

        $request_url = $request_data[0];

       
        if($request_url == '/') {
            $request_url = static::$main;
        }

        if(preg_match('/\/$/i', $request_url)) {
            $request_url .= 'index';
        }

        $script_path = $path . $request_url . '.php';

        echo '<pre>'; var_dump($script_path); echo '</pre>';

        if (file_exists($script_path) && !is_dir($script_path)) {
            require_once $script_path;
        } else {
            die('404');
        }
    }

    public static function route2() {

        $request_uri = $_SERVER['REQUEST_URI'];

//        echo '<pre>'; var_dump($_SERVER['REQUEST_URI']); echo '</pre>';

        $request_data = explode('?', $request_uri);

        $request_url = $request_data[0];

        $routers = require_once APP_DIR . '/config/routing.php';

//        echo '<pre>'; var_dump($routers); echo '</pre>';

        $route = $routers[$request_url] ?? null;

//        echo '<pre>'; var_dump($route); echo '</pre>';

        if (is_null($route)) {
            die('404');
        }

//        call_user_func_array($route, []);

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
            
            echo '<pre>'; var_dump($className); echo '</pre>';

            $arguments[] = new $className();

        }

        call_user_func_array($route, $arguments);

    }
}


