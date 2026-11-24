<?php
// Writes a logged-in Monocle session into a session save path, so tests can
// request pages that require login without going through IndieAuth.
//
//   php tests/seed-session.php <session.save_path> <session id> [micropub endpoint]
list(, $savePath, $id) = $argv;
$micropub = $argv[3] ?? null;
$fixtures = __DIR__.'/fixtures';
$base = 'http://127.0.0.1:18080';

// PHP 8.6 turns session.use_strict_mode on by default, which replaces an id
// PHP didn't issue itself with a random one. Turn it off here so the session
// is written under the given id; the app still accepts that id in strict mode
// because the session file exists by then.
ini_set('session.use_strict_mode', '0');
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
    'endpoint' => $micropub ?: "$base/micropub",
    'config' => json_decode(file_get_contents("$fixtures/micropub-config.json"), true),
  ],
];
session_write_close();
