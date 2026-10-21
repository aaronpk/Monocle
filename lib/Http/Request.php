<?php
namespace Monocle\Http;

// The parts of a PSR-7 server request that Monocle uses, built from the PHP
// globals the same way Zend\Diactoros\ServerRequestFactory::fromGlobals() did.
class Request {

  private $method;
  private $uri;
  private $query;
  private $parsedBody;

  public function __construct($method, $path, array $query=[], array $parsedBody=[]) {
    $this->method = $method;
    $this->uri = new Uri($path);
    $this->query = $query;
    $this->parsedBody = $parsedBody;
  }

  public static function fromGlobals() {
    // The path is left percent-encoded, as it appears in the request line
    $path = explode('?', $_SERVER['REQUEST_URI'] ?? '/', 2)[0];
    if(preg_match('#^https?://[^/]+(/.*)?$#i', $path, $match))
      $path = $match[1] ?? '/';
    return new self($_SERVER['REQUEST_METHOD'] ?? 'GET', $path, $_GET, $_POST);
  }

  public function getMethod() {
    return $this->method;
  }

  public function getUri() {
    return $this->uri;
  }

  public function getQueryParams() {
    return $this->query;
  }

  public function getParsedBody() {
    return $this->parsedBody;
  }

}
