<?php
namespace App;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use IndieAuth;
use Config;

class Controller {

  public function index(ServerRequestInterface $request, ResponseInterface $response) {
    \p3k\session_setup();

    if(isset($_SESSION['token'])) {

      if(!isset($_SESSION['channels'])) {
        $r = microsub_get($_SESSION['microsub'], $_SESSION['token']['access_token'], 'channels');
        if($r && $r['code'] == 200) {
          $channels = json_decode($r['body'], true);
          $_SESSION['channels'] = $channels['channels'];
        } else {
          echo '<pre>';
          print_r($_SESSION['token']);
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

  private function requireLogin() {
    \p3k\session_setup();
    if(!isset($_SESSION['token'])) {
      header('Location: /');
      die();
    }
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

      $data = microsub_get($_SESSION['microsub'], $_SESSION['token']['access_token'], 'timeline', ['channel'=>$uid]);
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
