<?php
// Same as config.test.php, but with the image proxy enabled so image_proxy()
// signing is exercised.
class Config {
  public static $base = 'http://127.0.0.1:18082/';
  public static $useragent = 'Monocle test';

  public static $xray = 'http://127.0.0.1:18080/xray/';

  public static $imageProxyURL = 'https://imageproxy.example/';
  public static $imageProxyKey = 'test-signing-key';

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
