<?php
chdir('..');
include('vendor/autoload.php');

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

$container = new League\Container\Container;
$container->share('response', Zend\Diactoros\Response::class);
$container->share('request', function () {
  return Zend\Diactoros\ServerRequestFactory::fromGlobals(
      $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
  );
});
$container->share('emitter', Zend\Diactoros\Response\SapiEmitter::class);

$route = new League\Route\RouteCollection($container);

$route->map('GET', '/', 'App\\Controller::index');
$route->map('GET', '/debug', 'App\\Controller::debug');

$route->map('GET', '/login', 'App\\LoginController::login');
$route->map('POST', '/login', 'App\\LoginController::login_start');
$route->map('GET', '/login/callback', 'App\\LoginController::login_callback');
$route->map('GET', '/logout', 'App\\LoginController::logout');

$route->map('GET', '/channel/{uid}', 'App\\Controller::timeline');
$route->map('GET', '/channel/{uid}/{source}', 'App\\Controller::timeline');
$route->map('POST', '/channels/reload', 'App\\Controller::reload_channels');
$route->map('POST', '/microsub/mark_read', 'App\\Controller::mark_as_read');
$route->map('POST', '/microsub/mark_all_as_read', 'App\\Controller::mark_all_as_read');
$route->map('POST', '/microsub/mark_unread', 'App\\Controller::mark_as_unread');
$route->map('POST', '/microsub/remove', 'App\\Controller::remove_entry');

$route->map('POST', '/micropub', 'App\\Controller::micropub');
$route->map('POST', '/micropub/refresh', 'App\\Controller::micropub_refresh');

$route->map('GET', '/preview', 'App\\PreviewController::get');
$route->map('POST', '/preview', 'App\\PreviewController::post');

$route->map('GET', '/{path}', 'App\\Controller::path');


$templates = new League\Plates\Engine(dirname(__FILE__).'/../views');

try {
  $response = $route->dispatch($container->get('request'), $container->get('response'));
  $container->get('emitter')->emit($response);
} catch(League\Route\Http\Exception\NotFoundException $e) {
  $response = $container->get('response');
  $response->getBody()->write("Not Found\n");
  $container->get('emitter')->emit($response->withStatus(404));
} catch(League\Route\Http\Exception\MethodNotAllowedException $e) {
  $response = $container->get('response');
  $response->getBody()->write("Method not allowed\n");
  $container->get('emitter')->emit($response->withStatus(405));
}

