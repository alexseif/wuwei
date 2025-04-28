const Encore = require('@symfony/webpack-encore');
const webpack = require('webpack');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .addEntry('calendar', './assets/calendar.js')
    .enableStimulusBridge('./assets/controllers.json')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })
    .addPlugin(new webpack.ProvidePlugin({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery',
        Popper: ['popper.js', 'default'],
    }))
    .enableSassLoader()
    .copyFiles({
        from: './node_modules/@fortawesome/fontawesome-free/webfonts',
        to: 'fonts/[path][name].[ext]',
    })
    .autoProvidejQuery()

    // Add this section for TinyMCE assets
    .copyFiles({
        from: './node_modules/tinymce/skins',
        to: 'skins/[path][name].[ext]',
    })
    .copyFiles({
        from: './node_modules/tinymce/themes',
        to: 'themes/[path][name].[ext]',
    })
    .copyFiles({
        from: './node_modules/tinymce/plugins',
        to: 'plugins/[path][name].[ext]',
    })
    .copyFiles({
        from: './node_modules/tinymce/models',
        to: 'models/[path][name].[ext]', // Copy the models directory
    })
    ;

module.exports = Encore.getWebpackConfig();
