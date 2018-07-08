<?php
namespace App;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use IndieAuth;
use Config;

class LoginController {

  public function __construct() {
    \p3k\session_setup(true);
    IndieAuth\Client::$clientID = Config::$base;
    IndieAuth\Client::$redirectURL = Config::$base.'login/callback';
  }

  public function login(ServerRequestInterface $request, ResponseInterface $response) {
    $response->getBody()->write(view('login', [
      'title' => 'Monocle',
    ]));
    return $response;
  }

  public function logout(ServerRequestInterface $request, ResponseInterface $response) {
    unset($_SESSION['token']);
    unset($_SESSION['micropub']);
    unset($_SESSION['microsub']);
    session_destroy();
    return $response->withHeader('Location', '/')->withStatus(302);
  }

  public function login_start(ServerRequestInterface $request, ResponseInterface $response) {
    $params = $request->getParsedBody();

    if(!isset($params['url'])) {
      $_SESSION['auth_error'] = 'invalid url';
      $_SESSION['auth_error_description'] = 'The URL you entered was not valid';
      return $response->withHeader('Location', '/login')->withStatus(302);
    }

    $scope = 'create update read follow channels';
    list($authorizationURL, $error) = IndieAuth\Client::begin($params['url'], $scope);

    if($error) {
      $_SESSION['auth_error'] = $error['error'];
      $_SESSION['auth_error_description'] = $error['error_description'];
      return $response->withHeader('Location', '/login')->withStatus(302);
    }

    return $response->withHeader('Location', $authorizationURL)->withStatus(302);
  }

  public function login_callback(ServerRequestInterface $request, ResponseInterface $response) {
    $params = $request->getQueryParams();

    list($token, $error) = IndieAuth\Client::complete($params);

    if($error) {
      $_SESSION['auth_error'] = $error['error'];
      $_SESSION['auth_error_description'] = $error['error_description'];
      return $response->withHeader('Location', '/login')->withStatus(302);
    }

    $micropub = IndieAuth\Client::discoverMicropubEndpoint($token['me']);
    $microsub = IndieAuth\Client::discoverMicrosubEndpoint($token['me']);

    if(!$microsub) {
      $_SESSION['auth_error'] = 'missing_endpoint';
      $_SESSION['auth_error_description'] = 'We didn\'t find a Microsub endpoint at your website';
      return $response->withHeader('Location', '/login')->withStatus(302);
    }

    $_SESSION['token'] = $token;
    $_SESSION['microsub'] = $microsub;

    // Fetch Micropub config
    if($micropub) {
      $config = get_micropub_config($micropub, $token);
      $_SESSION['micropub'] = $config;
    }

    return $response->withHeader('Location', '/')->withStatus(302);
  }

}
