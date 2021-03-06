#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

//Imports
use App\Commands\CreateUserCommand;
use App\Commands\SendEmailCommand;
use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\Console\Application;

//Instance the dotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//Create a capsule for ELOQUENT
$capsule = new Capsule();

//Set up database connection stuffs
$capsule->addConnection(
    [
        'driver' => 'mysql',
        'host' => getenv('DB_HOST'),
        'database' => getenv('DB_NAME'),
        'username' => getenv('DB_USER'),
        'password' => getenv('DB_PASS'),
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ]
);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();
// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

//Init terminal aplication
$application = new Application();
//Command for creating a new user
$application->add(new CreateUserCommand());

//Command for sending an email
$application->add(new SendEmailCommand());

$application->run();