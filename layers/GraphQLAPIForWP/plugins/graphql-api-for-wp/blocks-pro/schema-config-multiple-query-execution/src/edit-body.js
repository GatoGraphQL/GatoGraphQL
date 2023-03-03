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
const title = __('Multiple Query Execution', 'graphql-api');
const description = __('Multiple queries are combined together, and executed as a single operation, reusing their state and their data, and improving the performance of the application.', 'graphql-api');

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