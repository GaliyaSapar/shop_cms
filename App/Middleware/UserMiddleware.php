<?php


namespace App\Middleware;


use App\DI\Container;
use App\Repository\UserRepository;
use App\Router\Route;
use App\Service\UserService;

class UserMiddleware implements IMiddleware
{
    /**
     * @var UserService
     */
    private $user_service;

    /**
     * @var Container
     */
    private $container;

    public function __construct(UserService $user_service, Container $container)
    {
        $this->user_service = $user_service;
        $this->container = $container;
    }

    public function run(Route $route)
    {
        $controller = $route->getController();

        $user_repository = $this->container->get(UserRepository::class);

        $user = $this->user_service->getCurrentUser($user_repository);
        $controller->addSharedData('user', $user);

    }
}