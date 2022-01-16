<?php

ini_set('display_errors', 1);
ini_set('display_starup_error', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

use App\Middlewares\AuthenticationMiddleware;
use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;
use WoohooLabs\Harmony\Harmony;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Laminas\Diactoros\Response;
use WoohooLabs\Harmony\Middleware\LaminasEmitterMiddleware;
use \Franzl\Middleware\Whoops\WhoopsMiddleware;

$container = new DI\Container();

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

$log = new Logger('app');
$log->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::WARNING));

$request = Laminas\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);
$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();
$map->get('index', '/', [
    'App\Controllers\IndexController',
    'indexAction'
]);
$map->get('contact', '/contact', [
    'App\Controllers\ContactController',
    'indexAction'
]);
$map->post('sendContact', '/contact/send', [
    'App\Controllers\ContactController',
    'sendAction'
]);
$map->get('indexJobs', '/admin/jobs', [
    'App\Controllers\JobsController',
    'indexAction'
]);
$map->get('deleteJobs', '/admin/jobs/{id}/delete', [
    'App\Controllers\JobsController',
    'deleteAction'
]);
$map->get('addJobs', '/admin/jobs/add', [
    'App\Controllers\JobsController',
    'getAddJobAction'
]);
$map->post('saveJobs', '/admin/jobs/add', [
    \App\Controllers\JobsController::class,
    'getAddJobAction'
]);
$map->get('addUser', '/admin/users/add', [
    'App\Controllers\UsersController',
    'getAddUser'
]);
$map->post('saveUser', '/admin/users/save', [
    'App\Controllers\UsersController',
    'postSaveUser'
]);
$map->get('loginForm', '/login', [
    'App\Controllers\AuthController',
    'getLogin'
]);
$map->get('logout', '/logout', [
    'App\Controllers\AuthController',
    'getLogout'
]);
$map->post('auth', '/auth', [
    'App\Controllers\AuthController',
    'postLogin'
]);
$map->get('admin', '/admin', [
    'App\Controllers\AdminController',
    'getIndex'
]);
$map->get('admin.profile.changePassword', '/admin/profile/changePassword', [
    'App\Controllers\ProfileController',
    'changePassword'
]);
$map->post('admin.profile.savePassword', '/admin/profile/savePassword', [
    'App\Controllers\ProfileController',
    'savePassword'
]);


$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

try{
    $harmony = new Harmony($request, new Response());
    //->addMiddleware(new FastRouteMiddleware($router))
    if ($_SERVER['DEBUG'] === "true") {
      $harmony->addMiddleware(new WhoopsMiddleware());
    }
    $harmony
      ->addMiddleware(new LaminasEmitterMiddleware(new SapiEmitter()))
      ->addMiddleware(new Middlewares\AuraRouter($routerContainer))
      ->addMiddleware(new DispatcherMiddleware($container, 'request-handler'))
      ->addMiddleware(new AuthenticationMiddleware())
      ->run();
} catch (Exception $e) {
    $log->error($e->getMessage());
    $emitter = new SapiEmitter();
    $emitter->emit(new Response\EmptyResponse(500));
} catch (Error $e) {
    $log->error($e->getMessage());
    $emitter = new SapiEmitter();
    $emitter->emit(new Response\EmptyResponse(500));
}

