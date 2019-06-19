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

    if(isset($result['code']) && $result['code'] == 200 && isset($result['data']['items'])) {
      $entries = $result['data']['items'];

      $response->getBody()->write(view('public_timeline_content', [
        'title' => 'Preview',
        'channel' => [],
        'entries' => $entries,
        'paging' => [],
        'responses_enabled' => false,
        'mode' => 'preview',
      ]));
    } else {
      // XRay returns empty items on a 404 error
      if(isset($result['code']) && $result['code'] == 404) {
        $result['error'] = 'not_found';
        $result['error_description'] = 'The URL returned 404 Not Found';
      }

      $response->getBody()->write(view('preview_error', [
        'title' => 'Preview Error',
        'result' => $result
      ]));
    }

    return $response;
  }

}
