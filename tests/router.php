<?php
// Router for `php -S ... tests/router.php`: serve real files from public/
// directly, and send everything else through public/index.php like nginx does.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if($path != '/' && is_file(__DIR__.'/../public'.$path)) {
  return false;
}
chdir(__DIR__.'/../public');
require __DIR__.'/../public/index.php';
