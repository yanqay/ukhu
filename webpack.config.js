const path = require("path");
const webpack = require("webpack");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");
const ManifestPlugin = require("webpack-manifest-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");
const TerserJSPlugin = require("terser-webpack-plugin");

module.exports = {
  entry: {
    "layout/backoffice/scripts": "./resources/layout/backoffice/scripts.js", /* scripts.js contains generic js functionality */
    "home/index": "./resources/home/index.js", /* index.js already includes css files inside */
    "layout/backoffice/app": "./resources/layout/backoffice/app.js", /* app.js already includes css files inside */

    "layout/frontpage/app": "./resources/layout/frontpage/app.js", /* app.js already includes css files inside */
  },
  output: {
    path: path.resolve(__dirname, "public/assets"),
    publicPath: "/assets/",
  },
  optimization: {
    minimizer: [new TerserJSPlugin({}), new OptimizeCSSAssetsPlugin({})],
  },
  performance: {
    maxEntrypointSize: 1024000,
    maxAssetSize: 1024000,
  },
  module: {
    rules: [
      {
        /* load style css files */
        test: /\.css$/,
        use: [MiniCssExtractPlugin.loader, "css-loader"],
      },
      {
        /* load bootstrap icons (as fonts) https://icons.getbootstrap.com/  */
        test: /\.woff(2)?(\?v=[0-9]\.[0-9]\.[0-9])?$/,
        include: path.resolve(__dirname, './node_modules/bootstrap-icons/font/fonts'),
        use: {
            loader: 'file-loader',
            options: {
                name: '[name].[ext]',
                outputPath: 'webfonts',
                publicPath: '/assets/webfonts',
            },
        }
      },
    ],
  },
  plugins: [
    new CleanWebpackPlugin(),
    new ManifestPlugin(),
    new MiniCssExtractPlugin({
      ignoreOrder: false,
    }),
  ],
  watchOptions: {
    ignored: ["./node_modules/"],
  },
  mode: "development",
};
