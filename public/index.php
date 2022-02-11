<?php


if( !session_id() ) @session_start();

use Aura\SqlQuery\QueryFactory;
use Delight\Auth;
use League\Plates\Engine;


require '../vendor/autoload.php';


$builder = new DI\ContainerBuilder();
$builder->addDefinitions([
    PDO::class => function(){
    return new PDO('mysql:dbname=components;host=localhost','mysql','');
    },
    QueryFactory::class => function(){
        return new QueryFactory('mysql');
    },
    Auth\Auth::class => DI\create()->constructor(DI\get('PDO')),
    Engine::class => function(){
    return new Engine('../app/view/');
    },

]);
try {
    $container = $builder->build();
} catch (Exception $e) {
    $e->getMessage();
}


$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/ProjectComponents/public/logout', ['App\controllers\AbstractController','logout']);
    
    $r->addRoute('GET', '/ProjectComponents/public/register', ['App\controllers\RegistrationController','register']);
    $r->addRoute('POST', '/ProjectComponents/public/register', ['App\controllers\RegistrationController','registerUser']);
    
    $r->addRoute('GET', '/ProjectComponents/public/', ['App\controllers\HomeController','loginView']);
    $r->addRoute('GET', '/ProjectComponents/public/login', ['App\controllers\HomeController','loginView']);
    $r->addRoute('POST', '/ProjectComponents/public/login', ['App\controllers\HomeController','loginForm']);
    
    
    $r->addRoute('GET', '/ProjectComponents/public/users', ['App\controllers\ViewController','users']);
    $r->addRoute('GET', '/ProjectComponents/public/edit', ['App\controllers\ViewController','viewEdit']);
    $r->addRoute('GET', '/ProjectComponents/public/security', ['App\controllers\ViewController', 'viewSecurity']);
    $r->addRoute('GET', '/ProjectComponents/public/createUser', ['App\controllers\ViewController','createUserView']);
    $r->addRoute('GET', '/ProjectComponents/public/status', ['App\controllers\ViewController', 'statusView']);
    $r->addRoute('GET', '/ProjectComponents/public/media', ['App\controllers\ViewController', 'mediaView']);
    $r->addRoute('GET', '/ProjectComponents/public/profile', ['App\controllers\ViewController', 'profileView']);
    
    
    $r->addRoute('POST', '/ProjectComponents/public/createUser', ['App\controllers\UsersControllers','createUser']);
    $r->addRoute('POST', '/ProjectComponents/public/edit', ['App\controllers\UsersControllers', 'updateUserInfo']);
    $r->addRoute('POST', '/ProjectComponents/public/security', ['App\controllers\UsersControllers', 'editSecurity']);
    $r->addRoute('GET', '/ProjectComponents/public/delete', ['App\controllers\UsersControllers', 'deleteUser']);
    $r->addRoute('POST', '/ProjectComponents/public/status', ['App\controllers\UsersControllers', 'setStatus']);
    $r->addRoute('POST', '/ProjectComponents/public/media', ['App\controllers\UsersControllers', 'updateMedia']);
    
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo 404;
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo 405;
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $container->call($routeInfo[1]);
        break;
}

