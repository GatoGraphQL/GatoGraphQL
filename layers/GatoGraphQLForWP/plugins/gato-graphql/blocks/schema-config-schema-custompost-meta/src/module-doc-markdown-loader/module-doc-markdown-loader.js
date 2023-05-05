/**
 * Path to load the lazy chunks on the fly
 * @see https://v4.webpack.js.org/guides/public-path/#on-the-fly
 */
__webpack_public_path__ = window.graphqlApiSchemaConfigSchemaCustompostMeta?.publicPath;

/**
 * Read the content from a Markdown file in a given language, and return it as HTML
 *
 * @param {string} lang The language folder from which to retrieve the Markdown file
 */
const getModuleDocMarkdownContent = ( lang ) => {
	return import( /* webpackChunkName: "moduleDocs/[request]" */ `@moduleDocs/${ lang }.md` )
		.then(obj => obj.default)
}

/**
 * Read the content from a Markdown file in a given language or, if it doesn't exist,
 * in a default language (which for sure exists), and return it as HTML
 *
 * @param {string} defaultLang The default language. If none provided, get it from the localized data
 * @param {string|null} lang The language to translate to. If none provided, get it from the localized data
 */
const getModuleDocMarkdownContentOrUseDefault = ( defaultLang, lang ) => {
	lang = lang || window.graphqlApiSchemaConfigSchemaCustompostMeta?.localeLang
	defaultLang = defaultLang || window.graphqlApiSchemaConfigSchemaCustompostMeta?.defaultLang
	return getModuleDocMarkdownContent( lang )
		.catch(err => getModuleDocMarkdownContent( defaultLang ) )
}
export default getModuleDocMarkdownContentOrUseDefault;
