<?php


if( !session_id() ) @session_start();

use Aura\SqlQuery\QueryFactory;
use Delight\Auth;
use Laracasts\Flash\Flash;
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
    Flash::class => function(){
    return new Laracasts\Flash\Flash();
    }
]);
try {
    $container = $builder->build();
} catch (Exception $e) {
    $e->getMessage();
}


$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/ProjectComponents/public/', ['App\controllers\pageController','loginView']);
    $r->addRoute('GET', '/ProjectComponents/public/login', ['App\controllers\pageController','loginView']);
    $r->addRoute('GET', '/ProjectComponents/public/register', ['App\controllers\pageController','register']);
    $r->addRoute('GET', '/ProjectComponents/public/users', ['App\controllers\pageController','users']);
    $r->addRoute('GET', '/ProjectComponents/public/logout', ['App\controllers\pageController','logout']);
    $r->addRoute('GET', '/ProjectComponents/public/createUser', ['App\controllers\pageController','createUserView']);
    $r->addRoute('GET', '/ProjectComponents/public/edit', ['App\controllers\pageController','viewEdit']);
    $r->addRoute('GET', '/ProjectComponents/public/security', ['App\controllers\pageController', 'viewSecurity']);
    $r->addRoute('GET', '/ProjectComponents/public/delete', ['App\controllers\userController', 'deleteUser']);
    $r->addRoute('GET', '/ProjectComponents/public/status', ['App\controllers\pageController', 'statusView']);
    $r->addRoute('GET', '/ProjectComponents/public/media', ['App\controllers\pageController', 'mediaView']);
    $r->addRoute('GET', '/ProjectComponents/public/profile', ['App\controllers\pageController', 'profileView']);
    
    $r->addRoute('POST', '/ProjectComponents/public/media', ['App\controllers\userController', 'mediaUpdate']);
    $r->addRoute('POST', '/ProjectComponents/public/register', ['App\controllers\pageController','registerUser']);
    $r->addRoute('POST', '/ProjectComponents/public/status', ['App\controllers\userController', 'statusSet']);
    $r->addRoute('POST', '/ProjectComponents/public/login', ['App\controllers\pageController','loginForm']);
    $r->addRoute('POST', '/ProjectComponents/public/createUser', ['App\controllers\userController','createUser']);
    $r->addRoute('POST', '/ProjectComponents/public/edit', ['App\controllers\userController', 'updateUserInfo']);
    $r->addRoute('POST', '/ProjectComponents/public/security', ['App\controllers\userController', 'editSecurity']);
    
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

