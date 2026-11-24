#!/bin/bash
# Characterization harness. Runs Monocle under the given PHP binary with the
# built-in server, against the fixture server in tests/fixtures/server.php,
# and records every response (and every request Monocle forwarded) so runs on
# different PHP versions can be compared with tests/compare.sh.
#
#   tests/run.sh <php-binary> <out-dir> [-d ini=value ...]
#
# e.g. tests/run.sh php7.4 /tmp/out-74 -d short_open_tag=On
#
# PHP 8.6 changed three session defaults, which show up in the Set-Cookie
# headers. To compare an older PHP against 8.6 or later, run the older one with
#   -d session.use_strict_mode=1 -d session.cookie_httponly=1 -d session.cookie_samesite=Lax
set -u
PHP=$1; OUT=$2; shift 2
ROOT=$(cd "$(dirname "$0")/.." && pwd)
FIXTURE_PHP=${FIXTURE_PHP:-php8.4}
SESSION=monocletest
FIX=http://127.0.0.1:18080

rm -rf "$OUT"
mkdir -p "$OUT/sessions" "$OUT/responses"
export FIXTURE_LOG="$OUT/fixture-requests.log"
: > "$FIXTURE_LOG"

PHPFLAGS=(-d display_errors=0 -d log_errors=1 -d error_reporting=-1 -d "error_log=$OUT/php-errors.log"
          -d expose_php=0 -d "session.save_path=$OUT/sessions" "$@")

pids=()
trap 'kill "${pids[@]}" 2>/dev/null; wait 2>/dev/null' EXIT
$FIXTURE_PHP -S 127.0.0.1:18080 "$ROOT/tests/fixtures/server.php" > "$OUT/fixture-server.log" 2>&1 & pids+=($!)
ENV=test $PHP "${PHPFLAGS[@]}" -S 127.0.0.1:18081 -t "$ROOT/public" "$ROOT/tests/router.php" > "$OUT/server.log" 2>&1 & pids+=($!)
ENV=testproxy $PHP "${PHPFLAGS[@]}" -S 127.0.0.1:18082 -t "$ROOT/public" "$ROOT/tests/router.php" > "$OUT/server-proxy.log" 2>&1 & pids+=($!)
for port in 18080 18081 18082; do
  for i in $(seq 50); do curl -s -o /dev/null "http://127.0.0.1:$port/" && break; sleep 0.1; done
done

pass=0; fail=0
check() { if [ "$2" = "0" ]; then pass=$((pass+1)); else echo "  FAIL $1"; fail=$((fail+1)); fi; }
resp() { cat "$OUT/responses/$1"; }

seed() { $PHP "$ROOT/tests/seed-session.php" "$OUT/sessions" "$SESSION"; }

# Save the response (status line and headers minus Date and Host, then body)
# plus the requests Monocle sent to the fixture server while handling it.
# Host is dropped because the built-in server only sends it before PHP 8.6.
record() {
  local name=$1; shift
  local before; before=$(wc -l < "$FIXTURE_LOG")
  curl -s -D "$OUT/h.tmp" -o "$OUT/b.tmp" "$@"
  { grep -ivE '^(date|host):' "$OUT/h.tmp" | tr -d '\r'; cat "$OUT/b.tmp"; } > "$OUT/responses/$name.txt"
  { echo "forwarded:"; sed -n "$((before+1)),\$p" "$FIXTURE_LOG"; } > "$OUT/responses/$name.forwarded.txt"
  rm -f "$OUT/h.tmp" "$OUT/b.tmp"
}

seed

# --- GET pages ---------------------------------------------------------------
python3 -I "$ROOT/tests/replay.py" record --base http://127.0.0.1:18081 --requests "$ROOT/tests/requests.txt" \
  --out "$OUT/responses/get" --all-headers --drop-header host > /dev/null
python3 -I "$ROOT/tests/replay.py" record --base http://127.0.0.1:18082 --requests "$ROOT/tests/requests-proxy.txt" \
  --out "$OUT/responses/get-proxy" --all-headers --drop-header host > /dev/null
cp "$FIXTURE_LOG" "$OUT/responses/get.forwarded.txt"

