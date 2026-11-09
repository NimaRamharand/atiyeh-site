#!/bin/sh
# Deletes every item tagged as demo content and rebuilds it from scripts/wp/seed.php.
set -eu
WP=${WP:-wp}
SCRIPTS_DIR=${SCRIPTS_DIR:-/scripts}
# The content types live in atiyeh-core; make sure it is on (e.g. after pulling a new version).
if $WP plugin is-installed atiyeh-core >/dev/null 2>&1 && ! $WP plugin is-active atiyeh-core >/dev/null 2>&1; then
  $WP plugin activate atiyeh-core >/dev/null
fi
echo "▸ ساخت محتوای دمو"
$WP eval-file "$SCRIPTS_DIR/seed.php"
$WP rewrite flush >/dev/null
$WP cache flush >/dev/null 2>&1 || true
