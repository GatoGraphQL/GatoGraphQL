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
import {
	withCard,
	withEditableOnFocus,
	MarkdownInfoModalButton,
} from '@graphqlapi/components';
import { getMarkdownContentOrUseDefault } from './markdown-loader';

const getViewBooleanLabel = ( value ) => value ? `✅ ${ __('Yes', 'graphql-api') }` : `❌ ${ __('No', 'graphql-api') }`
const getEditBooleanLabel = ( value ) => value ? __('Yes', 'graphql-api') : __('No', 'graphql-api')

const PersistedQueryOptions = ( props ) => {
	const {
		isSelected,
		className,
		queryPostParent,
		setAttributes,
		attributes:
		{
			isEnabled,
			acceptVariablesAsURLParams,
			inheritQuery,
		}
	} = props;
	const variablesAsURLParamsTitle = __('Accept variables as URL params?', 'graphql-api')
	const inheritQueryTitle = __('Inherit query from ancestor(s)?', 'graphql-api')
	return (
		<>
			<div className={ `${ className }__enabled` }>
				<em>{ __('Enabled?', 'graphql-api') }</em>
				{ !isSelected && (
					<>
						<br />
						{ getViewBooleanLabel( isEnabled ) }
					</>
				) }
				{ isSelected &&
					<ToggleControl
						{ ...props }
						label={ getEditBooleanLabel( isEnabled ) }
						checked={ isEnabled }
						onChange={ newValue => setAttributes( {
							isEnabled: newValue,
						} ) }
					/>
				}
			</div>
			<hr />
			<div className={ `${ className }__variables_enabled` }>
				<em>{ variablesAsURLParamsTitle }</em>
				{ isSelected && (
					<MarkdownInfoModalButton
						title={ variablesAsURLParamsTitle }
						pageFilename="variables-as-url-params"
						getMarkdownContentCallback={ getMarkdownContentOrUseDefault }
					/>
				) }
				{ !isSelected && (
					<>
						<br />
						{ getViewBooleanLabel( acceptVariablesAsURLParams ) }
					</>
				) }
				{ isSelected &&
					<ToggleControl
						{ ...props }
						label={ getEditBooleanLabel( acceptVariablesAsURLParams ) }
						checked={ acceptVariablesAsURLParams }
						onChange={ newValue => setAttributes( {
							acceptVariablesAsURLParams: newValue,
						} ) }
					/>
				}
			</div>
			{/* If this post has a parent, then allow to inherit query/variables */ }
			{
				!! queryPostParent && (
					<>
						<hr />
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
					</>
				)
			}
		</>
	);
}

export default compose( [
	withState( {
		header: __('Options', 'graphql-api'),
	} ),
	withSelect( ( select ) => {
		const { getEditedPostAttribute } = select(
			'core/editor'
		);
		return {
			queryPostParent: getEditedPostAttribute( 'parent' ),
		};
	} ),
	withEditableOnFocus(),
	withCard(),
] )( PersistedQueryOptions );
