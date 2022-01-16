#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$dotenv = new Dotenv\Dotenv(__DIR__ );
$dotenv->load();

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => $_SERVER['DB_DRIVER'],
    'host'      => $_SERVER['DB_HOST'],
    'database'  => $_SERVER['DB_NAME'],
    'username'  => $_SERVER['DB_USER'],
    'password'  => $_SERVER['DB_PASS'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
    'port'      => $_SERVER['DB_PORT']
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();
// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new \App\Commands\CreateUserCommand());
$application->add(new \App\Commands\SendMailsCommand());

$application->run();