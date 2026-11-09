// Shared helpers for the start / reset-demo commands.
import { spawn, spawnSync } from 'node:child_process';
import { existsSync, mkdirSync, symlinkSync, readdirSync, writeFileSync, rmSync, lstatSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

export const ROOT = join(dirname(fileURLToPath(import.meta.url)), '..');
const PORT = process.env.ATIYEH_PORT || '8093';
const LITE_PORT = process.env.ATIYEH_LITE_PORT || '8081';
export const SITE_URL_DOCKER = `http://localhost:${PORT}`;
export const SITE_URL_LITE = `http://localhost:${LITE_PORT}`;
const CACHE = join(ROOT, '.cache');
const CORE = join(CACHE, 'wordpress');
const PHAR = join(CACHE, 'wp-cli.phar');
export const lite = 'lite';

function run(cmd, args, opts = {}) {
  const r = spawnSync(cmd, args, { stdio: 'inherit', cwd: ROOT, ...opts });
  if (r.status !== 0) {
    console.error(`✖ فرمان ناموفق بود: ${cmd} ${args.join(' ')}`);
    process.exit(r.status ?? 1);
  }
}

export function docker(args) { run('docker', args); }

export async function waitForUrl(url, timeoutMs = 180000) {
  const start = Date.now();
  process.stdout.write('در انتظار بالا آمدن وردپرس');
  while (Date.now() - start < timeoutMs) {
    try { const r = await fetch(url); if (r.ok) { console.log(' ✔'); return; } } catch {}
    process.stdout.write('.');
    await new Promise((r) => setTimeout(r, 2000));
  }
  console.error('\n✖ وردپرس در زمان مقرر بالا نیامد.'); process.exit(1);
}

/** Runs scripts/wp/<script> with WP-CLI, inside Docker or locally (lite). */
export function runSetup(mode, script) {
  if (mode === 'docker') {
    run('docker', ['compose', 'run', '--rm', 'wpcli', 'sh', `/scripts/${script}`]);
    return;
  }
  run('sh', [join(ROOT, 'scripts', 'wp', script)], {
    env: {
      ...process.env,
      WP: `php ${PHAR} --path=${CORE}${process.getuid?.() === 0 ? ' --allow-root' : ''}`,
      SITE_URL: SITE_URL_LITE,
      ASSETS_DIR: join(ROOT, 'assets-source'),
      DEMO_DIR: join(ROOT, 'demo'),
      SCRIPTS_DIR: join(ROOT, 'scripts', 'wp'),
      ATIYEH_LITE: '1',
    },
  });
}

async function download(url, dest) {
  console.log('دانلود', url);
  const r = await fetch(url);
  if (!r.ok) throw new Error(`download failed ${url}`);
  writeFileSync(dest, Buffer.from(await r.arrayBuffer()));
}

function link(target, path) {
  try { lstatSync(path); rmSync(path, { recursive: true, force: true }); } catch {}
  symlinkSync(target, path);
}

/** Prepares .cache/wordpress (Persian core + SQLite drop-in) whose wp-content links back to the repo. */
export async function ensureLite() {
  mkdirSync(CACHE, { recursive: true });
  if (!existsSync(PHAR)) await download('https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar', PHAR);
  if (!existsSync(join(CORE, 'wp-includes'))) {
    const zip = join(CACHE, 'wp-fa.zip');
    if (!existsSync(zip)) await download('https://fa.wordpress.org/latest-fa_IR.zip', zip);
    run('unzip', ['-q', '-o', zip, '-d', CACHE]);
  }
  const sqlite = join(CACHE, 'sqlite-database-integration');
  if (!existsSync(sqlite)) {
    const zip = join(CACHE, 'sqlite.zip');
    if (!existsSync(zip)) await download('https://downloads.wordpress.org/plugin/sqlite-database-integration.latest-stable.zip', zip);
    run('unzip', ['-q', '-o', zip, '-d', CACHE]);
  }
  const content = join(CORE, 'wp-content');
  link(join(ROOT, 'wp-content', 'themes', 'atiyeh'), join(content, 'themes', 'atiyeh'));
  link(join(ROOT, 'wp-content', 'languages'), join(content, 'languages'));
  for (const p of readdirSync(join(ROOT, 'wp-content', 'plugins'))) {
    if (['akismet', 'hello.php', 'index.php'].includes(p)) continue;
    link(join(ROOT, 'wp-content', 'plugins', p), join(content, 'plugins', p));
  }
  link(sqlite, join(content, 'plugins', 'sqlite-database-integration'));
  if (!existsSync(join(content, 'db.php'))) {
    const dropin = require_text(join(sqlite, 'db.copy'))
      .replace(/\{SQLITE_IMPLEMENTATION_FOLDER_PATH\}/g, sqlite)
      .replace(/\{SQLITE_PLUGIN\}/g, 'sqlite-database-integration/load.php');
    writeFileSync(join(content, 'db.php'), dropin);
  }
  const cfg = join(CORE, 'wp-config.php');
  if (!existsSync(cfg)) {
    writeFileSync(cfg, `<?php
define('DB_NAME','atiyeh'); define('DB_USER',''); define('DB_PASSWORD',''); define('DB_HOST','');
define('DB_CHARSET','utf8mb4'); define('DB_COLLATE','');
define('DB_DIR', __DIR__ . '/wp-content/database/'); define('DB_FILE','atiyeh.sqlite');
define('WP_ENVIRONMENT_TYPE','local'); define('AUTOMATIC_UPDATER_DISABLED',true);
define('DISALLOW_FILE_EDIT',true); define('DISABLE_WP_CRON',true); define('WP_HTTP_BLOCK_EXTERNAL',true); define('WP_ACCESSIBLE_HOSTS','localhost');
$table_prefix = 'wp_';
if (!defined('ABSPATH')) define('ABSPATH', __DIR__ . '/');
require_once ABSPATH . 'wp-settings.php';
`);
  }
  writeFileSync(join(CACHE, 'router.php'), `<?php
$root = ${JSON.stringify(CORE)};
$path = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
if ($path !== '/' && file_exists($root . $path) && !is_dir($root . $path)) return false;
if (is_dir($root . $path) && file_exists($root . rtrim($path, '/') . '/index.php')) { require $root . rtrim($path, '/') . '/index.php'; return; }
$_SERVER['SCRIPT_NAME'] = '/index.php';
require $root . '/index.php';
`);
}

import { readFileSync } from 'node:fs';
function require_text(p) { return readFileSync(p, 'utf8'); }

export function startLiteServer() {
  const child = spawn('php', ['-S', `localhost:${LITE_PORT}`, '-t', CORE, join(CACHE, 'router.php')], { stdio: 'ignore', env: { ...process.env, PHP_CLI_SERVER_WORKERS: '4' } });
  process.on('SIGINT', () => { child.kill(); process.exit(0); });
  return child;
}
