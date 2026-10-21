#!/bin/bash
# Compare two tests/run.sh output directories, masking values that change on
# every run (session ids, cookie expiry times, IndieAuth state/PKCE values,
# timestamps) and the GD placeholder bytes (run.sh checks their dimensions).
#
#   tests/compare.sh <baseline-out> <new-out> [--ignore REGEX ...]
#
# Set ACCEPT_WWW=1 to accept the p3k/utils 1.2.2 change where display_url()
# also strips "www.": link text like >www.example.com< in the baseline is
# rewritten to >example.com< before comparing. Anything else still differs.
DIR=$(cd "$(dirname "$0")" && pwd)
A=$1; B=$2; shift 2
if [ "${ACCEPT_WWW:-}" = "1" ]; then
  TMP=$(mktemp -d)
  trap 'rm -rf "$TMP"' EXIT
  cp -r "$A/responses" "$TMP/responses"
  find "$TMP/responses" -name '*.txt' -exec sed -i -E 's#(>[[:space:]]*)www\.#\1#g' {} +
  A=$TMP
fi
IGNORE=(
  --ignore 'PHPSESSID=[A-Za-z0-9,-]+'
  --ignore '(expires=)[^;]+'
  --ignore '(state=)[A-Za-z0-9_%-]+'
  --ignore '(code_challenge=)[A-Za-z0-9_%-]+'
  --ignore '(code_verifier=)[A-Za-z0-9_%-]+'
  --ignore '("indieauth_state":\s*")[^"]+'
  --ignore '("indieauth_code_verifier":\s*")[^"]+'
  --ignore '(last_reload_timestamp = )[0-9]+'
  --ignore '("channels_timestamp":\s*)[0-9]+'
  --ignore '(data:image/png;base64,)[A-Za-z0-9+/=]*'
  --ignore '(Date: )[^\\]+'
  --ignore '("Date":")[^"]+'
)
status=0
for sub in . get get-proxy; do
  [ -d "$A/responses/$sub" ] || continue
  python3 -I "$DIR/replay.py" compare "$A/responses/$sub" "$B/responses/$sub" "${IGNORE[@]}" "$@" | sed "s#^#[$sub] #"
  [ "${PIPESTATUS[0]}" = "0" ] || status=1
done
exit $status
