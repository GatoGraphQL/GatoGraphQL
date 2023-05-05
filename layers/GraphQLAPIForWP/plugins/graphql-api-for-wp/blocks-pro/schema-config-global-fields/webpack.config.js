/**
 * Define constants
 */
const MODULE = 'global-fields';

const MODULE_DOCS_PATH = `docs-pro/modules/${ MODULE }/`;
const NPM_PACKAGE_VERSION = process.env.npm_package_version;
const PACKAGE_TAG = NPM_PACKAGE_VERSION.endsWith('-dev') ? 'master' : NPM_PACKAGE_VERSION;
const GITHUB_BASE_URL = `https://raw.githubusercontent.com/leoloso/PoP/${ PACKAGE_TAG }/layers/GraphQLAPIForWP/plugins/graphql-api-for-wp`
const BASE_URL = process.env.NODE_ENV === 'production'
	? `${ GITHUB_BASE_URL }/${ MODULE_DOCS_PATH }`
	: null;

const config = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );
config.resolve.alias['@moduleDocs'] = path.resolve(process.cwd(), `../../${ MODULE_DOCS_PATH }`)

const highlight = require('highlight.js');

config.module.rules.push(
	{
		test: /\.md$/,
		use: [
			{
				loader: "html-loader"
			},
			{
				loader: "markdown-loader",
				options: {
					baseUrl: BASE_URL,
					langPrefix: 'hljs language-',
					highlight: (code, lang) => {
					    if (!lang || ['text', 'literal', 'nohighlight'].includes(lang)) {
						    return `<pre class="hljs">${code}</pre>`;
					    }
					    const html = highlight.highlight(lang, code).value;
					    return `<span class="hljs">${html}</span>`;
					},
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
	},
	{
	    test: /\.gql$/i,
	    use: 'raw-loader',
	},
);

/**
 * Because the block and the package have their own webpack configuration,
 * they must provide a unique name for the global scope (which is used to lazy-load chunks),
 * otherwise it throws a JS error when loading blocks compiled with `npm run build`
 * @see https://github.com/WordPress/gutenberg/issues/23607
 * @see https://webpack.js.org/configuration/output/#outputjsonpfunction
 */
// ------------------------------------------------------
config.output.jsonpFunction = 'webpackJsonpGraphQLAPIProSchemaConfigGlobalFields';
// ------------------------------------------------------

module.exports = config;
