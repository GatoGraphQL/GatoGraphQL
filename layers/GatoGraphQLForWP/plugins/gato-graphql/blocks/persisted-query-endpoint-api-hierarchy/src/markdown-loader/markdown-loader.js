/**
 * Path to load the lazy chunks on the fly
 * @see https://v4.webpack.js.org/guides/public-path/#on-the-fly
 */
__webpack_public_path__ = window.gatoGraphqlPersistedQueryEndpointApiHierarchy?.publicPath;

/**
 * Read the content from a Markdown file in a given language, and return it as HTML
 *
 * @param {string} fileName The Markdown file name
 * @param {string} lang The language folder from which to retrieve the Markdown file
 */
const getMarkdownContent = ( fileName, lang ) => {
	return import( /* webpackChunkName: "docs/[request]" */ `@docs/${ fileName }/${ lang }.md` )
		.then(obj => obj.default)
	// ---------------------------------------------
	// Maybe uncomment for webpack v5, to not lazy load the docs,
	// but to load a single bundle with all files per language in advance
	// return import( /* webpackMode: "eager" */ `@docs/${ fileName }/${ lang }.md` )
	// 	.then(obj => obj.default)
	// ---------------------------------------------
}

/**
 * Read the content from a Markdown file in a given language or, if it doesn't exist,
 * in a default language (which for sure exists), and return it as HTML
 *
 * @param {string} fileName The Markdown file name
 * @param {string} defaultLang The default language. If none provided, get it from the localized data
 * @param {string|null} lang The language to translate to. If none provided, get it from the localized data
 */
const getMarkdownContentOrUseDefault = ( fileName, defaultLang, lang ) => {
	lang = lang || window.gatoGraphqlPersistedQueryEndpointApiHierarchy?.localeLang
	defaultLang = defaultLang || window.gatoGraphqlPersistedQueryEndpointApiHierarchy?.defaultLang
	return getMarkdownContent( fileName, lang )
		.catch(err => getMarkdownContent( fileName, defaultLang ) )
}
export default getMarkdownContentOrUseDefault;
