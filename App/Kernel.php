<?php


namespace App;


use App\DI\Container;
use App\Http\Request;
use App\Http\Response;
use App\Middleware\IMiddleware;
use App\Router\Route;
use App\Router\Router;

final class Kernel
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Container
     */
    private $container;

    public function __construct(Router $router, Container $container)
    {
//        $this->request = $request;

        $this->router = $router;
        $this->container=$container;
        $this->config = $this->container->get(Config::class);
    }

    public function run() {

        $route = $this->router->dispatch();

        $this->runMiddlewares($route);
        $response = $this->dispatch($route);

        $response->send();
    }

    /**
     * @param Route $route
     * @return Response
     * @throws \ReflectionException
     */
    private function dispatch(Route $route): Response {
        return $this->container->getInjector()->callMethod(
            $route->getController(),
            $route->getMethod()
        );
    }

    private function runMiddlewares(Route $route) {
        $middlewares = $this->config->get('middlewares');

        foreach ($middlewares as $middleware_class) {
            $this->runMiddleware($route, $middleware_class);
        }
    }

    private function runMiddleware(Route $route, string $middleware_class) {
        $is_middleware_exist = class_exists($middleware_class);
        if (!$is_middleware_exist) {
            return;
        }

        $implements = class_implements($middleware_class);
        $is_middleware = in_array(IMiddleware::class, $implements);

        if(!$is_middleware) {
            return;
        }

        /**
         * @var $middleware IMiddleware
         */
        $middleware = $this->container->get($middleware_class);
        $middleware->run($route);
    }

}