<?php
// Configuration used by tests/run.sh. External services point at the
// fixture server in tests/fixtures/server.php.
class Config {
  public static $base = 'http://127.0.0.1:18081/';
  public static $useragent = 'Monocle test';

  public static $xray = 'http://127.0.0.1:18080/xray/';

  public static $imageProxyURL = false;
  public static $imageProxyKey = false;

  public static $public = [
    '127.0.0.1/stream' => [
      'title' => 'Public Stream',
      'path' => '/stream',
      'microsub' => [
        'channel' => 'home',
        'endpoint' => 'http://127.0.0.1:18080/microsub',
        'access_token' => 'public-token',
      ]
    ]
  ];
}
