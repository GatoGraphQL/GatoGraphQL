/**
 * Define constants
 */
const ANY_IMPLICIT_FEATURE = 'any-built-in-scalar';

const IMPLICIT_FEATURES_DOCS_PATH = `docs/implicit-features/`;
const PluginMetadataHelpers = require("../../block-helpers/plugin-metadata.helpers")
const GITHUB_BASE_URL = PluginMetadataHelpers.getGitHubRepoDocsRootPathURL()
const BASE_URL = process.env.NODE_ENV === 'production'
	? `${ GITHUB_BASE_URL }/${ IMPLICIT_FEATURES_DOCS_PATH }/${ ANY_IMPLICIT_FEATURE }`
	: null;

const config = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );
config.resolve.alias['@implicitFeaturesDocs'] = path.resolve(process.cwd(), `../../${ IMPLICIT_FEATURES_DOCS_PATH }`)

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
// Needed for webpack v4, so commented out for webpack v5
// config.output.jsonpFunction = 'webpackJsonpGatoGraphQLSchemaConfigurationAdditionalDocumentation';
// ------------------------------------------------------

module.exports = config;
