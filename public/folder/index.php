<?php

use App\Service\FolderService;

require_once $_SERVER['DOCUMENT_ROOT'] . '/../App/bootstrap.php';

$folders = FolderService::getList('id');

smarty()->assign_by_ref('folders', $folders);
smarty()->display('folder/index.tpl');
