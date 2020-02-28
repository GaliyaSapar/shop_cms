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

    public function addSingletone(string $class_name, callable $callback) {
        $this->factories[$class_name] = $callback;
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
        if (isset($this->singletones[$class_name])) {

            if ($this->singletones[$class_name] == false) {
                $this->singletones[$class_name] = $this->factories[$class_name]();
            }

            return $this->singletones[$class_name];
        } else {
            return $this->injector->createClass($class_name);
        }
    }
}