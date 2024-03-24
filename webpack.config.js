const Encore = require('@symfony/webpack-encore');
const { VueLoaderPlugin } = require('vue-loader')

// // Manually configure the runtime environment if not already configured yet by the "encore" command.
// // It's useful when you use tools that rely on webpack.config.js file.
// if (!Encore.isRuntimeEnvironmentConfigured()) {
//     Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
// }
//
// module.exports = Encore.getWebpackConfig();

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or subdirectory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/js/app.js')
    .cleanupOutputBeforeBuild()
    .addPlugin(new VueLoaderPlugin())
    .enableVueLoader()
    .disableSingleRuntimeChunk();

module.exports = Encore.getWebpackConfig()
