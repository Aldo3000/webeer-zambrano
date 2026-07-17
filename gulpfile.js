import path from 'path'
import fs from 'fs'
import { glob } from 'glob'
import { src, dest, watch, series } from 'gulp'
import * as dartSass from 'sass'
import gulpSass from 'gulp-sass'
import terser from 'gulp-terser'
import sharp from 'sharp'

const sass = gulpSass(dartSass)

// RUTAS (como en tu gulp viejo)
const paths = {
    scss: 'src/scss/**/*.scss',
    js: 'src/js/**/*.js',
    img: 'src/img/**/*.{jpg,png}'
}

// JS
export function js(done){
    src('src/js/app.js')
        .pipe(terser())
        .pipe(dest('build/js'))
    done()
}

// CSS
export function css(done){
    src('src/scss/app.scss', { sourcemaps: true })
        .pipe(sass({
            outputStyle: 'compressed'
        }).on('error', sass.logError))
        .pipe(dest('build/css', { sourcemaps: true }))
    done()
}

// 🔥 IMÁGENES (moderno con sharp)
export async function imagenes(done) {
    const srcDir = './src/img';
    const buildDir = './build/img';
    const images = await glob(paths.img);

    images.forEach(file => {
        const relativePath = path.relative(srcDir, path.dirname(file));
        const outputSubDir = path.join(buildDir, relativePath);

        if (!fs.existsSync(outputSubDir)) {
            fs.mkdirSync(outputSubDir, { recursive: true });
        }

        const baseName = path.basename(file, path.extname(file));

        const outputJpg = path.join(outputSubDir, `${baseName}.jpg`);
        const outputWebp = path.join(outputSubDir, `${baseName}.webp`);

        const options = { quality: 80 };

        sharp(file).jpeg(options).toFile(outputJpg);
        sharp(file).webp(options).toFile(outputWebp);
    });

    done();
}


// WATCH
export function dev(){
    watch(paths.scss, css)
    watch(paths.js, js)
    watch(paths.img, imagenes)
}

// DEFAULT
export default series(js, css, imagenes, dev)