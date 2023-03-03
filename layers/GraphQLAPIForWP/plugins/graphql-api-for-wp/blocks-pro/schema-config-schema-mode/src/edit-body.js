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
} from '@graphqlapi/components';
 
/**
 * Define consts
 */
const title = __('Public/Private Schema', 'graphql-api');
const description = __('Define if the schema metadata is public, and everyone has access to it, or is private, and can be accessed only when the Access Control validations are satisfied.', 'graphql-api');

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
		className: 'graphql-api-pro-block',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withPROCard(),
] )( EditBody );