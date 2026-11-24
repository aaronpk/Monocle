<?php
// Router script for `php -S 127.0.0.1:18080 tests/fixtures/server.php`.
// Stands in for everything Monocle talks to over HTTP: a Microsub endpoint,
// a Micropub endpoint, an IndieAuth profile with authorization and token
// endpoints, and XRay. Every request is appended to $FIXTURE_LOG as JSON so
// tests can check what Monocle sent.

$dir = __DIR__;
$base = 'http://127.0.0.1:18080';
$tls = 'https://127.0.0.1:18443';
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$body = file_get_contents('php://input');

if($log = getenv('FIXTURE_LOG')) {
  $headers = [];
  foreach(['HTTP_AUTHORIZATION' => 'Authorization', 'CONTENT_TYPE' => 'Content-Type', 'HTTP_ACCEPT' => 'Accept'] as $k => $name) {
    if(isset($_SERVER[$k])) $headers[$name] = $_SERVER[$k];
  }
  file_put_contents($log, json_encode([
    'method' => $method,
    'uri' => $_SERVER['REQUEST_URI'],
  ] + (getenv('FIXTURE_PRIVATE') ? ['server' => '127.0.0.2'] : []) + [
    'headers' => $headers,
    'body' => $body,
  ], JSON_UNESCAPED_SLASHES)."\n", FILE_APPEND);
}

function json($data, $code=200, $headers=[]) {
  http_response_code($code);
  header('Content-Type: application/json');
  foreach($headers as $h) header($h);
  echo is_string($data) ? $data : json_encode($data, JSON_UNESCAPED_SLASHES);
  exit;
}

function fixture($name) {
  return json_decode(file_get_contents(__DIR__.'/'.$name.'.json'), true);
}

switch($path) {

  case '/microsub':
    if($method == 'GET') {
      switch($_GET['action'] ?? '') {
        case 'channels':
          json(fixture('channels'));
        case 'timeline':
          $channel = $_GET['channel'] ?? '';
          if(!preg_match('/^[a-z]+$/', $channel) || !file_exists("$dir/timeline-$channel.json"))
            json(['error' => 'not_found'], 404);
          $data = fixture('timeline-'.$channel);
          if(isset($_GET['after'])) {
            $data = file_exists("$dir/timeline-$channel-page2.json") ? fixture("timeline-$channel-page2") : ['items' => [], 'paging' => new stdClass];
          }
          if(isset($_GET['source'])) {
            $data['items'] = array_values(array_filter($data['items'], function($i){ return ($i['_source'] ?? '') == $_GET['source']; }));
            $data['source'] = ['name' => 'Source '.$_GET['source']];
          }
          if(($_GET['is_read'] ?? '') == 'false') {
            $data['items'] = array_values(array_filter($data['items'], function($i){ return empty($i['_is_read']); }));
          }
          json($data);
      }
      json(['error' => 'bad_request'], 400);
    }
    parse_str($body, $params);
    if(($params['action'] ?? '') == 'timeline')
      json(['result' => 'ok', 'updated' => is_array($params['entry'] ?? null) ? count($params['entry']) : 1]);
    json(['error' => 'bad_request'], 400);

  case '/micropub':
    if($method == 'GET' && ($_GET['q'] ?? '') == 'config')
      json(fixture('micropub-config'));
    if($method == 'POST')
      json(['created' => true], 201, ["Location: $base/posts/1"]);
    json(['error' => 'invalid_request'], 400);

  case '/me/':
    header('Content-Type: text/html');
    echo "<!doctype html><html><head>\n";
    echo "<link rel=\"authorization_endpoint\" href=\"$base/auth\">\n";
    echo "<link rel=\"token_endpoint\" href=\"$base/token\">\n";
    echo "<link rel=\"micropub\" href=\"$base/micropub\">\n";
    echo "<link rel=\"microsub\" href=\"$base/microsub\">\n";
    echo "</head><body>Fixture user</body></html>\n";
    exit;

  // A profile that only advertises IndieAuth server metadata: no
  // authorization_endpoint or token_endpoint link, so signing in works only
  // if the client reads them from the metadata document.
  case '/me-metadata/':
    header('Content-Type: text/html');
    echo "<!doctype html><html><head>\n";
    echo "<link rel=\"indieauth-metadata\" href=\"$tls/metadata\">\n";
    echo "<link rel=\"micropub\" href=\"$base/micropub\">\n";
    echo "<link rel=\"microsub\" href=\"$base/microsub\">\n";
    echo "</head><body>Fixture user with server metadata</body></html>\n";
    exit;

  // The issuer has to be https, so this is fetched through tests/fixtures/tls-proxy.py
  case '/metadata':
    json([
      'issuer' => "$tls/",
      'authorization_endpoint' => "$base/auth",
      'token_endpoint' => "$base/token",
      'code_challenge_methods_supported' => ['S256'],
    ]);

  case '/token':
    parse_str($body, $params);
    $users = ['fixture-code' => "$base/me/", 'fixture-code-metadata' => "$base/me-metadata/"];
    if(!isset($users[$params['code'] ?? '']))
      json(['error' => 'invalid_grant'], 400);
    json([
      'access_token' => 'fixture-access-token',
      'token_type' => 'Bearer',
      'scope' => 'create update read follow channels profile',
      'me' => $users[$params['code']],
    ]);

  // Redirects to the copy of this server on 127.0.0.2, which Monocle must not follow
  case '/redirect-private':
    header('Location: http://127.0.0.2:18080/microsub?'.$_SERVER['QUERY_STRING'], true, 302);
    exit;

  case '/xray/parse':
    $url = $_GET['url'] ?? '';
    if(strpos($url, 'notfound') !== false)
      json(['data' => ['type' => 'feed', 'items' => []], 'url' => $url, 'code' => 404]);
    if(strpos($url, 'broken') !== false)
      json(['error' => 'timeout', 'error_description' => 'The request timed out', 'url' => $url, 'code' => 0]);
    json(['data' => fixture('xray-feed'), 'url' => $url, 'code' => 200]);

  case '/img/photo.png':
    header('Content-Type: image/png');
    echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
    exit;
}

http_response_code(404);
echo "fixture: not found\n";
