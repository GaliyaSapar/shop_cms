<?php

use App\Config;
use App\Db\MySQL;
use App\DI\Container;
use App\Http\Request;
use App\Http\Response;
use App\Kernel;
use App\Model\Cart;
use App\Model\User;
use Composer\Autoload\ClassLoader;


define('APP_DIR', __DIR__ . '/../');

require_once APP_DIR . '/vendor/autoload.php';

//$config = require_once  APP_DIR . '/config/config.php';

//$smarty = new Smarty();
//
//$smarty->template_dir = $config['template']['template_dir'];
//$smarty->compile_dir = $config['template']['compile_dir'];
//$smarty->cache_dir = $config['template']['cache_dir'];

session_start();

//$factory = new Factory();
//
//$factory->addSingletone(Smarty::class, function() use ($config) {
//    $smarty = new Smarty();
//
//    $smarty->template_dir = $config['template']['template_dir'];
//    $smarty->compile_dir = $config['template']['compile_dir'];
//    $smarty->cache_dir = $config['template']['cache_dir'];
//
//    return $smarty;
//});

$container = new Container();

$container->addSingletone(Response::class, function () {
    return new Response();
});

$container->addSingletone(Request::class, function () {
    return new Request();
});

$container->addSingletone(MySQL::class, function () use ($container) {
    $config = $container->get(Config::class);
   
    $host = $config->get('db.host');
    $user_name = $config->get('db.user');
    $user_pwd = $config->get('db.password');
    $db_name = $config->get('db.db_name');

    return new MySQL($host, $user_name, $user_pwd, $db_name);
});

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

//$router = new Router($factory);

//function db() {
//    global $config; // ?
//    static $mysql;
//
//    if (is_null($mysql)) {
//        $mysql = new MySQL($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['db_name'], $config['db']['port']);
//    }
//    return $mysql;
//}

//function smarty() {
//    global $config; // ?
//    static $smarty;
//
//    if(is_null($smarty)) {
//        $smarty = new Smarty(); //?
//
//        $smarty->template_dir = $config['template']['template_dir'];
//        $smarty->compile_dir = $config['template']['compile_dir'];
//        $smarty->cache_dir = $config['template']['cache_dir'];
//    }
//    return $smarty;
//}

//function user() {
//    static $user;
//
//    /**
//     * @var $user User
//     */
//    if (is_null($user)) {
//        $user = new User();
//    }
//
//    if (isset($_SESSION['user_id'])) {
//        $user_id = (int) $_SESSION['user_id'];
//        $user = UserService::getUserById($user_id);
//    }
//    return $user;
//}
//
///**
// * @return Cart
// */
//
//function cart() {
//    static $cart;
//    if (is_null($cart)) {
//        $cart = CartService::getCart();
//    }
//    return $cart;
//}

//$user = user();
//$cart = cart();
//
//$smarty = $factory->getInstance(Smarty::class);
//
//$smarty->assign_by_ref('user', $user);
//$smarty->assign_by_ref('cart', $cart);