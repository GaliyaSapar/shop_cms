<?php

use App\Controller\VendorController;

require_once $_SERVER['DOCUMENT_ROOT'] . '/../App/bootstrap.php';

//echo '<pre>'; var_dump($_SERVER['DOCUMENT_ROOT']); echo '</pre>';

VendorController::edit();
