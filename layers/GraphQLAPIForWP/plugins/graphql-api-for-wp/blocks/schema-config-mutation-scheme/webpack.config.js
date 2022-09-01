const config = require( '@wordpress/scripts/config/webpack.config' );

const path = require( 'path' );
config.resolve.alias['@moduleDocs'] = path.resolve(process.cwd(), '../../docs/modules/nested-mutations/')

config.module.rules.push(
	{
		test: /\.md$/,
		use: [
			{
				loader: "html-loader"
			},
			{
				loader: "markdown-loader"
			}
		]
	},
	{
		test: /\.(gif|png|jpe?g|svg)$/i,
		use: [
		  'file-loader',
		  {
			loader: 'image-webpack-loader',
			options: {
			  bypassOnDebug: true, // webpack@1.x
			  disable: true, // webpack@2.x and newer
			},
		  },
		],
	  }
);

module.exports = config;
