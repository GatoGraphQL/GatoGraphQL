/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
import { InfoModalButton } from '../info-modal';

/**
 * These docs are kept in English. When the admin language is not English, prepend
 * a notice (styled with the existing `.doc-config-highlight` class) linking to the
 * same docs on the localized website (the user's language as a subdomain of the
 * configured Gato GraphQL website, e.g. https://es.gatographql.com). The locale is
 * read from the admin's `<html lang>`, and the website URL from the global set in
 * PHP (Plugin::enqueueDocsWebsiteURLData). The notice text is translated via the
 * .json pack — the same string as the PHP doc notice, so the translation is shared.
 */
const DEFAULT_DOC_LANG = 'en';

const getEnglishDocNoticeHTML = () => {
	const lang = ( document.documentElement.lang || '' ).split( '-' )[ 0 ].toLowerCase();
	const websiteURL = window.gatoGraphQLDocsWebsiteURL;
	if ( ! lang || lang === DEFAULT_DOC_LANG || ! websiteURL ) {
		return '';
	}
	// Prefix the language as a subdomain: https://gatographql.com -> https://es.gatographql.com
	const localizedURL = websiteURL.replace( /^(https?:\/\/)/, `$1${ lang }.` );
	let host = localizedURL;
	try {
		host = new URL( localizedURL ).host;
	} catch ( e ) {}
	const link = `<a href="${ localizedURL }" target="_blank">${ host }</a>`;
	return `<div class="doc-config-highlight">${ sprintf(
		__( 'This documentation is in English. You can read it in your language at %s.', 'gatographql' ),
		link
	) }</div>`;
};

const MarkdownInfoModalButton = ( props ) => {
	const {
		pageFilename,
		getMarkdownContentCallback,
	} = props;
	const [ page, setPage ] = useState([]);
	useEffect(() => {
		getMarkdownContentCallback( pageFilename ).then( value => {
			setPage( getEnglishDocNoticeHTML() + value )
		});
	}, []);
	return (
		<InfoModalButton
			{ ...props }
			content={ page }
		/>
	);
};
export default MarkdownInfoModalButton;
