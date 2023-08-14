/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { withSelect } from '@wordpress/data';
import { compose, withState } from '@wordpress/compose';
import { ToggleControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { getModuleDocMarkdownContentOrUseDefault } from './module-doc-markdown-loader';
import {
	withCard,
	withEditableOnFocus,
	MarkdownInfoModalButton,
} from '@gatographql/components';
import { getMarkdownContentOrUseDefault } from './markdown-loader';

const getViewBooleanLabel = ( value ) => value ? `✅ ${ __('Yes', 'gatographql') }` : `❌ ${ __('No', 'gatographql') }`
const getEditBooleanLabel = ( value ) => value ? __('Yes', 'gatographql') : __('No', 'gatographql')

const PersistedQueryEndpointAPIHierarchy = ( props ) => {
	const {
		isSelected,
		className,
		queryPostParent,
		setAttributes,
		attributes:
		{
			inheritQuery,
		}
	} = props;
	const inheritQueryTitle = __('Inherit query from ancestor(s)?', 'gatographql')
	return (
		<>
			{/* If this post has a parent, then allow to inherit query/variables */ }
			{
				! queryPostParent && (
					<div className={ `${ className }__inherit_query` }>
						<em>{ __('This section is enabled when selecting an item from the "Parent GraphQL persisted query" dropdown, in the Page Attributes box.', 'gatographql') }</em>
					</div>
				)
			}
			{
				!! queryPostParent && (
					<div className={ `${ className }__inherit_query` }>
						<em>{ inheritQueryTitle }</em>
						{ isSelected && (
							<MarkdownInfoModalButton
								title={ inheritQueryTitle }
								pageFilename="inherit-query"
								getMarkdownContentCallback={ getMarkdownContentOrUseDefault }
							/>
						) }
						{ !isSelected && (
							<>
								<br />
								{ getViewBooleanLabel( inheritQuery ) }
							</>
						) }
						{ isSelected &&
							<ToggleControl
								{ ...props }
								label={ getEditBooleanLabel( inheritQuery ) }
								checked={ inheritQuery }
								onChange={ newValue => setAttributes( {
									inheritQuery: newValue,
								} ) }
							/>
						}
					</div>
				)
			}
		</>
	);
}

export default compose( [
	withEditableOnFocus(),
	withState( {
		header: __('API Hierarchy', 'gatographql'),
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withSelect( ( select ) => {
		const { getEditedPostAttribute } = select(
			'core/editor'
		);
		return {
			queryPostParent: getEditedPostAttribute( 'parent' ),
		};
	} ),
	withCard(),
] )( PersistedQueryEndpointAPIHierarchy );
