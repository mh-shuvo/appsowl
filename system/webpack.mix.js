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

mix.js('resources/js/app.js', 'public/js/app.js')
   // .js('resources/js/jquery.metisMenu.js', 'public/js/jquery.metisMenu.js')
   // .js('resources/js/jquery.slimscroll.min.js', 'public/js/jquery.slimscroll.min.js')
   // .js('resources/js/inspinia.js', 'public/js/inspinia.js')
   // .js('resources/js/pace.min.js', 'public/js/pace.min.js')
   // .js('resources/js/datatables.min.js', 'public/js/datatables.min.js')
   // .js('resources/js/bootstrap-datepicker.js', 'public/js/bootstrap-datepicker.js')
   // .js('resources/js/toastr.min.js', 'public/js/toastr.min.js')
   // .js('resources/js/sweetalert.min.js', 'public/js/sweetalert.min.js')
   // .js('resources/js/jquery.validate.js', 'public/js/jquery.validate.js')
   // .js('resources/js/additional-methods.min.js', 'public/js/additional-methods.min.js')
   .sass('resources/sass/app.scss', 'public/css/app.scss')
   .styles('resources/css/font-awesome.css', 'public/css/font-awesome.css')
   .styles('resources/css/animate.css', 'public/css/animate.css')
   .styles('resources/css/style.css', 'public/css/style.css')
   .styles('resources/css/datatables.min.css', 'public/css/datatables.min.css')
   .styles('resources/css/datepicker3.css', 'public/css/datepicker3.css')
   .styles('resources/css/toastr.min.css', 'public/css/toastr.min.css')
   .version();
