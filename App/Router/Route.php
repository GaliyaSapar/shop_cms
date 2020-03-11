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
     * @var array
     */
    private $params;
    /**
     * Route constructor.
     * @param ControllerAbstract $controller
     * @param string $method
     * @param array $params
     * @throws MethodDoesNotExistException
     */
    public function __construct(ControllerAbstract $controller, string $method, array $params = [])
    {
        $this->isMethodExist($controller, $method);

        $this->controller = $controller;
        $this->method = $method;
        $this->params = $params;

        $this->controller->setRoute($this);
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
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    public function getParam(string $key) {
        return $this->params[$key] ?? null;
    }

    /**
     * @param ControllerAbstract $controller
     * @param string $method
     * @return bool
     * @throws MethodDoesNotExistException
     */

    private function isMethodExist(ControllerAbstract $controller, string $method) {
        $reflection_controller = new \ReflectionObject($controller);

        if (!$reflection_controller->hasMethod($method)) {
            throw new MethodDoesNotExistException($controller, $method);
        }
        return true;
    }

}