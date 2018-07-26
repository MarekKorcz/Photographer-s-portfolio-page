var Encore = require('@symfony/webpack-encore');

Encore

    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')

    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    
    // will create public/build/app.js and public/build/app.css
     .addEntry('app', './assets/js/app.js')
    // .addStyleEntry('css/app', './assets/css/app.scss')
    
    .createSharedEntry('vendor', [
        'jquery',
        'bootstrap'
    ])
    
    // uncomment for legacy applications that require $/jQuery as a global variable
    .autoProvidejQuery()
    
    // enable source maps during development
    .enableSourceMaps(!Encore.isProduction())
    
    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()
    
    // show OS notifications when builds finish/fail
    .enableBuildNotifications()   
    
    .enableSassLoader(function(sassOptions) {}, {
         resolveUrlLoader: false
     })
    
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())
;

// export the final configuration
module.exports = Encore.getWebpackConfig();