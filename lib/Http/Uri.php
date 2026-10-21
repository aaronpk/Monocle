<?php
namespace Monocle\Http;

class Uri {

  private $path;

  public function __construct($path) {
    $this->path = $path;
  }

  public function getPath() {
    return $this->path;
  }

}
