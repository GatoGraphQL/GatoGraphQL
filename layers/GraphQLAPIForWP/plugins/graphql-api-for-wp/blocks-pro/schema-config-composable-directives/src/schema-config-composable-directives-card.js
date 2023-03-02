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
	withPROCard,
	withEditableOnFocus,
	MarkdownInfoModalButton,
} from '@graphqlapi/components';
 
const title = __('Composable Directives', 'graphql-api');
const description = __('Allow directives to nest and modify the behavior of other directives.', 'graphql-api');

const SchemaConfigComposableDirectivesCard = ( props ) => {
	return (
		<>
			<em>{ description }</em>
			<MarkdownInfoModalButton
				{ ...props }
				title = { __(`Documentation for: "${ title }"`, 'graphql-api') }
				getMarkdownContentCallback = { getModuleDocMarkdownContentOrUseDefault }
				text = { __('View details', 'graphql-api') }
				// icon = { null }
				variant = ""
				isSmall = { false }
			/>
		</>
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