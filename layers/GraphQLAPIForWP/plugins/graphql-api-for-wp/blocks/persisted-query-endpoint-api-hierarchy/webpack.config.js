/**
 * Define constants
 */
const MODULE = 'api-hierarchy';
const MODULE_DOCS_PATH = `docs/modules/${ MODULE }/`;
const BASE_URL = process.env.NODE_ENV === 'production'
	? 'https://raw.githubusercontent.com/GraphQLAPI/graphql-api-for-wp/master'
	: 'https://raw.githubusercontent.com/leoloso/PoP/master/layers/GraphQLAPIForWP/plugins/graphql-api-for-wp'

const config = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );
config.resolve.alias['@moduleDocs'] = path.resolve(process.cwd(), `../../${ MODULE_DOCS_PATH }`)

/**
 * Resolve folder docs/ as @docs
 */
config.resolve.alias['@docs'] = path.resolve(process.cwd(), 'docs/')

// ---------------------------------------------
// Consider for webpack v5, to generate the bundle containing all docs per language
// (as `docs-en.js`, `docs-es.js`, etc) and not lazy-load them
// langs = ['en'/*, 'es' */]
// langs.forEach( lang => config.entry[`docs-${ lang }`] = path.resolve( process.cwd(), `docs/${ lang }`, 'index.js' ) )
// ---------------------------------------------

// ---------------------------------------------
// Uncomment for webpack v5, to not duplicate the content of the docs inside build/index.js
// config.entry.index = {
// 	import: path.resolve( process.cwd(), 'src', 'index.js' ),
// 	dependOn: 'docs'
// }
// config.entry.docs = langs.map( lang => `docs-${ lang }` )
// ---------------------------------------------

/**
 * Add support for additional file types
 */
config.module.rules.push(
	/**
	 * Markdown
	 */
	{
		test: /\.md$/,
		use: [
			{
				loader: "html-loader"
			},
			{
				loader: "markdown-loader",
				options: {
					baseUrl: `${ BASE_URL }/${ MODULE_DOCS_PATH }`
				}
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

/**
 * Because the block and the package have their own webpack configuration,
 * they must provide a unique name for the global scope (which is used to lazy-load chunks),
 * otherwise it throws a JS error when loading blocks compiled with `npm run build`
 * @see https://github.com/WordPress/gutenberg/issues/23607
 * @see https://webpack.js.org/configuration/output/#outputjsonpfunction
 */
// ------------------------------------------------------
config.output.jsonpFunction = 'webpackJsonpGraphQLAPIPersistedQueryEndpointAPIHierarchy';
// ------------------------------------------------------

module.exports = config;
