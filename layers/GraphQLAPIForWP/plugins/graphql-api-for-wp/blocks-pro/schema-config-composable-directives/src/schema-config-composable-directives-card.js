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
 
const title = __('Composable Directives', 'graphql-api');
const description = __('Allow directives to nest and modify the behavior of other directives.', 'graphql-api');

const SchemaConfigComposableDirectivesCard = ( props ) => {
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
		className: 'graphql-api-composable-directives',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withPROCard(),
] )( SchemaConfigComposableDirectivesCard );