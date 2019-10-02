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

function j($json) {
  return htmlspecialchars(json_encode($json, JSON_PRETTY_PRINT+JSON_UNESCAPED_SLASHES));
}

function is_logged_in() {
  return isset($_SESSION) && array_key_exists('me', $_SESSION);
}

function display_date($format, $date) {
  try {
    $d = new DateTime($date);
    return $d->format($format);
  } catch(Exception $e) {
    return false;
  }
}

function login_required(&$response) {
  return $response->withHeader('Location', '/?login_required')->withStatus(302);
}

function http_client() {
  static $http;
  if(!isset($http))
    $http = new \p3k\HTTP(Config::$useragent);
  $http->set_timeout(20);
  return $http;
}

function get_micropub_config($endpoint, $token) {
  $r = micropub_get($endpoint, $token['access_token'], ['q'=>'config']);
  $config = [];
  if($r['code'] == 200) {
    $c = json_decode($r['body'], true);
    if($c) {
      $config = $c;
    }
  }

  $_SESSION['micropub'] = [
    'endpoint' => $endpoint,
    'config' => $config
  ];
  return $_SESSION['micropub'];
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

function micropub_post_form($endpoint, $token, $params=[]) {
  $headers = [
    'Accept: application/json',
    'Authorization: Bearer '.$token,
  ];

  $params = p3k\http_build_query($params);

  return http_client()->post($endpoint, $params, $headers);
}

function microsub_get($endpoint, $token, $action, $params=[]) {
  $headers = [
    'Accept: application/json',
    'Authorization: Bearer '.$token,
  ];

  $params['action'] = $action;

  return http_client()->get(\p3k\url\add_query_params_to_url($endpoint, $params), $headers);
}

function microsub_post($endpoint, $token, $action, $params=[]) {
  $headers = [
    'Accept: application/json',
    'Authorization: Bearer '.$token,
  ];

  $params['action'] = $action;

  $params = p3k\http_build_query($params);

  return http_client()->post($endpoint, $params, $headers);
}

function supports_post_type($type) {
  if(!isset($_SESSION['micropub']['config']['post-types']))
    return true;

  $types = $_SESSION['micropub']['config']['post-types'];

  foreach($types as $t) {
    if(is_array($t) && isset($t['type']) && $t['type'] == $type) {
      return true;
    }
  }

  return false;
}

function image_placeholder(&$entry, $photo) {
  if(isset($entry['refs'][$photo])) {
    $p = $entry['refs'][$photo];

    if(isset($p['ratio'])) {
      $im = imagecreate(1000*$p['ratio'], 1000);
      $color = imagecolorallocate($im, 80, 80, 80);
      imagefill($im, 0,0, $color);
      ob_start();
      imagepng($im);
      $data = base64_encode(ob_get_clean());
      return 'data:image/png;base64,'.$data;
    }

  }

  return '';
}

function fa($name, $collection='solid') {
  return '<svg class="svg-icon"><use xlink:href="/assets/font-awesome-5.11.2/'.$collection.'.svg#'.$name.'"></use></svg>';
}

function image_proxy($url, $opts='1240x0') {
  if(!Config::$imageProxyURL)
    return $url;

  $urlToSign = $url.'#'.$opts;
  $sig = strtr(base64_encode(hash_hmac('sha256', $urlToSign, Config::$imageProxyKey, 1)), '/+' , '_-');
  return Config::$imageProxyURL.$opts.',s'.$sig.'/'.$url;
}
