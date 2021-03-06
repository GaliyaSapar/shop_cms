<?php


namespace App\Controller\Exception;


use App\Controller\ControllerAbstract;
use Throwable;

class MethodDoesNotExistException extends \Exception
{
    public function __construct(ControllerAbstract $controller, string $method, $code = 0, Throwable $previous = null)
    {
        $controller_class_name = get_class($controller);

        $message = "Method '$method' does not exist in controller '$controller_class_name'";
        parent::__construct($message, $code, $previous);
    }

}