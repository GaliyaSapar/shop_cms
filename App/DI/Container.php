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

    /**
     * @var array
     */
    private $interfaces_dictionary = [];

    public function __construct(array $interfaces_dictionary = [])
    {
        $this->injector = new Injector($this);
        $this->singletones[self::class] = $this;
        $this->interfaces_dictionary = $interfaces_dictionary;
    }

    public function get($key) {
        if (!class_exists($key)) {
            if (interface_exists($key)) {
                $interface_mapping = $this->getInterfaceMapping($key);
                return $this->get($interface_mapping);
            }
            throw new \Exception('class not exist: ' . $key);
        }
        return $this->getClass($key);
    }

    private function  getInterfaceMapping(string $key): ?string {
        return $this->interfaces_dictionary[$key] ?? null;
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