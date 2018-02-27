<?php
date_default_timezone_set('UTC');

if(getenv('ENV')) {
  require(dirname(__FILE__).'/config.'.getenv('ENV').'.php');
} else {
  require(dirname(__FILE__).'/config.php');
}

function view($template, $data=[]) {
  global $templates;
  return $templates->render($template, $data);
}

function e($text) {
  return htmlspecialchars($text);
}

function is_logged_in() {
  return isset($_SESSION) && array_key_exists('me', $_SESSION);
}

function display_date($format, $date) {
  $d = new DateTime($date);
  return $d->format($format);
}

function login_required(&$response) {
  return $response->withHeader('Location', '/?login_required')->withStatus(302);
}

function http_client() {
  static $http;
  if(!isset($http))
    $http = new \p3k\HTTP(Config::$useragent);
  return $http;
}

function micropub_get($endpoint, $token, $params=[]) {
  $headers = [
    'Accept: application/json',
    'Authorization: Bearer '.$token,
  ];

  return http_client()->get(\p3k\url\add_query_params_to_url($endpoint, $params), $headers);
}

function micropub_post($endpoint, $token, $params=[]) {
  $headers = [
    'Accept: application/json',
    'Content-type: application/json',
    'Authorization: Bearer '.$token,
  ];

  return http_client()->post($endpoint, json_encode($params), $headers);
}

function microsub_get($endpoint, $token, $action, $params=[]) {
  $headers = [
    'Accept: application/json',
    'Authorization: Bearer '.$token,
  ];

  $params['action'] = $action;

  return http_client()->get(\p3k\url\add_query_params_to_url($endpoint, $params), $headers);
}

function microsub_post($endpoint, $token, $params=[]) {
  $headers = [
    'Accept: application/json',
    'Authorization: Bearer '.$token,
  ];

  return http_client()->post($endpoint, http_build_query($params), $headers);
}

