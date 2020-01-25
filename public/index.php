<?php
//Front Controller
//Show errors
ini_set('display_errors', 1);
ini_set('display_starup_error', 1);
error_reporting(E_ALL);
define('base_url', '/introphp/portfolio');
//Load de autoload with all the classes
require_once '../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule; //Eloquent
use Aura\Router\RouterContainer; //Router

//Create a capsule for ELOQUENT
$capsule = new Capsule;

//Set up database connection stuffs
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'cursophp',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();
// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

//Object request from Zend Diactoros
$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

//Create route container
$routerContainer = new RouterContainer();

//Create a map of routes
$map = $routerContainer->getMap();

//Create a new route
$map->get('index', base_url.'/', [
    'controller' => 'App\Controllers\IndexController',
    'method' => 'indexAction'
]);

$map->get('addJob', base_url.'/add/job', [
    'controller' => 'App\Controllers\JobsController',
    'method' => 'getAddJobAction'
]);

$map->post('saveJob', base_url.'/add/job', [
    'controller' => 'App\Controllers\JobsController',
    'method' => 'getAddJobAction'
]);

$map->get('createUser', base_url.'/user/create', [
    'controller' => 'App\Controllers\UserController',
    'method' => 'create'
]);

$map->post('saveUser', base_url.'/user/store', [
    'controller' => 'App\Controllers\UserController',
    'method' => 'store'
]);

$map->get('login', base_url.'/auth', [
    'controller' => 'App\Controllers\AuthController',
    'method' => 'getLogin'
]);

$map->post('auth', base_url.'/auth/login', [
    'controller' => 'App\Controllers\AuthController',
    'method' => 'postLogin'
]);

//Get the matcher from aura
$matcher = $routerContainer->getMatcher();
//Search the route and file
$route = $matcher->match($request);

//Verify if the route exists
if(!$route) {
    echo "Route undefined";
} else {
    //Save data from handler
    $handlerData = $route->handler;
    $controllerName = $handlerData['controller'];
    $method = $handlerData['method'];
    //Create a new instance of the controller
    $controller = new $controllerName;
    //Call the method from controller
    $response = $controller->$method($request);

    //Print headers for redirect responses
    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header(sprintf('%s: %s', $name, $value), false);
        }
    }

    http_response_code($response->getStatusCode());
    var_dump($response);

    //Show response html
    echo $response->getBody();
}


