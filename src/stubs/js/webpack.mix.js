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

mix.options({processCssUrls: false})
   .ts('resources/js/app.ts', 'public/js')
   .sourceMaps(false)
   .extract([
      'vue', 'vuex', 'axios'
   ])
   .sass('resources/sass/app.scss', 'public/css');

if (mix.inProduction()) {
   mix.purgeCss({whitelistPatterns: [/animation$/]})
      .version();
}