<?php
namespace App;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use IndieAuth;
use Config;

class PreviewController {

  public function get(ServerRequestInterface $request, ResponseInterface $response) {

    $entries = [];

    $response->getBody()->write(view('preview', [
      'title' => 'Preview',
    ]));

    return $response;
  }

  public function post(ServerRequestInterface $request, ResponseInterface $response) {
    $body = $request->getParsedBody();

    $ch = curl_init(Config::$xray.'parse?'.http_build_query([
      'expect' => 'feed',
      'timeout' => 20,
      'url' => $body['url']
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = json_decode(curl_exec($ch), true);

    if(isset($result['data']['items'])) {
      $entries = $result['data']['items'];

      $response->getBody()->write(view('public_timeline_content', [
        'title' => 'Preview',
        'channel' => [],
        'entries' => $entries,
        'paging' => [],
        'responses_enabled' => false,
        'mode' => 'preview',
      ]));
    } elseif(isset($result['error'])) {
      $response->getBody()->write(view('preview_error', [
        'title' => 'Preview Error',
        'result' => $result
      ]));
    } else {

    }

    return $response;
  }

}
