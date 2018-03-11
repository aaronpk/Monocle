<?php
class Config {
  public static $base = 'https://monocle.p3k.io.dev/';
  public static $useragent = '';

  // publicly-hosted channels at alternate domains
  public static $public = [
    'stream.indieweb.org.dev' => [
      'title' => 'IndieWeb',
      'path' => '/',
      'microsub' => [
        'channel' => 'xxx',
        'endpoint' => 'https://aperture.p3k.io/microsub/1',
        'access_token' => 'xxx',
      ]
    ]
  ];
}
