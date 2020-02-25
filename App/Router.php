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
}
