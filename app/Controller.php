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

      if(!isset($_SESSION['channels'])) {
        $r = $this->_reloadChannels();
        if(!$r || $r['code'] != 200) {
          echo '<pre>';
          print_r($r);
          echo '</pre>';
          die();
        }
      }

      $response->getBody()->write(view('main', [
        'title' => 'Monocle',
      ]));
    } else {
      $response->getBody()->write(view('index', [
        'title' => 'Monocle',
      ]));
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

    $r = micropub_post($_SESSION['micropub']['endpoint'], $_SESSION['token']['access_token'], [
      'type' => ['h-entry'],
      'properties' => $body
    ]);

    $location = false;
    if(isset($r['headers']['Location'])) {
      $location = $r['headers']['Location'];
    }

    // $location = 'http://example.com/foo';

    $response->getBody()->write(json_encode([
      'location' => $location
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

      $channel = false;
      foreach($_SESSION['channels'] as $ch) {
        if($ch['uid'] == $uid) {
          $channel = $ch;
          break;
        }
      }

      if(!$channel) {
        $response->getBody()->write(view('not_found'));
        return $response->withStatus(404);
      }

      $q = ['channel'=>$uid];
      if(isset($params['after']))
        $q['after'] = $params['after'];

      $data = microsub_get($_SESSION['microsub'], $_SESSION['token']['access_token'], 'timeline', $q);
      $data = json_decode($data['body'], true);

      $entries = $data['items'] ?? [];
      $paging = $data['paging'] ?? [];

      $response->getBody()->write(view('timeline', [
        'title' => 'Monocle',
        'channel' => $channel,
        'entries' => $entries,
        'paging' => $paging,
      ]));
    }

    return $response;
  }
}
