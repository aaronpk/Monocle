<?php
chdir('..');
include('vendor/autoload.php');

$route = new Monocle\Router();

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

$route->map('GET', '/widget/{uid}', 'App\\Controller::widget');

$route->map('GET', '/{path}', 'App\\Controller::path');


$templates = new Monocle\View(dirname(__FILE__).'/../views');

$response = new Monocle\Http\Response();

try {
  $route->dispatch(Monocle\Http\Request::fromGlobals(), $response)->emit();
} catch(Monocle\RouteNotFound $e) {
  $response->getBody()->write("Not Found\n");
  $response->withStatus(404)->emit();
} catch(Monocle\MethodNotAllowed $e) {
  $response->getBody()->write("Method not allowed\n");
  $response->withStatus(405)->emit();
}

