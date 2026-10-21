<?php
namespace Monocle\Http;

// An immutable response with the PSR-7 methods Monocle uses, and an emit()
// that sends it the same way Zend\Diactoros\Response\SapiEmitter did.
class Response {

  private static $phrases = [
    200 => 'OK', 201 => 'Created', 202 => 'Accepted', 204 => 'No Content',
    301 => 'Moved Permanently', 302 => 'Found', 303 => 'See Other', 304 => 'Not Modified',
    307 => 'Temporary Redirect', 308 => 'Permanent Redirect',
    400 => 'Bad Request', 401 => 'Unauthorized', 403 => 'Forbidden', 404 => 'Not Found',
    405 => 'Method Not Allowed', 410 => 'Gone', 429 => 'Too Many Requests',
    500 => 'Internal Server Error', 502 => 'Bad Gateway', 503 => 'Service Unavailable',
  ];

  private $status = 200;
  private $reasonPhrase = 'OK';
  private $headers = [];
  private $body;

  public function __construct() {
    $this->body = new Body();
  }

  public function getBody() {
    return $this->body;
  }

  public function getStatusCode() {
    return $this->status;
  }

  public function getHeaders() {
    return $this->headers;
  }

  public function withHeader($name, $value) {
    $new = clone $this;
    // Replace any existing header with the same name regardless of case
    foreach(array_keys($new->headers) as $existing) {
      if(strtolower($existing) == strtolower($name))
        unset($new->headers[$existing]);
    }
    $new->headers[$name] = is_array($value) ? array_values($value) : [$value];
    return $new;
  }

  public function withStatus($code, $reasonPhrase='') {
    $new = clone $this;
    $new->status = (int)$code;
    $new->reasonPhrase = $reasonPhrase ?: (self::$phrases[$new->status] ?? '');
    return $new;
  }

  public function emit() {
    foreach($this->headers as $name => $values) {
      $name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $name)));
      $replace = $name !== 'Set-Cookie';
      foreach($values as $value) {
        header($name.': '.$value, $replace, $this->status);
        $replace = false;
      }
    }
    header('HTTP/1.1 '.$this->status.($this->reasonPhrase ? ' '.$this->reasonPhrase : ''), true, $this->status);
    echo $this->body;
  }

}
