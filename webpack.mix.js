const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
require('laravel-mix-polyfill');
require('laravel-mix-purgecss');

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

mix.js('resources/js/app.js', 'public/js');
mix.js('resources/js/chapterEdit.js', 'public/js');
// mix.js('resources/js/app.js', 'public/js').extract(['vue']);
mix.sass('resources/sass/app.scss', 'public/css')
.options({
  processCssUrls: false,
  postCss: [ tailwindcss('tailwind.config.js') ],
});
mix.polyfill({
  enabled: true,
  useBuiltIns: "usage",
  targets: {"firefox": "50", "ie": 11}
})
.purgeCss({    
  enabled: mix.inProduction(),    
  folders: ['src', 'templates'],    
  extensions: ['html', 'js', 'php', 'vue'],
});
mix.version();
