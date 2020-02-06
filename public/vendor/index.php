<?php

use App\Service\VendorService;

require_once $_SERVER['DOCUMENT_ROOT'] . '/../App/bootstrap.php';

$vendors = VendorService::getList('id');

smarty()->assign_by_ref('vendors', $vendors);
smarty()->display('vendor/index.tpl');

