<?php


namespace App\DI;


class Container
{
    /**
     * @var Injector
     */
    private $injector;

    /**
     * @var array
     */
    private $singletones = [];

    /**
     * @var array
     */
    private $factories = [];

    public function __construct()
    {
        $this->injector = new Injector($this);
        $this->singletones[self::class] = $this;
    }

    public function get($key) {
        if (!class_exists($key)) {
            throw new \Exception('class not exist');
        }
        return $this->getClass($key);
    }

    public function addSingletone(string $class_name, callable $callback=null) {
        if (is_callable($callback)) {
            $this->factories[$class_name] = $callback;
        }
        $this->singletones[$class_name] = false;
    }

    /**
     * @return Injector
     */
    public function getInjector(): Injector
    {
        return $this->injector;
    }

    private function getClass(string $class_name) {
        $is_singletone = $this->isSingletone($class_name);

        if ($is_singletone) {
            $instance = $this->getSingletone($class_name);

        } else {
            $instance = $this->getInjector()->createClass($class_name);
        }

        return $instance;
    }

    private function isSingletone(string $class_name): bool {
        return isset($this->singletones[$class_name]);
    }

    private function getSingletone(string $class_name) {
        $instance = $this->singletones[$class_name];

        if ($instance == false) {
            $instance = $this->createSingletone($class_name);
        }
        return $instance;
    }

    private function createSingletone(string $class_name) {
        $is_factory_exist = $this->isFactoryExist($class_name);

        if ($is_factory_exist) {
            $factory = $this->getFactory($class_name);
            $instance = $factory();
        } else {
            $instance = $this->getInjector()->createClass($class_name);
        }

        $this->singletones[$class_name] = $instance;
        return $instance;
    }

    private function isFactoryExist(string $class_name) {
        return isset($this->factories[$class_name]) && is_callable($this->factories[$class_name]);
    }

    private function getFactory(string $class_name): callable  {
        return $this->factories[$class_name];
    }
}