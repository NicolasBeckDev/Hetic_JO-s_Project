var Encore = require('@symfony/webpack-encore');

Encore
// directory where all compiled assets will be stored
    .setOutputPath('web/build/')

    // what's the public path to this directory (relative to your project's document root dir)
    .setPublicPath('/build')

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()

    // will output as web/build/app.js
    .addEntry('app', './src/AppBundle/Resources/assets/js/main.js')

    // will output as web/build/global.css
    .addStyleEntry('global', './src/AppBundle/Resources/assets/css/global.scss')

    // allow sass/scss files to be processed
     .enableSassLoader(function(sassOptions) {}, {
             resolveUrlLoader: false
     })

    // allow legacy applications to use $/jQuery as a global variable
    .autoProvidejQuery()

    .enableSourceMaps(!Encore.isProduction())

// create hashed filenames (e.g. app.abc123.css)
    .enableVersioning()
;

// export the final configuration
module.exports = Encore.getWebpackConfig();