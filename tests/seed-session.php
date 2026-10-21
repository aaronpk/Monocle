<?php
// Writes a logged-in Monocle session into a session save path, so tests can
// request pages that require login without going through IndieAuth.
//
//   php tests/seed-session.php <session.save_path> <session id>
list(, $savePath, $id) = $argv;
$fixtures = __DIR__.'/fixtures';
$base = 'http://127.0.0.1:18080';

session_save_path($savePath);
session_id($id);
session_start();
$_SESSION = [
  'token' => [
    'access_token' => 'fixture-access-token',
    'token_type' => 'Bearer',
    'scope' => 'create update read follow channels profile',
    'me' => "$base/me/",
  ],
  'microsub' => "$base/microsub",
  'channels' => json_decode(file_get_contents("$fixtures/channels.json"), true)['channels'],
  'channels_timestamp' => 1700000000,
  'micropub' => [
    'endpoint' => "$base/micropub",
    'config' => json_decode(file_get_contents("$fixtures/micropub-config.json"), true),
  ],
];
session_write_close();
