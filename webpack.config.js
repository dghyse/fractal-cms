/**
 * webpack.config.js
 *
 * @author Philippe Gaultier <pgaultier@redcat.io>
 * @copyright 2018-2021 Redcat
 * @license https://www.redcat.io/license license
 * @version XXX
 * @link https://www.redcat.io
 */

const argv = require('yargs').argv;
const webpack = require('webpack');
const path = require('path');
const fs = require('fs');
const AssetsWebpackPlugin = require('assets-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const CompressionWebpackPlugin = require('compression-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const Hashes = require('jshashes');
// const { AureliaPlugin, ModuleDependenciesPlugin } = require('aurelia-webpack-plugin');
const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer');
const DuplicatePackageCheckerPlugin = require('duplicate-package-checker-webpack-plugin');
// const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');


const AutoprefixerPlugin = require('autoprefixer');
const CssNano = require('cssnano');

const prodFlag = (process.argv.indexOf('-p') !== -1) || (process.argv.indexOf('production') !== -1);

var confPath = './webpack-yii.json';
if(argv.env && argv.env.config) {
  confPath = path.join(__dirname, argv.env.config, 'webpack-yii.json');
}
if(!fs.existsSync(confPath)) {
  throw 'Error: file "' + confPath + '" not found.';
}
var version = '1.0.0';

var config = require(confPath);
if (argv.env && argv.env.config) {
  config.sourceDir = path.relative(__dirname, argv.env.config);
}

var webpackConfig = {
  entry: config.entry,
  mode: prodFlag ? 'production' : 'development',
  performance: {
    hints: prodFlag ? false : 'warning',
    maxEntrypointSize: 512000,
    maxAssetSize: 512000
  },
  context: path.resolve(__dirname, config.sourceDir, config.subDirectories.sources),
  output: {
    path: path.resolve(__dirname, config.sourceDir, config.subDirectories.dist),
    filename: prodFlag ?  config.assets.scripts + '/[name].[chunkhash:8].js' : config.assets.scripts + '/[name].js',
    // chunkFilename: prodFlag ?  config.assets.scripts + '/[name]-[id].[chunkhash:8].js' : config.assets.scripts + '/[name]-[id].js'
    chunkFilename: prodFlag ?  config.assets.scripts + '/[name].[chunkhash:8].js' : config.assets.scripts + '/[name].js'
    // sourceMapFilename: prodFlag ?  config.assets.scripts + '/[name]-[id].[chunkhash:8].js' : config.assets.scripts + '/[name]-[id].js'
  },
  plugins: [
    // new RemoveEmptyScriptsPlugin(),
    new webpack.DefinePlugin({
      PRODUCTION: JSON.stringify(prodFlag),
      VERSION: JSON.stringify(prodFlag ? version : version + '-dev'),
    }),
    /*/
    new webpack.ProvidePlugin({
        Promise: "es6-promise-promise"
    }),
    /**/

    new DuplicatePackageCheckerPlugin(),
    new MiniCssExtractPlugin({
      // path: path.resolve(__dirname, config.sourceDir, config.subDirectories.dist),
      filename: prodFlag ? config.assets.styles + '/[name].[chunkhash:8].css' : config.assets.styles + '/[name].css',
      // chunkFilename: prodFlag ? config.assets.styles + '/[name]-[id].[chunkhash:8].css' : config.assets.styles + '/[name]-[id].css'
      chunkFilename: prodFlag ? config.assets.styles + '/[name].[chunkhash:8].css' : config.assets.styles + '/[name].css'
      // sourceMapFilename: prodFlag ?  config.assets.scripts + '/[name]-[id].[chunkhash:8].css' : config.assets.scripts + '/[name]-[id].css'
    }),
    /*/
    new AureliaPlugin({
        aureliaApp: undefined,
        entry: ["app"]
    }),
    /**/
    /**/
    new CompressionWebpackPlugin({
      filename: "[path][base].gz[query]",
      algorithm: "gzip",
      test: /\.(js|css|map)$/,
      threshold: 10,
      minRatio: 1
    }),
    /**/
    new CleanWebpackPlugin({
      verbose: true,
      dry: !prodFlag
    }),
    /**/
    new AssetsWebpackPlugin({
      prettyPrint: true,
      filename: config.catalog,
      path:config.sourceDir,
      processOutput: function (assets) {
        let finalAssets = {};
        for (let a in assets) {
          if (a.length > 0) {
            for(let b in assets[a]) {
              assets[a][b] = assets[a][b].replace('auto/', '');
            }
            // console.log(assets[a]);
            finalAssets[a] = assets[a];
          }
        }
        return JSON.stringify(finalAssets, null, this.prettyPrint ? 2 : null);
      }
    })
    /**/
  ],
  externals: config.externals,
  module: {
    rules: [
      {
        enforce: 'pre',
        test: /\.js$/,
        loader: 'source-map-loader',
        exclude: [
        ]
      },
      {
        enforce: 'pre',
        test: /\.tsx?$/,
        use: 'source-map-loader'
      },
      {
        test: /\.tsx$/,
        loader: 'ts-loader',
        exclude: /node_modules/
      },
      {
        test: /\.ts$/i,
        use: [
          'ts-loader',
          '@aurelia/webpack-loader'
        ],
        exclude: /node_modules/
      },
      {
        test: /\.(ttf|eot|svg|woff|woff2)((\?|#)[a-z0-9]+)?$/,
        loader: 'file-loader',
        options: {
          // esModule: false,
          name: '[path][name].[ext]'
        }
      },
      {
        test: /\.(jpe?g|png|gif)$/,
        loader: 'file-loader',
        options: {
          // esModule: false,
          name: '[path][name].[ext]'
        }
      },
      {
        test: /\.s[ac]ss$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
            options: {
              publicPath: (resourcePath, context) => {
                return path.relative(path.dirname(resourcePath), context) + '/';
              }
            }
          },
          {
            loader: 'css-loader',
            options: {
              importLoaders: 2,
              esModule: false,
              modules: false
            }
          },
          {
            loader: 'postcss-loader',
            /*/
            options: {
                postcssOptions: {
                    ident: 'postcss',
                    plugins:[AutoprefixerPlugin, CssNano({preset: 'default'})],
                    minimize: true
                }
            }
            /**/
          },
          'sass-loader'
        ]
      },
      {
        test: /\.css$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
            options: {
              publicPath: (resourcePath, context) => {
                return path.relative(path.dirname(resourcePath), context) + '/';
              }
            }
          },
          { loader: 'css-loader', options: {
              importLoaders: 2,
              esModule: false,
              // modules: false
            } },
          {
            loader: 'postcss-loader',
            /*/
            options: {
                postcssOptions: {
                    ident: 'postcss',
                    plugins:[AutoprefixerPlugin, CssNano({preset: 'default'})],
                    minimize: true
                }
            }
            /**/
          },
        ]
      },
      {
        test: /\.html$/i,
        use: {
          loader: '@aurelia/webpack-loader',
          options: {
            // The other possible Shadow DOM mode is 'closed'.
            // If you turn on "closed" mode, there will be difficulty to perform e2e
            // tests (such as Cypress). Because shadowRoot is not accessible through
            // standard DOM APIs in "closed" mode.
            // defaultShadowOptions: { mode: 'open' }
          }
        },
        exclude: /node_modules/
      }
    ]
  },
  optimization: {
    removeEmptyChunks: true,
    runtimeChunk: {
      name: "manifest"
    },
    splitChunks: {
      hidePathInfo: true, // prevents the path from being used in the filename when using maxSize
      chunks: 'initial',
      cacheGroups: {
        default: false,
        vendors: { // picks up everything from node_modules as long as the sum of node modules is larger than minSize
          test: /[\\/]node_modules[\\/]/,
          name: 'vendors',
          priority: 19,
          enforce: true, // causes maxInitialRequests to be ignored, minSize still respected if specified in cacheGroup
          minSize: 1000 // use the default minSize
        },
        vendorsAsync: { // vendors async chunk, remaining asynchronously used node modules as single chunk file
          test: /[\\/]node_modules[\\/]/,
          name: 'vendors.async',
          chunks: 'async',
          priority: 9,
          reuseExistingChunk: true,
          minSize: 1000  // use smaller minSize to avoid too much potential bundle bloat due to module duplication.
        },
        commonsAsync: { // commons async chunk, remaining asynchronously used modules as single chunk file
          name: 'commons.async',
          minChunks: 2, // Minimum number of chunks that must share a module before splitting
          chunks: 'async',
          priority: 0,
          reuseExistingChunk: true,
          minSize: 1000  // use smaller minSize to avoid too much potential bundle bloat due to module duplication.
        }
        /*/
        commons: {
            test: /[\\/]node_modules[\\/](?!aurelia)/,
            name: "vendor",
            chunks: "all"
        }
        /**/
      }
    }
  },
  resolve: {
    alias: {
      // https://github.com/aurelia/dialog/issues/387
      // Uncomment next line if you had trouble to run aurelia-dialog on IE11
      // 'aurelia-dialog': path.resolve(__dirname, 'node_modules/aurelia-dialog/dist/umd/aurelia-dialog.js'),

      // https://github.com/aurelia/binding/issues/702
      // Enforce single aurelia-binding, to avoid v1/v2 duplication due to
      // out-of-date dependencies on 3rd party aurelia plugins
      // 'aurelia-binding': path.resolve(__dirname, 'node_modules/aurelia-binding')
    },
    extensions: ['.ts', '.js'],
    modules: [
      path.resolve(__dirname, config.sourceDir, config.subDirectories.sources, "app"),
      path.resolve(__dirname, config.sourceDir, config.subDirectories.sources),
      "node_modules"
    ].map(function(x){
      return path.resolve(x);
    })
  },
  target: 'web'
};

if (!prodFlag) {
  webpackConfig.devtool = 'source-map';
}
/*/
if (serviceWorker) {
    webpackConfig.plugins.unshift(        // new ServiceWorkerPlugin
        new ServiceWorkerWebpackPlugin({
            entry: serviceWorker,
            filename: config.assets.scripts +'/'+ config.serviceWorker,
        })
    );
}
/**/

module.exports = webpackConfig;