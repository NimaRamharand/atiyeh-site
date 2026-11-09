// Deletes all demo content and rebuilds it (pages, menus, sample posts/CPT entries, media).
import { lite, runSetup, ensureLite } from './lib.mjs';
const mode = process.argv.includes('--lite') ? lite : 'docker';
if (mode === lite) await ensureLite();
runSetup(mode, 'reset-demo.sh');
console.log('\n✔ محتوای دمو دوباره ساخته شد.');
