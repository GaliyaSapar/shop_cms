<?php

namespace App\Controller;

class Main
{
    private function __construct()
    {
    }

    public static function index() {
        ProductController::list();
    }

}