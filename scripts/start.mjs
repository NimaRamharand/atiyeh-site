// `npm run start`        → Docker stack (WordPress + MariaDB), first run installs and seeds the demo.
// `npm run start:lite`   → no Docker: PHP built-in server + SQLite (needs PHP 8.2+ with pdo_sqlite).
import { lite, docker, waitForUrl, runSetup, SITE_URL_DOCKER, SITE_URL_LITE, ensureLite, startLiteServer } from './lib.mjs';

const isLite = process.argv.includes('--lite');

if (isLite) {
  await ensureLite();
  startLiteServer();
  await waitForUrl(SITE_URL_LITE + '/wp-includes/images/blank.gif');
  runSetup(lite, 'setup.sh');
  console.log(`\n✔ سایت آماده است: ${SITE_URL_LITE}   (مدیریت: ${SITE_URL_LITE}/wp-admin  admin / atiyeh-demo)`);
  console.log('برای توقف Ctrl+C را بزنید.');
} else {
  docker(['compose', 'up', '-d', 'db', 'wordpress']);
  await waitForUrl(SITE_URL_DOCKER + '/wp-includes/images/blank.gif');
  runSetup('docker', 'setup.sh');
  console.log(`\n✔ سایت آماده است: ${SITE_URL_DOCKER}   (مدیریت: ${SITE_URL_DOCKER}/wp-admin  admin / atiyeh-demo)`);
}
