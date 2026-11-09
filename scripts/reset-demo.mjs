// Deletes all demo content and rebuilds it (pages, menus, sample posts/CPT entries, media).
import { lite, runSetup } from './lib.mjs';
runSetup(process.argv.includes('--lite') ? lite : 'docker', 'reset-demo.sh');
console.log('\n✔ محتوای دمو دوباره ساخته شد.');
