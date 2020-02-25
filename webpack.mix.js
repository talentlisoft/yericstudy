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

// mix.js('resources/js/app.js', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css');

mix.copy('node_modules/angular-i18n/angular-locale_zh-cn.js', 'public/js');
mix.js('resources/js/admin/index.js', 'public/js/admin.js').sass('resources/sass/admin/index.scss', 'public/css/admin.css').version();
mix.js('resources/js/trainee/index.js', 'public/js/trainee.js').sass('resources/sass/trainee/index.scss', 'public/css/trainee.css').version();
mix.sass('resources/sass/admin/indexvue.scss', 'public/css/adminvue.css').version();
mix.js('resources/js/vueadmin/index.js', 'public/js/vue.js');
