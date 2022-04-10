let mix  = require( 'laravel-mix' );
let path = require( 'path' );

require( './mix' );

mix.setPublicPath( 'dist' )
   .js( 'resources/js/field.js', 'js' )
   .vue( {
       extractVueStyles: true,
       version: 3
   } )
   .sass( 'resources/scss/field.scss', 'css' )
   .nova( 'out-of-office/password-generator' );

mix.options( {
    processCssUrls:   false
} );
