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
	withCard,
	withEditableOnFocus,
	GoProLink,
} from '@graphqlapi/components';
 
const SchemaConfigComposableDirectivesCard = ( props ) => {
	// const {
	// 	isSelected,
	// } = props;
	const description = __('Allow directives to nest and modify the behavior of other directives.', 'graphql-api');
	return (
		<em>{ description }</em>
		// <>
		// 	{ !isSelected && (
		// 		<>
		// 			<span>{ description }</span>
		// 		</>
		// 	) }
		// 	{ isSelected && (
		// 		<>
		// 			<span>{ description }</span>
		// 			<GoProLink
		// 				className="button button-secondary"
		// 			/>
		// 		</>
		// 	) }
		// </>
	);
}

export default compose( [
	withEditableOnFocus(),
	withState( {
		header: __('Composable Directives', 'graphql-api'),
		className: 'graphql-api-composable-directives',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
] )( SchemaConfigComposableDirectivesCard );