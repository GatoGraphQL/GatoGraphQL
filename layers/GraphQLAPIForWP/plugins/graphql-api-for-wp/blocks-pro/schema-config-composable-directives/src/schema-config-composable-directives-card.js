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
} from '@graphqlapi/components';
 
const SchemaConfigComposableDirectivesCard = ( props ) => {
	const description = __('Allow directives to nest and modify the behavior of other directives.', 'graphql-api');
	return (
		<em>{ description }</em>
	);
}

export default compose( [
	withEditableOnFocus(),
	withState( {
		header: __('🔒 Composable Directives', 'graphql-api'),
		className: 'graphql-api-composable-directives',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withPROCard(),
] )( SchemaConfigComposableDirectivesCard );