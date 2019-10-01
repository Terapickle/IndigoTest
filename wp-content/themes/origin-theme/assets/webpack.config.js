const path = require("path");
const { argv } = require("yargs");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const FriendlyErrorsWebpackPlugin = require("friendly-errors-webpack-plugin");
const OptimizeCssAssetsPlugin = require("optimize-css-assets-webpack-plugin");
const LiveReloadPlugin = require("webpack-livereload-plugin");
const jsonImporter = require("node-sass-json-importer");
const BrowserSyncPlugin = require("browser-sync-webpack-plugin");

const isDev = process.env.NODE_ENV !== "production";

const config = {
  mode: isDev ? "development" : "production",
  devtool: "cheap-source-map",
  stats: {
    hash: false,
    version: false,
    timings: false,
    children: false,
    errors: false,
    errorDetails: false,
    warnings: false,
    chunks: false,
    modules: false,
    reasons: false,
    source: false,
    publicPath: false
  },
  externals: {
    jquery: 'window.jQuery'
  },
  entry: {
    main: ["./assets/js/main.js", "./assets/sass/main.scss"]
  },
  output: {
    filename: "[name].js",
    path: path.resolve(__dirname, "dist")
  },
  module: {
    rules: [
      {
        test: /\.jsx?$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: {
            presets: ["@babel/preset-env"]
          }
        }
      },
      {
        test: /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader,
          "css-loader",
          {
            loader: "postcss-loader",
            options: {
              plugins: () => [
                require("autoprefixer")({
                  browsers: [
                    "last 2 versions",
                    "not IE <= 10",
                    "not Safari <= 9",
                    "> 10%",
                    "not BlackBerry > 0",
                    "not ExplorerMobile > 0",
                    "not Baidu > 0",
                    "not OperaMobile > 0",
                    "not Android > 0",
                  ]
                }),
                require("postcss-flexbugs-fixes")
              ]
            }
          },
          {
            loader: "sass-loader",
            options: {
              importer: jsonImporter()
            }
          }
        ]
      },
      {
        test: /(\.(png|jpe?g|gif)$|^((?!font).)*\.svg$)/,
        use: {
          loader: "file-loader",
          options: {
            name: "images/[name].[ext]?[hash]"
          }
        }
      },
      {
        test: /(\.(woff2?|ttf|eot|otf)$|font.*\.svg$)/,
        use: {
          loader: "file-loader",
          options: {
            name: "fonts/[name].[ext]?[hash]"
          }
        }
      }
    ]
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: "[name].css",
      chunkFilename: "[id].css"
    }),
    new FriendlyErrorsWebpackPlugin()
  ]
};

if (!!argv.watch) {

  // To use browser-sync, comment out this next line:
  // ---
  config.plugins.push(new LiveReloadPlugin());

  // To use browser-sync, uncomment this block:
  // ---
  // config.plugins.push(new BrowserSyncPlugin({
  //   host: 'localhost',
  //   port: 3000,
  //   proxy: 'http://demo.wp',
  //   files: [
  //     path.resolve(__dirname, "../**/*.php")
  //   ]
  // }));
}

if (!isDev) {
  config.plugins.push(
    new OptimizeCssAssetsPlugin({
      cssProcessorPluginOptions: {
        preset: ["default", { discardComments: { removeAll: true } }]
      }
    })
  );
}

module.exports = config;
