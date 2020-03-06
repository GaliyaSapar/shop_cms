<?php

namespace App\Router;

use App\Config;
use App\Controller\Exception\MethodDoesNotExistException;
use App\DI\Container;
use App\Http\Request;

class Router {

    /**
     * @var Container
     */
    private $container;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Config
     */
    private $config;

    public function __construct(Container $container, Request $request, Config $config)
    {
        $this->container=$container;
        $this->config=$config;
        $this->request=$request;
    }

    public function dispatch() {
        $routes = $this->config->get('routes');

        $url = $this->request->getUrl();
        $route = $routes[$url] ?? null;

        if (is_null($route)) {
//            die('404');
            $this->notFound();
        }

        $controller = $this->container->get($route[0]);
//        $this->container->getInjector()->callMethod($controller, $route[1]);
        $method = $route[1];

        try {
            $route = new Route($controller, $method);
        } catch (MethodDoesNotExistException $e) {
            $this->notFound();
        }

        return $route;

    }

    private function getRoutes() {
        $controllers = $this->config->get('controllers');

        $routes = [];

        foreach ($controllers as $controller) {
            $reflection_controller = new \ReflectionClass($controller);
            $methods = $reflection_controller->getMethods();

            foreach ($methods as $method) {
                $doc_comment = $method->getDocComment();

                $matches = [];
                preg_match_all('/@Route\(.+\)/im', $doc_comment, $matches);

                if (empty($matches[0])) {
                    continue;
                }

                $annotation_routes = $matches[0];

                foreach ($annotation_routes as $annotate_route) {
                    $annotate_params = str_replace('@Route(', '', $annotate_route);
                    $annotate_params = str_replace(')', '', $annotate_params);

                    $annotate_params = explode(',', $annotate_params);
                    $annotate_params = array_map(function ($item) {
                        return trim($item);
                    }, $annotate_params);

                    $params = [];

                    foreach ($annotate_params as $param_str) {
                        $param_data = explode('=', $param_str);
                        $key = $param_data[0];
                        $value = $param_data[1];

                        $value = str_replace('\"', '', $value);

                        $params[$key] = $value;
                    }




                    $routes[$params['url']] = [
                        $controller, $method->getName(),
                    ];
                }
            }
        }

        return $routes;
    }

    private function notFound() {
        die('404');
    }

//    public function route() {
//
//        $request_uri = $_SERVER['REQUEST_URI'];
//
//        $request_data = explode('?', $request_uri);
//
//        $request_url = $request_data[0];
//
//        $routers = require_once APP_DIR . '/config/routes.php';
//
//        $route = $routers[$request_url] ?? null;

//        if (is_null($route)) {
//            die('404');
//        }

//        $class = $route[0];
//        $method = $route[1];
//
//        $reflectionClassController = new \ReflectionClass($class);
//
//        if (!$reflectionClassController->hasMethod($method)) {
//            die ('503 method does not exist');
//        }
//
//        $reflectionMethod = $reflectionClassController->getMethod($method);
//
//        $arguments = [];
//
//        foreach ($reflectionMethod->getParameters() as $parameter) {
//
//            $reflectionParameterClass = $parameter->getClass();
//
//            $className = $reflectionParameterClass->getName();
//
//           echo '<pre>'; var_dump($className); echo '</pre>';
//
//            $arguments[] = $this->factory->getInstance($className);
//
//        }
//
//        call_user_func_array($route, $arguments);

//    }
}


