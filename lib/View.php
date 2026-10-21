<?php
namespace Monocle;

// Renders the PHP templates in views/, with the parts of the League\Plates
// API the templates use: $this->layout(), $this->section() and
// $this->insert(). As in Plates, a template sees only the data it was
// rendered with, and a layout sees only the data passed to layout() plus
// the rendered template as the "content" section.
class View {

  private $directory;

  public function __construct($directory) {
    $this->directory = rtrim($directory, '/');
  }

  public function path($name) {
    return $this->directory.'/'.$name.'.php';
  }

  public function render($name, array $data=[]) {
    return (new Template($this, $name))->render($data);
  }

}
