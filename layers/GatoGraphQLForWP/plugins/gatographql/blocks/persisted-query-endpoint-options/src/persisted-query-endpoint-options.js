/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
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

const PersistedQueryEndpointOptions = ( props ) => {
	const {
		isSelected,
		className,
		setAttributes,
		attributes:
		{
			isEnabled,
			doURLParamsOverrideGraphQLVariables,
		}
	} = props;
	const variablesAsURLParamsTitle = __('Do URL params override variables?', 'gatographql')
	return (
		<>
			<div className={ `${ className }__enabled` }>
				<em>{ __('Enabled?', 'gatographql') }</em>
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
						pageFilename="do-url-params-override-graphql-variables"
						getMarkdownContentCallback={ getMarkdownContentOrUseDefault }
					/>
				) }
				{ !isSelected && (
					<>
						<br />
						{ getViewBooleanLabel( doURLParamsOverrideGraphQLVariables ) }
					</>
				) }
				{ isSelected &&
					<ToggleControl
						{ ...props }
						label={ getEditBooleanLabel( doURLParamsOverrideGraphQLVariables ) }
						checked={ doURLParamsOverrideGraphQLVariables }
						onChange={ newValue => setAttributes( {
							doURLParamsOverrideGraphQLVariables: newValue,
						} ) }
					/>
				}
			</div>
		</>
	);
}

export default compose( [
	withEditableOnFocus(),
	withState( {
		header: __('Persisted Query Options', 'gatographql'),
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
] )( PersistedQueryEndpointOptions );
