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
   .extract(['vue'])
   .sass('resources/sass/app.scss', 'public/css')
   .sass('resources/sass/vue.scss', 'public/css'); // 测试用的样式

// 生产环境才加上版本号
if (mix.inProduction()) {
    mix.version(); //加上版本号后，视图中引入的路径就要用mix()方法
}
