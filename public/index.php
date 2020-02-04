<?php
//Front Controller
//Show errors
ini_set('display_errors', 1);
ini_set('display_starup_error', 1);
error_reporting(E_ALL);
//Load de autoload with all the classes
require_once '../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule; //Eloquent
//Router
use Aura\Router\RouterContainer;
// Libraries needs for middleware
use Laminas\Diactoros\Response;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabs\Harmony\Middleware\LaminasEmitterMiddleware;

//Initialize sessions
session_start();
//Instance the dotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
//Create the container for dependency injection
$container = new DI\Container();

//Create a capsule for ELOQUENT
$capsule = new Capsule;

//Set up database connection stuffs
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => getenv('DB_HOST'),
    'database' => getenv('DB_NAME'),
    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASS'),
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();
// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

//Object request from Zend Diactoros
$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
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
$map->get('index', '/', [
    'App\Controllers\IndexController',
    'indexAction'
]);

$map->get('Jobs', '/jobs', [
    'App\Controllers\JobsController',
    'indexAction'
]);

$map->get('JobDelete', '/jobs/delete', [
    'App\Controllers\JobsController',
    'deleteJob'
]);

$map->get('addJob', '/add/job', [
    'App\Controllers\JobsController',
    'getAddJobAction'
]);

$map->post('saveJob', '/add/job', [
    'App\Controllers\JobsController',
    'getAddJobAction'
]);

$map->get('createUser', '/user/create', [
    'App\Controllers\UserController',
    'create'
]);

$map->post('saveUser', '/user/store', [
    'App\Controllers\UserController',
    'store'
]);

$map->get('login', '/auth', [
    'App\Controllers\AuthController',
    'getLogin'
]);

$map->post('auth', '/auth/login', [
    'App\Controllers\AuthController',
    'postLogin'
]);

$map->get('admin', '/admin', [
    'App\Controllers\AuthController',
    'getDashboard'
]);

$map->get('logout', '/logout', [
    'App\Controllers\AuthController',
    'logout'
]);

//Get the matcher from aura
$matcher = $routerContainer->getMatcher();
//Search the route and file
$route = $matcher->match($request);

//Verify if the route exists
if (!$route) {
    echo "Route undefined";
} else {

    //Implementation of harmony middleware, dispatcher
    $harmony = new Harmony($request, new Response());
    $harmony
        ->addMiddleware(new LaminasEmitterMiddleware(new SapiEmitter()))
        ->addMiddleware(new Middlewares\AuraRouter($routerContainer))
        ->addMiddleware(new \App\Middlewares\AuthMiddleware())
        ->addMiddleware(new DispatcherMiddleware($container, 'request-handler'))
        ->run();
}