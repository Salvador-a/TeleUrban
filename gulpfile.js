// Importa las funciones necesarias de gulp
const { src, dest, watch, parallel } = require('gulp');

// CSS
// Importa gulp-sass para compilar archivos SCSS a CSS
const sass = require('gulp-sass')(require('sass'));
// Importa gulp-plumber para prevenir la detención del proceso de Gulp cuando ocurren errores
const plumber = require('gulp-plumber');
// Importa autoprefixer para añadir prefijos a las propiedades CSS para diferentes navegadores
const autoprefixer = require('autoprefixer');
// Importa cssnano para minificar el CSS
const cssnano = require('cssnano');
// Importa gulp-postcss para transformar el CSS con plugins de JavaScript
const postcss = require('gulp-postcss');
// Importa gulp-sourcemaps para generar sourcemaps para el CSS
const sourcemaps = require('gulp-sourcemaps');

// Imágenes
const cache = require('gulp-cache'); // Importa gulp-cache para almacenar en caché y reutilizar los resultados de tareas costosas
const imagemin = require('gulp-imagemin'); // Importa gulp-imagemin para minificar imágenes
const webp = require('gulp-webp'); // Importa gulp-webp para convertir imágenes a formato WebP
const avif = require('gulp-avif'); // Importa gulp-avif para convertir imágenes a formato AVIF

// Javascript
const terser = require('gulp-terser-js'); // Importa gulp-terser-js para minificar archivos JavaScript
const concat = require('gulp-concat'); // Importa gulp-concat para concatenar archivos
const rename = require('gulp-rename') // Importa gulp-rename para renombrar archivos

// Webpack
const webpack = require('webpack-stream'); // Importa webpack-stream para usar webpack con Gulp



// Define los paths de los archivos a procesar
const paths = {
    scss: 'src/scss/**/*.scss', // Todos los archivos .scss dentro de la carpeta src/scss
    js: 'src/js/**/*.js', // Todos los archivos .js dentro de la carpeta src/js
    imagenes: 'src/img/**/*' // Todos los archivos dentro de la carpeta src/img
}

// Define la tarea css
function css() {
    return src(paths.scss) // Toma todos los archivos .scss
        .pipe(sourcemaps.init()) // Inicializa la generación de sourcemaps
        .pipe(sass()) // Compila los archivos .scss a .css
        .pipe(postcss([autoprefixer(), cssnano()])) // Aplica autoprefixer y cssnano (minificación)
        .pipe(sourcemaps.write('.')) // Escribe los sourcemaps en el mismo directorio que el archivo .css
        .pipe( dest('public/build/css') ); // Coloca los archivos resultantes en la carpeta public/build/css
}
// Define la tarea javascript
function javascript() {
    return src(paths.js) // Toma todos los archivos .js
    .pipe(webpack({ // Inicia webpack
        module: {
            rules: [
                {
                    test: /\.css$/i, // Aplica la regla a todos los archivos .css
                    use: ['style-loader', 'css-loader'], // Usa style-loader y css-loader
                }
            ]
        },
        mode: 'production', // Establece el modo de webpack a 'production'
        watch: true, // Habilita la observación de cambios en los archivos
        entry: './src/js/app.js', // Define el punto de entrada de la aplicación
    }))
    .pipe(sourcemaps.init()) // Inicializa la generación de sourcemaps
    .pipe(terser()) // Minifica el JavaScript
    .pipe(sourcemaps.write('.')) // Escribe los sourcemaps en el mismo directorio que el archivo .js
    .pipe(rename({ suffix: '.min' })) // Añade el sufijo '.min' al nombre del archivo
    .pipe(dest('./public/build/js')) // Coloca los archivos resultantes en la carpeta public/build/js
}

// Define la tarea imagenes
function imagenes() {
    return src(paths.imagenes) // Toma todos los archivos en la carpeta de imágenes
        .pipe( cache(imagemin({ optimizationLevel: 3}))) // Minifica las imágenes con un nivel de optimización de 3 y las almacena en caché
        .pipe( dest('public/build/img')) // Coloca las imágenes resultantes en la carpeta public/build/img
}

// Define la función versionWebp
function versionWebp( done ) {
    const opciones = {
        quality: 50 // Establece la calidad de la imagen WebP a 50
    };
    src('src/img/**/*.{png,jpg}') // Toma todas las imágenes .png y .jpg en la carpeta de imágenes
        .pipe( webp(opciones) ) // Convierte las imágenes a formato WebP con las opciones especificadas
        .pipe( dest('public/build/img') ) // Coloca las imágenes resultantes en la carpeta public/build/img
    done(); // Indica que la tarea ha terminado
}

// Define la función versionAvif
function versionAvif( done ) {
    const opciones = {
        quality: 50 // Establece la calidad de la imagen AVIF a 50
    };
    src('src/img/**/*.{png,jpg}') // Toma todas las imágenes .png y .jpg en la carpeta de imágenes
        .pipe( avif(opciones) ) // Convierte las imágenes a formato AVIF con las opciones especificadas
        .pipe( dest('public/build/img') ) // Coloca las imágenes resultantes en la carpeta public/build/img
    done(); // Indica que la tarea ha terminado
}

// Define la función dev
function dev(done) {
    watch( paths.scss, css ); // Observa cambios en los archivos .scss y ejecuta la tarea css cuando ocurren cambios
    watch( paths.js, javascript ); // Observa cambios en los archivos .js y ejecuta la tarea javascript cuando ocurren cambios
    watch( paths.imagenes, imagenes) // Observa cambios en las imágenes y ejecuta la tarea imagenes cuando ocurren cambios
    watch( paths.imagenes, versionWebp) // Observa cambios en las imágenes y ejecuta la tarea versionWebp cuando ocurren cambios
    watch( paths.imagenes, versionAvif) // Observa cambios en las imágenes y ejecuta la tarea versionAvif cuando ocurren cambios
    done() // Indica que la tarea ha terminado
}
// Exporta la tarea css para que pueda ser ejecutada desde la línea de comandos con 'gulp css'
exports.css = css;

// Exporta la tarea javascript para que pueda ser ejecutada desde la línea de comandos con 'gulp js'
exports.js = javascript;

// Exporta la tarea imagenes para que pueda ser ejecutada desde la línea de comandos con 'gulp imagenes'
exports.imagenes = imagenes;

// Exporta la tarea versionWebp para que pueda ser ejecutada desde la línea de comandos con 'gulp versionWebp'
exports.versionWebp = versionWebp;

// Exporta la tarea versionAvif para que pueda ser ejecutada desde la línea de comandos con 'gulp versionAvif'
exports.versionAvif = versionAvif;

// Exporta la tarea dev para que pueda ser ejecutada desde la línea de comandos con 'gulp dev'
// La tarea dev se ejecuta en paralelo con las tareas css, imagenes, versionWebp, versionAvif, javascript
exports.dev = parallel( css, imagenes, versionWebp, versionAvif, javascript, dev);