grep -q 'An Article Title' "$OUT"/responses/get/*GET-channel-home.txt; check "home timeline renders" $?
grep -q '^status: 200' "$OUT"/responses/get/*GET-channel-edge.txt; check "edge-case timeline renders (200)" $?
grep -q 'Page two' "$OUT"/responses/get/*GET-channel-home-after-a1.txt; check "paging" $?
grep -q '^status: 200' "$OUT"/responses/get/0000-GET-.txt; check "logged-out / shows the index" $?
grep -q '^location: /channel/notifications' "$OUT"/responses/get/0014-GET-.txt; check "logged-in / redirects to the first channel" $?
grep -q 'https://imageproxy.example/1240x0,s' "$OUT"/responses/get-proxy/*GET-channel-home.txt; check "image proxy URLs are signed" $?

# The placeholder for a photo with refs ratio 1.5 is a 1500x1000 PNG
png=$(grep -o 'data:image/png;base64,[A-Za-z0-9+/=]*' "$OUT"/responses/get/*GET-channel-home.txt | head -1 | cut -d, -f2)
dims=$(echo "$png" | $FIXTURE_PHP -r '$i = @getimagesizefromstring(base64_decode(stream_get_contents(STDIN))); echo $i ? "$i[0]x$i[1]" : "none";')
[ "$dims" = "1500x1000" ]; check "image placeholder is 1500x1000 (got $dims)" $?
echo "$dims" > "$OUT/responses/placeholder-dimensions.txt"

# --- POST actions (logged in) --------------------------------------------------
C=(-b "PHPSESSID=$SESSION")
B=http://127.0.0.1:18081
record post-mark-read "${C[@]}" -d channel=home -d 'entry[]=101' -d 'entry[]=103' $B/microsub/mark_read
record post-mark-all-read "${C[@]}" -d channel=home -d entry=101 $B/microsub/mark_all_as_read
record post-mark-unread "${C[@]}" -d channel=home -d 'entry[]=102' $B/microsub/mark_unread
record post-remove "${C[@]}" -d channel=home -d entry=104 $B/microsub/remove
record post-reload-json "${C[@]}" -X POST "$B/channels/reload?format=json"
record post-reload-html "${C[@]}" -X POST $B/channels/reload
record post-micropub "${C[@]}" -d content=Hello -d 'mp-destination=https://alice.example/' $B/micropub
record post-micropub-reply "${C[@]}" -d 'in-reply-to=https://bob.example/notes/1' -d content=Reply $B/micropub
record post-micropub-json "${C[@]}" -H 'Content-Type: application/json' --data '{"content":"json"}' $B/micropub
record post-micropub-refresh "${C[@]}" -X POST $B/micropub/refresh
record post-preview "${C[@]}" -d url=https://feed.example/ $B/preview
record post-preview-404 -d url=https://notfound.example/ $B/preview
record post-preview-error -d url=https://broken.example/ $B/preview
record post-root-405 -X POST $B/
record post-logged-out -d channel=home -d entry=1 $B/microsub/mark_read

grep -q 'method=mark_read' "$OUT/responses/post-mark-read.forwarded.txt"; check "mark_read forwarded to microsub" $?
grep -qF '"location":"http:\/\/127.0.0.1:18080\/posts\/1"' "$OUT/responses/post-micropub.txt"; check "micropub post returns location" $?
grep -q 'Preview item one' "$OUT/responses/post-preview.txt"; check "preview renders xray items" $?
grep -q '^HTTP/1.1 405' "$OUT/responses/post-root-405.txt"; check "POST / is 405" $?

# --- Login flow through the fixture IndieAuth server ---------------------------
J=(-b "$OUT/jar" -c "$OUT/jar")
record login-start "${J[@]}" -d "url=$FIX/me/" -d auto-scheme=0 $B/login
state=$(grep -i '^location:' "$OUT/responses/login-start.txt" | grep -o 'state=[^&]*' | cut -d= -f2)
iss=$(grep -i '^location:' "$OUT/responses/login-start.txt" | grep -o 'iss=[^&]*' | cut -d= -f2)
record login-callback "${J[@]}" "$B/login/callback?code=fixture-code&state=$state"
record login-debug "${J[@]}" $B/debug
record login-home "${J[@]}" $B/
record logout "${J[@]}" $B/logout
record login-bad-state "${J[@]}" -d "url=$FIX/me/" -d auto-scheme=0 $B/login
record login-bad-state-callback "${J[@]}" "$B/login/callback?code=fixture-code&state=wrong"
record login-bad-state-page "${J[@]}" $B/login
record login-missing-url "${J[@]}" -X POST $B/login
record login-missing-url-page "${J[@]}" $B/login

grep -q 'fixture-access-token' "$OUT/responses/login-debug.txt"; check "login stores the token in the session" $?
grep -q 'invalid_state' "$OUT/responses/login-bad-state-page.txt"; check "bad state is reported" $?

# --- Summaries -----------------------------------------------------------------
# PHP error log without timestamps and paths, counted by message
sed -E 's/^\[[^]]*\] //; s#'"$ROOT"'/##g' "$OUT/php-errors.log" 2>/dev/null | sort | uniq -c | sort -rn > "$OUT/php-errors-summary.txt"
fatals=$(grep -ciE 'fatal|uncaught' "$OUT/php-errors-summary.txt")
echo "$(basename "$OUT"): $pass checks passed, $fail failed; php error log: $(wc -l < "$OUT/php-errors-summary.txt") distinct messages, $fatals fatal"
[ "$fail" = "0" ]
