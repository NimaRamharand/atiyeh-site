// Copies the self-hosted Vazirmatn variable font (weights 100-900) from npm into the theme.
import { copyFileSync, mkdirSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const src = join(root, 'node_modules', 'vazirmatn');
const dest = join(root, 'wp-content', 'themes', 'atiyeh', 'assets', 'fonts');
mkdirSync(dest, { recursive: true });
copyFileSync(join(src, 'fonts', 'webfonts', 'Vazirmatn[wght].woff2'), join(dest, 'Vazirmatn-Variable.woff2'));
copyFileSync(join(src, 'OFL.txt'), join(dest, 'OFL.txt'));
console.log('Vazirmatn copied to', dest);
