<?php


namespace App;


use App\DI\Container;
use App\Http\Request;
use App\Http\Response;
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
//        $this->config = $config;
        $this->router = $router;
        $this->container=$container;
    }

    public function run() {

        $route = $this->router->dispatch();

        $response = $this->dispatch($route);

        $response->send();
    }

    /**
     * @param Route $route
     * @return Response
     */

    private function dispatch(Route $route): Response {
        return $this->container->getInjector()->callMethod(
            $route->getController(),
            $route->getMethod()
        );
    }
}