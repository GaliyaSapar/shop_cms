<?php


namespace App\Router;


use App\Controller\ControllerAbstract;
use App\Controller\Exception\MethodDoesNotExistException;

class Route
{
    /**
     * @var ControllerAbstract
     */
    private $controller;

    /**
     * @var string
     */
    private $method;

    /**
     * Route constructor.
     * @param ControllerAbstract $controller
     * @param string $method
     * @throws MethodDoesNotExistException
     */
    public function __construct(ControllerAbstract $controller, string $method)
    {
        $this->isMethodExist($controller, $method);

        $this->controller = $controller;
        $this->method = $method;
    }

    /**
     * @return ControllerAbstract
     */
    public function getController(): ControllerAbstract
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param ControllerAbstract $controller
     * @param string $method
     * @return bool
     * @throws MethodDoesNotExistException
     */

    private function isMethodExist(ControllerAbstract $controller, string $method) {
        $reflection_controller = new \ReflectionObject($controller);

        if (!$reflection_controller->hasMethod()) {
            throw new MethodDoesNotExistException($controller, $method);
        }
        return true;
    }

}