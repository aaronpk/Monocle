<?php
namespace Monocle;

// One template being rendered by View. Templates call these methods as $this->...
class Template {

  private $view;
  private $name;
  private $layoutName;
  private $layoutData = [];
  private $sections = [];

  public function __construct(View $view, $name) {
    $this->view = $view;
    $this->name = $name;
  }

  public function render(array $data=[]) {
    $path = $this->view->path($this->name);
    if(!is_file($path))
      throw new \LogicException('The template "'.$this->name.'" could not be found at "'.$path.'".');

    $level = ob_get_level();
    try {
      $content = $this->capture($path, $data);

      if(isset($this->layoutName)) {
        $layout = new Template($this->view, $this->layoutName);
        $layout->sections = array_merge($this->sections, ['content' => $content]);
        $content = $layout->render($this->layoutData);
      }

      return $content;
    } catch(\Throwable $e) {
      while(ob_get_level() > $level) {
        ob_end_clean();
      }
      throw $e;
    }
  }

  public function layout($name, array $data=[]) {
    $this->layoutName = $name;
    $this->layoutData = $data;
  }

  public function section($name, $default=null) {
    return $this->sections[$name] ?? $default;
  }

  public function insert($name, array $data=[]) {
    echo $this->view->render($name, $data);
  }

  // Include the template with only its data (and $this) in scope
  private function capture($__path, array $__data) {
    extract($__data);
    unset($__data);
    ob_start();
    include $__path;
    return ob_get_clean();
  }

}
