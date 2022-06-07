const mix = require('laravel-mix');
const path = require('path');

mix.setPublicPath('public')
   .js('resources/js/app.js', 'public/js')
   .postCss("resources/css/app.css", "public/css", [
       require("tailwindcss"),
   ])
   .version();
