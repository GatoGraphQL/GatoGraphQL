/**
 * Path to load the lazy chunks on the fly
 * @see https://v4.webpack.js.org/guides/public-path/#on-the-fly
 */
__webpack_public_path__ = window.schemaConfigurationEditorComponents?.publicPath;

/**
 * Read the content from a Markdown file in a given language, and return it as HTML
 *
 * @param {string} lang The language folder from which to retrieve the Markdown file
 */
const getImplicitFeaturesDocMarkdownContent = ( fileName, lang ) => {
	return import( /* webpackChunkName: "implicitFeaturesDocs/[request]" */ `@implicitFeaturesDocs/${ fileName }/${ lang }.md` )
		.then(obj => obj.default)
}

/**
 * Read the content from a Markdown file in a given language or, if it doesn't exist,
 * in a default language (which for sure exists), and return it as HTML
 *
 * @param {string} fileName The Markdown file name
 * @param {string} defaultLang The default language. If none provided, get it from the localized data
 * @param {string|null} lang The language to translate to. If none provided, get it from the localized data
 */
const getImplicitFeaturesDocMarkdownContentOrUseDefault = ( fileName, defaultLang, lang ) => {
	lang = lang || window.schemaConfigurationEditorComponents?.localeLang
	defaultLang = defaultLang || window.schemaConfigurationEditorComponents?.defaultLang
	return getImplicitFeaturesDocMarkdownContent( lang )
		.catch(err => getImplicitFeaturesDocMarkdownContent( fileName, defaultLang ) )
}
export default getImplicitFeaturesDocMarkdownContentOrUseDefault;
