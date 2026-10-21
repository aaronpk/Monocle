<?php
namespace Monocle;

use Monocle\Http\Request;
use Monocle\Http\Response;

// A small router with the matching rules Monocle relied on from
// league/route 3 and FastRoute: routes without placeholders are checked
// before routes with them, a {name} placeholder matches one path segment,
// HEAD requests fall back to GET routes, and a path that only matches other
// methods is a 405. Handlers are "Class::method" strings, called with
// ($request, $response, $args) on a new instance of the class.
class Router {

  const NOT_FOUND = 404;
  const METHOD_NOT_ALLOWED = 405;

  private $static = [];
  private $variable = [];

  public function map($method, $path, $handler) {
    if(strpos($path, '{') === false) {
      $this->static[$method][$path] = $handler;
    } else {
      $names = [];
      $regex = preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_-]*)\}/', function($m) use(&$names) {
        $names[] = $m[1];
        return '([^/]+)';
      }, $path);
      $this->variable[$method][] = ['regex' => '~^'.$regex.'$~', 'names' => $names, 'handler' => $handler];
    }
  }

  // Returns the handler's response, or throws RouteNotFound / MethodNotAllowed
  public function dispatch(Request $request, Response $response) {
    $method = $request->getMethod();
    $path = $request->getUri()->getPath();

    $match = $this->match($method, $path);
    if(!$match && $method == 'HEAD')
      $match = $this->match('GET', $path);

    if(!$match) {
      foreach(array_unique(array_merge(array_keys($this->static), array_keys($this->variable))) as $other) {
        if($other != $method && $this->match($other, $path))
          throw new MethodNotAllowed();
      }
      throw new RouteNotFound();
    }

    list($handler, $args) = $match;
    list($class, $function) = explode('::', $handler);
    $result = (new $class)->$function($request, $response, $args);

    if(!($result instanceof Response))
      throw new \RuntimeException('Route callables must return a response');

    return $result;
  }

  private function match($method, $path) {
    if(isset($this->static[$method][$path]))
      return [$this->static[$method][$path], []];

    foreach($this->variable[$method] ?? [] as $route) {
      if(preg_match($route['regex'], $path, $m)) {
        array_shift($m);
        return [$route['handler'], array_combine($route['names'], $m)];
      }
    }

    return false;
  }

}
