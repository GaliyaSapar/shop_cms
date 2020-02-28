<?php


namespace App\DI;


use JsonSchema\Constraints\ObjectConstraint;

class Injector
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function createClass(string $class_name)
    {
        $reflection_class = $this->getReflectionClass($class_name);
        $reflection_constructor =$reflection_class->getConstructor();

        $arguments = [];

        if($reflection_constructor instanceof \ReflectionMethod) {
            $arguments = $this->getDependenciesArray($reflection_constructor);
        }

        return $reflection_class->newInstanceArgs($arguments);
    }

    public function callMethod(Object $object, string $method)
    {
        $reflection_class = $this->getReflectionClass($object);

        if (!$reflection_class->hasMethod($method)) {
            die('503 method does not exist');
        }

        $reflection_method = $reflection_class->getMethod($method);
        $arguments = $this->getDependenciesArray($reflection_method);

        return call_user_func_array([$object, $method], $arguments);
    }


    /**
     * @param $class
     * @return \ReflectionClass
     * @throws \ReflectionException
     */

    private function getReflectionClass($class)
    {
        return new \ReflectionClass($class);
    }

    /**
     * @param \ReflectionMethod $method
     * @return array
     * @throws \Exception
     */

    private function getDependenciesArray(\ReflectionMethod $method): array {
        $arguments=[];

        foreach ($method->getParameters() as $parameter) {
            $reflection_argument = $parameter->getClass();
            $argument_class_name = $reflection_argument->getName();

            $arguments[] = $this->container->get($argument_class_name);
        }
        return $arguments;
    }

}