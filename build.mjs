// node build.mjs — concatenates resources/{css,js} into dist/. No dependencies.
import { readdirSync, readFileSync, writeFileSync } from 'node:fs';

const bundle = (dir, out, banner) => {
    const files = readdirSync(dir).filter((f) => !f.startsWith('.')).sort();
    writeFileSync(out, banner + files.map((f) => `/* ---- ${f} ---- */\n` + readFileSync(`${dir}/${f}`, 'utf8')).join('\n'));
    console.log(`${out}: ${files.length} files`);
};

bundle('resources/css', 'dist/armada.css', '/* Armada — https://github.com/armsys-tech/armada — MIT. Built file, edit resources/css. */\n');
bundle('resources/js', 'dist/armada.js', '/* Armada — https://github.com/armsys-tech/armada — MIT. Built file, edit resources/js. */\n');
