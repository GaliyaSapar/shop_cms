<?php

use App\Db\MySQL;

define('APP_DIR', __DIR__ . '/../');

require_once APP_DIR . '/vendor/autoload.php';

//require_once APP_DIR . '/libs/Smarty/Smarty.class.php';
//require_once APP_DIR . '/App/Db/IModel.php';
//require_once APP_DIR . '/App/Db/MySQL.php';
//
//require_once APP_DIR . '/App/Controller/Main.php';
//require_once APP_DIR . '/App/Controller/ProductController.php';
//
//require_once APP_DIR . '/App/Model/Model.php';
//require_once APP_DIR . '/App/Model/Product.php';
//require_once APP_DIR . '/App/Model/Folder.php';
//require_once APP_DIR . '/App/Model/Vendor.php';
//
//require_once APP_DIR . '/App/Service/VendorService.php';
//require_once APP_DIR . '/App/Service/FolderService.php';
//require_once APP_DIR . '/App/Service/ProductService.php';

$config = require_once  APP_DIR . '/config/config.php';

//$smarty = new Smarty();
//
//$smarty->template_dir = $config['template']['template_dir'];
//$smarty->compile_dir = $config['template']['compile_dir'];
//$smarty->cache_dir = $config['template']['cache_dir'];

/**
 * @return MySQL
 */

function db() {
    global $config; // ?
    static $mysql;

    if (is_null($mysql)) {
        $mysql = new MySQL($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['db_name']);
    }

    return $mysql;
}

function smarty() {
    global $config; // ?
    static $smarty;

    if(is_null($smarty)) {
        $smarty = new Smarty(); //?

        $smarty->template_dir = $config['template']['template_dir'];
        $smarty->compile_dir = $config['template']['compile_dir'];
        $smarty->cache_dir = $config['template']['cache_dir'];
    }
    return $smarty;
}