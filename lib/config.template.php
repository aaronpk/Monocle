<?php
class Config {
  public static $base = 'https://monocle.p3k.io.dev/';
  public static $useragent = '';

  public static $xray = 'https://xray.p3k.app/';

  // Monocle only fetches public addresses when a URL comes from a visitor (the
  // site they sign in with and its Microsub/Micropub endpoints). List any
  // hostnames, IP addresses or CIDR ranges on a private network that should
  // be reachable anyway, e.g. a Microsub server on the same machine.
  public static $httpAllow = [];

  // If you are running this image proxy, configure the base URL and secret
  // https://github.com/willnorris/imageproxy
  public static $imageProxyURL = false;
  public static $imageProxyKey = false;

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
