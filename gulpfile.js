// Importación de módulos necesarios para la automatización de tareas
const { src, dest, watch, parallel, series } = require('gulp');
const sass = require('gulp-sass')(require('sass')); // Compilar SCSS a CSS
const autoprefixer = require('autoprefixer'); // Añadir prefijos a CSS para compatibilidad con navegadores
const postcss = require('gulp-postcss'); // Transformar CSS con plugins de PostCSS
const sourcemaps = require('gulp-sourcemaps'); // Crear sourcemaps para CSS y JS
const cssnano = require('cssnano'); // Minificar CSS
const terser = require('gulp-terser-js'); // Minificar JavaScript
const rename = require('gulp-rename'); // Renombrar archivos
const imagemin = require('gulp-imagemin'); // Optimizar y minificar imágenes
const cache = require('gulp-cache'); // Cache para optimizar rendimiento
const webp = require('gulp-webp'); // Convertir imágenes a formato WebP
const avif = require('gulp-avif'); // Convertir imágenes a formato AVIF
const newer = require('gulp-newer'); // Procesar solo archivos nuevos o modificados
const fs = require('fs'); // Interactuar con el sistema de archivos
const path = require('path'); // Utilidades para trabajar con rutas de archivos
const webpackStream = require('webpack-stream'); // Usar Webpack con Gulp
const webpack = require('webpack'); // Webpack para la compilación de módulos
const notify = require('gulp-notify'); // Notificaciones en la terminal

// Definición de rutas de los archivos a procesar
const paths = {
    scss: 'src/scss/**/*.scss', // Todos los archivos SCSS dentro de src/scss
    js: 'src/js/**/*.js', // Todos los archivos JS dentro de src/js
    imagenes: 'src/img/**/*.{png,jpg,jpeg,webp}', // Todos los archivos de imagenes PNG, JPG, JPEG y WebP dentro de src/img
    imgSrc: 'src/img/', // Carpeta de origen de imágenes
    imgDest: 'public/build/imagenes/' // Carpeta de destino de imágenes procesadas
};

// Manejo de errores
function handleError(err) {
    notify.onError({
        title: "Gulp Error",
        message: "Error: <%= error.message %>",
        sound: "Bottle"
    })(err);
    this.emit('end');
}

// Tarea para compilar SCSS a CSS
function css() {
    return src(paths.scss) // Fuente de archivos SCSS
        .pipe(sourcemaps.init()) // Inicializa sourcemaps antes de la transformación
        .pipe(sass().on('error', handleError)) // Compila SCSS a CSS y maneja errores
        .pipe(postcss([autoprefixer(), cssnano()])) // Aplica autoprefixer y minifica CSS con cssnano
        .pipe(sourcemaps.write('.')) // Escribe sourcemaps en la misma carpeta que el archivo CSS
        .pipe(dest('public/build/css')); // Destino del archivo CSS compilado
}

// Tarea para procesar y minificar JavaScript usando Webpack
function javascript() {
    return src(paths.js) // Fuente de archivos JS
        .pipe(webpackStream({
            module: {
                rules: [
                    {
                        test: /\.css$/i, // Busca archivos CSS
                        use: ['style-loader', 'css-loader'], // Usa style-loader y css-loader para manejar CSS
                    }
                ]
            },
            mode: 'production', // Modo de producción para optimizar la salida
            watch: false, // No observa cambios en los archivos
            entry: './src/js/app.js', // Punto de entrada para Webpack
        }, webpack))
        .pipe(sourcemaps.init()) // Inicializa sourcemaps antes de la transformación
        .pipe(terser().on('error', handleError)) // Minifica JavaScript y maneja errores
        .pipe(sourcemaps.write('.')) // Escribe sourcemaps en la misma carpeta que el archivo JS
        .pipe(rename({ suffix: '.min' })) // Renombra el archivo minificado para indicar que está minificado
        .pipe(dest('./public/build/js')); // Destino del archivo JS minificado
}

// Tarea para optimizar y minificar imágenes
function imagenes() {
    return src(paths.imagenes) // Fuente de archivos de imágenes
        .pipe(newer(paths.imgDest)) // Procesa solo archivos nuevos o modificados
        .pipe(cache(imagemin({ optimizationLevel: 3 }))) // Minifica las imágenes y usa cache para mejorar el rendimiento
        .pipe(dest(paths.imgDest)); // Destino de las imágenes optimizadas
}

// Tarea para generar versiones WebP de las imágenes
function versionWebp() {
    return src(paths.imagenes) // Fuente de archivos de imágenes
        .pipe(newer({ dest: paths.imgDest, ext: '.webp' })) // Procesa solo archivos nuevos o modificados con extensión .webp
        .pipe(webp({ quality: 50 })) // Convierte imágenes a WebP con calidad del 50%
        .pipe(dest(paths.imgDest)); // Destino de las imágenes en formato WebP
}

// Tarea para generar versiones AVIF de las imágenes
function versionAvif() {
    return src(paths.imagenes) // Fuente de archivos de imágenes
        .pipe(newer({ dest: paths.imgDest, ext: '.avif' })) // Procesa solo archivos nuevos o modificados con extensión .avif
        .pipe(avif({ quality: 50 })) // Convierte imágenes a AVIF con calidad del 50%
        .pipe(dest(paths.imgDest)); // Destino de las imágenes en formato AVIF
}

// Tarea para limpiar imágenes eliminadas en la carpeta fuente y actualizar nombres
function cleanDeletedImages(done) {
    fs.readdir(paths.imgSrc, (err, srcFiles) => {
        if (err) return done(err); // Maneja errores al leer la carpeta de origen

        fs.readdir(paths.imgDest, (err, destFiles) => {
            if (err) return done(err); // Maneja errores al leer la carpeta de destino

            const srcBasenames = srcFiles.map(file => path.basename(file, path.extname(file))); // Obtiene los nombres base de los archivos en la carpeta de origen
            const filesToDelete = destFiles.filter(file => {
                const baseName = path.basename(file, path.extname(file));
                return !srcBasenames.includes(baseName); // Filtra archivos que no existen en la carpeta de origen
            }).map(file => path.join(paths.imgDest, file)); // Obtiene las rutas completas de los archivos a eliminar

            console.log('Deleting the following files:', filesToDelete); // Imprime los archivos a eliminar

            filesToDelete.forEach(file => {
                fs.unlink(file, err => {
                    if (err) console.error(`Failed to delete ${file}:`, err); // Maneja errores al eliminar archivos
                });
            });

            done();
        });
    });
}

// Tarea para observar cambios en los archivos y ejecutar tareas correspondientes
function watchArchivos() {
    watch(paths.scss, css); // Observa cambios en archivos SCSS y ejecuta la tarea css
    watch(paths.js, javascript); // Observa cambios en archivos JS y ejecuta la tarea javascript
    watch(paths.imagenes, series(cleanDeletedImages, imagenes, versionWebp, versionAvif)); // Observa cambios en imágenes y ejecuta tareas correspondientes
}

// Exportación de tareas para que puedan ser ejecutadas desde la línea de comandos
exports.css = css;
exports.js = javascript;
exports.imagenes = imagenes;
exports.versionWebp = versionWebp;
exports.versionAvif = versionAvif;
exports.cleanDeletedImages = cleanDeletedImages;
exports.watchArchivos = watchArchivos;
exports.default = parallel(css, javascript, imagenes, versionWebp, versionAvif, watchArchivos); // Tarea por defecto que ejecuta todas las tareas en paralelo
