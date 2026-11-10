#!/bin/sh
# First-run install + idempotent site configuration. Runs inside the wpcli container
# (Docker) or locally in lite mode. $WP is the WP-CLI command.
set -eu
WP=${WP:-wp}
SCRIPTS_DIR=${SCRIPTS_DIR:-/scripts}

if ! $WP core is-installed >/dev/null 2>&1; then
  echo "▸ نصب وردپرس"
  $WP core install --url="$SITE_URL" --title="آمار پژوهان پارسیان" \
    --admin_user=admin --admin_password=atiyeh-demo --admin_email=admin@example.com \
    --skip-email --locale=fa_IR
fi

echo "▸ زبان فارسی، منطقه زمانی تهران، پیوندهای یکتا"
$WP site switch-language fa_IR >/dev/null
$WP option update home "$SITE_URL" >/dev/null
$WP option update siteurl "$SITE_URL" >/dev/null
$WP option update blogdescription "پلاک کدپستی و آرشیو دیجیتال اسناد" >/dev/null
$WP option update timezone_string "Asia/Tehran" >/dev/null
$WP option update date_format "j F Y" >/dev/null
$WP option update time_format "H:i" >/dev/null
$WP option update start_of_week 6 >/dev/null
$WP option update blog_public 0 >/dev/null
$WP rewrite structure '/%postname%/' >/dev/null

echo "▸ قالب و افزونه‌ها"
$WP theme activate atiyeh >/dev/null
for t in $($WP theme list --status=inactive --field=name); do $WP theme delete "$t" >/dev/null || true; done
$WP plugin delete akismet hello >/dev/null 2>&1 || true
PLUGINS="atiyeh-core wp-parsidate fluentform seo-by-rank-math duplicator"
# Duplicator needs MySQL; skip it in the SQLite lite mode.
[ "${ATIYEH_LITE:-}" = "1" ] && PLUGINS="atiyeh-core wp-parsidate fluentform seo-by-rank-math" && $WP plugin deactivate duplicator >/dev/null 2>&1 || true
for p in $PLUGINS; do
  if $WP plugin is-installed "$p" >/dev/null 2>&1; then $WP plugin activate "$p" >/dev/null 2>&1 || true; fi
done
$WP rewrite flush >/dev/null

sh "$SCRIPTS_DIR/reset-demo.sh"
