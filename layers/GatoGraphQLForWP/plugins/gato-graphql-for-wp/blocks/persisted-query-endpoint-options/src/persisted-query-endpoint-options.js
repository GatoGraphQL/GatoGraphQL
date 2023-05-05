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
} from '@graphqlapi/components';
import { getMarkdownContentOrUseDefault } from './markdown-loader';

const getViewBooleanLabel = ( value ) => value ? `✅ ${ __('Yes', 'gato-graphql') }` : `❌ ${ __('No', 'gato-graphql') }`
const getEditBooleanLabel = ( value ) => value ? __('Yes', 'gato-graphql') : __('No', 'gato-graphql')

const PersistedQueryEndpointOptions = ( props ) => {
	const {
		isSelected,
		className,
		setAttributes,
		attributes:
		{
			isEnabled,
			acceptVariablesAsURLParams,
		}
	} = props;
	const variablesAsURLParamsTitle = __('Accept variables as URL params?', 'gato-graphql')
	return (
		<>
			<div className={ `${ className }__enabled` }>
				<em>{ __('Enabled?', 'gato-graphql') }</em>
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
		</>
	);
}

export default compose( [
	withEditableOnFocus(),
	withState( {
		header: __('Persisted Query Options', 'gato-graphql'),
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
] )( PersistedQueryEndpointOptions );
