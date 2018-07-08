<?php
namespace App;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use IndieAuth;
use Config;

class Controller {

  private function requireLogin() {
    \p3k\session_setup();
    if(!isset($_SESSION['token'])) {
      header('Location: /');
      die();
    }
  }

  private function _reloadChannels() {
    $r = microsub_get($_SESSION['microsub'], $_SESSION['token']['access_token'], 'channels');
    if($r && $r['code'] == 200) {
      $channels = json_decode($r['body'], true);
      $_SESSION['channels'] = $channels['channels'];
      $_SESSION['channels_timestamp'] = time();
    }
    return $r;
  }

  public function index(ServerRequestInterface $request, ResponseInterface $response) {
    \p3k\session_setup();

    if(isset($_SESSION['token'])) {
      // Logged-in home page

      if(!isset($_SESSION['channels'])) {
        $r = $this->_reloadChannels();
        if(!$r || $r['code'] != 200) {
          ?>
          <h2>Error</h2>
          <p>There was a problem trying to load the channels from your Microsub endpoint.</p>
          <ul>
            <li>Microsub endpoint: <code><?= htmlspecialchars($_SESSION['microsub']) ?></code></li>
            <li>Your website: <code><?= htmlspecialchars($_SESSION['token']['me']) ?></code></li>
          </ul>
          <p>The endpoint returned the following response.</p>
          <pre>
          <?php
          ob_start();
          print_r($r);
          $debug = ob_get_clean();
          echo htmlspecialchars($debug);
          ?>
          </pre>
          <?php
          die();
        }
      }

      $response->getBody()->write(view('main', [
        'title' => 'Monocle',
      ]));
    } else {
      // Logged out. Check if the hostname corresponds to any of the public hosted channels

      if(isset(Config::$public[$_SERVER['SERVER_NAME']])) {
        $public = Config::$public[$_SERVER['SERVER_NAME']];

        $params = $request->getQueryParams();

        $q = ['channel'=>$public['microsub']['channel']];
        if(isset($params['after']))
          $q['after'] = $params['after'];

        $cacheKey = md5($public['microsub']['endpoint'].'::'.http_build_query($q));
        $cacheFile = 'cache/'.$cacheKey.'.json';
        if(file_exists($cacheFile) && filemtime($cacheFile) >= time() - 300) {
          $data = json_decode(file_get_contents($cacheFile), true);
        } else {
          $data = microsub_get($public['microsub']['endpoint'], $public['microsub']['access_token'], 'timeline', $q);
          $data = json_decode($data['body'], true);
          file_put_contents($cacheFile, json_encode($data));
        }

        $entries = $data['items'] ?? [];
        $paging = $data['paging'] ?? [];

        $response->getBody()->write(view('public_timeline', [
          'title' => $public['title'],
          'channel' => [],
          'entries' => $entries,
          'paging' => $paging,
          'responses_enabled' => false
        ]));

      } else {
        $response->getBody()->write(view('index', [
          'title' => 'Monocle',
        ]));
      }
    }
    return $response;
  }

  public function debug(ServerRequestInterface $request, ResponseInterface $response) {
    \p3k\session_setup();

    $response->getBody()->write(view('debug', [
      'session' => $_SESSION,
      'title' => 'Monocle Debug'
    ]));
    return $response;
  }

  public function micropub(ServerRequestInterface $request, ResponseInterface $response) {
    $this->requireLogin();
    $body = $request->getParsedBody();

    $params = array_merge(['h' => 'entry'], $body);

    $r = micropub_post_form($_SESSION['micropub']['endpoint'], $_SESSION['token']['access_token'], $params);

    $location = false;
    if(isset($r['headers']['Location'])) {
      $location = $r['headers']['Location'];
    }

    // $location = 'http://example.com/foo';

    $response->getBody()->write(json_encode([
      'location' => $location,
      'response' => $r
    ]));
    return $response->withHeader('Content-type', 'application/json');
  }

