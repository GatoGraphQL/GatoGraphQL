/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { compose, withState } from '@wordpress/compose';

/**
 * Internal dependencies
 */
import { getModuleDocMarkdownContentOrUseDefault } from './module-doc-markdown-loader';
import {
	GraphAPIPROBlock,
	withPROCard,
	withEditableOnFocus,
} from '@gatographql/components';
 
/**
 * Define consts
 */
const title = __('Send HTTP Request Fields', 'gato-graphql');
const description = __('Addition of fields to execute HTTP requests against a webserver and fetch their response.', 'gato-graphql');

const EditBody = ( props ) => {
	return (
		<GraphAPIPROBlock
			{ ...props }
			title = { title }
			description = { description }
			getMarkdownContentCallback = { getModuleDocMarkdownContentOrUseDefault }
		/>
	);
}

export default compose( [
	withEditableOnFocus(),
	withState( {
		header: title,
		className: 'gato-graphql-pro-block',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withPROCard(),
] )( EditBody );