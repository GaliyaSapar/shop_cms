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


    public function __construct(UserService $user_service)
    {
        $this->user_service = $user_service;
    }

    public function run(Route $route)
    {
        $controller = $route->getController();

//        $user_repository = $this->container->get(UserRepository::class);

        $user = $this->user_service->getCurrentUser();
        $controller->addSharedData('user', $user);

    }
}