#!/bin/sh
# Deletes every item tagged as demo content and rebuilds it from scripts/wp/seed.php.
set -eu
WP=${WP:-wp}
SCRIPTS_DIR=${SCRIPTS_DIR:-/scripts}
echo "▸ ساخت محتوای دمو"
$WP eval-file "$SCRIPTS_DIR/seed.php"
$WP rewrite flush >/dev/null
$WP cache flush >/dev/null 2>&1 || true
