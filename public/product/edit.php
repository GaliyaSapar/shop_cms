<?php

use App\Controller\ProductController;

require_once $_SERVER['DOCUMENT_ROOT'] . '/../App/bootstrap.php';

$product_id = (int) $_GET['product_id'] ?? 0;

ProductController::edit($product_id);

