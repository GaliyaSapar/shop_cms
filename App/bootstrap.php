<?php

use App\Config;
use App\Db\MySQL;
use App\DI\Container;
use App\Http\Request;
use App\Http\Response;
use App\Kernel;
use App\Model\Cart;
use App\Model\User;
use App\MySQL\ArrayDataManager;
use App\MySQL\Connection;
use App\MySQL\Interfaces\IArrayDataManager;
use App\MySQL\Interfaces\IConnection;
use App\MySQL\Interfaces\IObjectDataManager;
use App\MySQL\ObjectDataManager;
use App\Service\CartService;
use App\Service\UserService;
use Composer\Autoload\ClassLoader;


define('APP_DIR', __DIR__ . '/../');

require_once APP_DIR . '/vendor/autoload.php';


session_start();

$container = new Container([
    IConnection::class => Connection::class,
    IArrayDataManager::class => ArrayDataManager::class,
    IObjectDataManager::class => ObjectDataManager::class,
]);

$container->addSingletone(Response::class);
$container->addSingletone(Request::class);
$container->addSingletone(CartService::class);
$container->addSingletone(UserService::class);

//$container->addSingletone(Response::class, function () {
//    return new Response();
//});
//
//$container->addSingletone(Request::class, function () {
//    return new Request();
//});
$container->addSingletone(Connection::class, function () use ($container) {
    $config = $container->get(Config::class);
    $host = $config->get('db.host');
    $user_name = $config->get('db.user');
    $user_pwd = $config->get('db.password');
    $db_name = $config->get('db.db_name');

    return new Connection($host, $db_name, $user_name, $user_pwd);
});

$container->addSingletone(ArrayDataManager::class);
$container->addSingletone(ObjectDataManager::class);

//$container->addSingletone(ArrayDataManager::class, function() use ($container) {
//    $connection = $container->get(Connection::class);
//
//    return new ArrayDataManager($connection);
//});

//$container->addSingletone(ObjectDataManager::class, function() use ($container) {
//    $connection = $container->get(Connection::class);
//    $array_data_manager = $container->get(ArrayDataManager::class);
//    return new ObjectDataManager($connection, $array_data_manager);
//});

//$container->addSingletone(MySQL::class, function () use ($container) {
//    $config = $container->get(Config::class);
//
//    $host = $config->get('db.host');
//    $user_name = $config->get('db.user');
//    $user_pwd = $config->get('db.password');
//    $db_name = $config->get('db.db_name');
//
//    return new MySQL($host, $user_name, $user_pwd, $db_name);
//});

$container->addSingletone(Config::class, function () {
    $config_path = APP_DIR . '/config/config.php';
    $default_configs_path = APP_DIR . '/config.d';

    return new Config($config_path, $default_configs_path);
    
   
});

$container->addSingletone(Smarty::class, function() use ($container) {
    $config = $container->get(Config::class);
    $smarty = new Smarty();

    $smarty->template_dir = $config->get('template.template_dir');
    $smarty->compile_dir = $config->get('template.compile_dir');
    $smarty->cache_dir = $config->get('template.cache_dir');

    $user = new User();
    $cart = new Cart();

    $smarty->assign_by_ref('user', $user);
    $smarty->assign_by_ref('cart', $cart);

    return $smarty;
});

$kernel = $container->get(Kernel::class);