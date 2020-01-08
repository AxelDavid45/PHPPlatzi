<?php
//Front Controller
//Show errors
ini_set('display_errors', 1);
ini_set('display_starup_error', 1);
error_reporting(E_ALL);

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
$baseRoute = '/intro-php-platzi/portfolio';
//Create a new route
$map->get('index', $baseRoute.'/', [
    'controller' => 'App\Controllers\IndexController',
    'method' => 'indexAction'
]);

$map->get('addJob', $baseRoute.'/add/job', [
    'controller' => 'App\Controllers\JobsController',
    'method' => 'getAddJobAction'
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
    $controller->$method();
}

//Temporally  code
function printElement($job) {
    // if($job->visible == false) {
    //   return;
    // }

    echo '<li class="work-position">';
    echo '<h5>' . $job->title . '</h5>';
    echo '<p>' . $job->description . '</p>';
    echo '<p>' . $job->getDurationAsString() . '</p>';
    echo '<strong>Achievements:</strong>';
    echo '<ul>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
    echo '</ul>';
    echo '</li>';
}


