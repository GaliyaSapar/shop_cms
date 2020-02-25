<?php

use \App\Controller\UserController;

require_once $_SERVER['DOCUMENT_ROOT'] . '/../App/bootstrap.php';

UserController::logout();