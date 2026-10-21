<?php
namespace Monocle\Http;

// A response body that is written to by controllers and views. Like a PSR-7
// stream, it is shared between a response and the copies made by withHeader()
// and withStatus().
class Body {

  private $contents = '';

  public function write($string) {
    $this->contents .= $string;
    return strlen($string);
  }

  public function __toString() {
    return $this->contents;
  }

}
