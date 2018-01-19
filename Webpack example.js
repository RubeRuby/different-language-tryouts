const ExtractTextPlugin  = require('extract-text-webpack-plugin');
const HtmlWebpackPlugin  = require('html-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');

module.exports = {
	context: __dirname + '/app',
	entry: './app.module.js',
	output: {
		path: __dirname + '/build',
		filename: 'bundle.js'
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: [{
					loader: 'ng-annotate-loader'
				}, {
					loader: 'babel-loader',
					options: {
						presets: ['env']
					}
				}]
			},
			{
				test: /\.scss$/,
				use: ExtractTextPlugin.extract({
                   use: ['css-loader', 'sass-loader'],
                   fallback: "style-loader"
               })
			},
			{
				test: /\.html$/,
				use: 'html-loader'
			}
		]
	},
	plugins: [
		new ExtractTextPlugin({
              filename: "[name].[contenthash].css",
              disable: process.env.NODE_ENV === "development"
          }),
		new HtmlWebpackPlugin({
	          template: __dirname + '/index.html'
	      }),
		new CleanWebpackPlugin(['build'])
	],
	resolve: {
		alias: {
			Styles: __dirname + '/assets/styles'
		}
	}
};