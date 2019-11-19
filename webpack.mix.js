const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/dashboard.js', 'public/js')
    .js('resources/js/forum.js', 'public/js')
    .js('resources/js/thread.js', 'public/js')
    .js('resources/js/profile.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/dashboard.scss', 'public/css')
    .sass('resources/sass/thread.scss', 'public/css')
    .sass('resources/sass/forum.scss', 'public/css')
    .sass('resources/sass/profile.scss', 'public/css');
