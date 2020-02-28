<?php

use App\Controller\CartController;
use App\Controller\Main;
use App\Controller\ProductController;
use App\Controller\VendorController;

return [
    '/' => [ProductController::class, 'list'],
    '/cart' => [CartController::class, 'view'],
    '/vendor' => [VendorController::class, 'list'],

];