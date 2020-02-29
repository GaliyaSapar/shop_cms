<?php


namespace App;


use App\Http\Request;

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

    public function __construct(Request $request, Config $config, Router $router)
    {
        $this->request = $request;
        $this->config = $config;
        $this->router = $router;
    }

    public function run() {

        $this->router->dispatch();
    }
}