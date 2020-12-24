
/**
 * Read the content from a Markdown file in a given language, and return it as HTML
 *
 * @param {string} fileName The Markdown file name
 * @param {string} lang The language folder from which to retrieve the Markdown file
 */
const getMarkdownContent = ( fileName, lang ) => {
	return import( /* webpackMode: "eager" */ `@docs/${ lang }/${ fileName }.md` )
		.then(obj => obj.default)
		// .then( ( { default: _ } ) )
}

/**
 * Read the content from a Markdown file in a given language or, if it doesn't exist,
 * in a default language (which for sure exists), and return it as HTML
 *
 * @param {string} fileName The Markdown file name
 * @param {string|null} defaultLang The default language. If none provided, get it from the localized data
 * @param {string|null} lang The language to translate to. If none provided, get it from the localized data
 */
const getMarkdownContentOrUseDefault = ( fileName, defaultLang, lang ) => {
	/**
	 * If lang or defaultLang are empty, then get them from the localized data under `window.persistedQueryEditorComponents`
	 * Property "localeLang": same as constant GraphQLAPI\GraphQLAPI\General\DocumentationConstants::LOCALE_LANG
	 * Property "defaultLang": same as constants: same as constant GraphQLAPI\GraphQLAPI\General\DocumentationConstants::DEFAULT_LANG
	 */
	lang = lang || window.persistedQueryEditorComponents?.localeLang
	defaultLang = defaultLang || window.persistedQueryEditorComponents?.defaultLang
	return getMarkdownContent( fileName, lang )
		.catch(err => getMarkdownContent( fileName, defaultLang ) )
}
export default getMarkdownContentOrUseDefault;
