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
const title = __('Cache Control', 'graphql-api');
const description = __('Provide HTTP caching for persisted queries, caching their response via the `Cache-Control` header.', 'graphql-api');

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