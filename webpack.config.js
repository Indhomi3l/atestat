const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .copyFiles({
    from: './assets/images',
    to: 'images/[path][name].[hash:8].[ext]',
    pattern: /\.(png|jpg|jpeg|avif|webp|svg)$/
  })
  .addEntry('app', './assets/app.js')
  .addEntry('main', './assets/main.js')
  .addEntry('two-sided', './assets/two-sided.js')
  .addEntry('home', './assets/home.js')
  .addEntry('discover', './assets/discover.js')
  .addEntry('about', './assets/about.js')
  .addEntry('single', './assets/single.js')
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
  .enableSassLoader()
;

module.exports = Encore.getWebpackConfig();