  public function mark_as_read(ServerRequestInterface $request, ResponseInterface $response) {
    $this->requireLogin();
    $body = $request->getParsedBody();

    microsub_post($_SESSION['microsub'], $_SESSION['token']['access_token'], 'timeline', [
      'channel' => $body['channel'],
      'method' => 'mark_read',
      'entry' => $body['entry'],
    ]);

    $r = $this->_reloadChannels();

    $response->getBody()->write(json_encode([
      'channels' => $_SESSION['channels']
    ]));
    return $response->withHeader('Content-type', 'application/json');
  }

  public function remove_entry(ServerRequestInterface $request, ResponseInterface $response) {
    $this->requireLogin();
    $body = $request->getParsedBody();

    $result = microsub_post($_SESSION['microsub'], $_SESSION['token']['access_token'], 'timeline', [
      'channel' => $body['channel'],
      'method' => 'remove',
      'entry' => $body['entry'],
    ]);

    $response->getBody()->write(json_encode([
      'entry' => $body['entry'],
      'result' => $result
    ]));
    return $response->withHeader('Content-type', 'application/json');
  }

  public function reload_channels(ServerRequestInterface $request, ResponseInterface $response) {
    $this->requireLogin();
    $params = $request->getQueryParams();

    $r = $this->_reloadChannels();

    if(isset($params['format']) && $params['format'] == 'json') {
      $response->getBody()->write(json_encode([
        'channels' => $_SESSION['channels']
      ]));
      return $response->withHeader('Content-type', 'application/json');
    } else {
      $response->getBody()->write(view('components/channel-list'));
      return $response;
    }
  }

  public function micropub_refresh(ServerRequestInterface $request, ResponseInterface $response) {
    $this->requireLogin();

    $config = get_micropub_config($_SESSION['micropub']['endpoint'], $_SESSION['token']);
    $_SESSION['micropub'] = $config;

    $response->getBody()->write(json_encode([
      'micropub' => $_SESSION['micropub']
    ]));
    return $response->withHeader('Content-type', 'application/json');
  }

  public function timeline(ServerRequestInterface $request, ResponseInterface $response) {
    $this->requireLogin();

    $params = $request->getQueryParams();

    if(preg_match('/\/channel\/(.+)/', $request->getUri()->getPath(), $match)) {
      $uid = $match[1];
      $uid = urldecode($uid);

      $channel = false;
      foreach($_SESSION['channels'] as $ch) {
        if($ch['uid'] == $uid) {
          $channel = $ch;
          break;
        }
      }

      if(!$channel) {
        $response->getBody()->write('The channel "'.e($uid).'" was not found.');
        return $response->withStatus(404);
      }

      $q = ['channel'=>$uid];
      if(isset($params['after']))
        $q['after'] = $params['after'];

      $data = microsub_get($_SESSION['microsub'], $_SESSION['token']['access_token'], 'timeline', $q);
      $data = json_decode($data['body'], true);

      $entries = $data['items'] ?? [];
      $paging = $data['paging'] ?? [];

      $destination = false;
      $responses_enabled = false;

      if(isset($_SESSION['micropub']['config']['destination'])) {
        foreach($_SESSION['micropub']['config']['destination'] as $dest) {
          if(
            (!isset($channel['destination']) && $dest['uid'] == '')
            || (isset($channel['destination']) && $dest['uid'] == $channel['destination'])
          ) {
            $destination = $dest;
            $responses_enabled = true;
          }
        }
      } else {
        // Enable responses if no destinations are configured or channel destination is not "none"
        $responses_enabled = !isset($channel['destination']) || $channel['destination'] != 'none';
      }

      $response->getBody()->write(view('timeline', [
        'title' => 'Monocle',
        'channel' => $channel,
        'entries' => $entries,
        'paging' => $paging,
        'destination' => $destination,
        'responses_enabled' => $responses_enabled
      ]));
    }

    return $response;
  }
}
