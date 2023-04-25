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
	InfoTooltip,
	SETTINGS_VALUE_LABEL,
	ATTRIBUTE_VALUE_DEFAULT,
	ATTRIBUTE_VALUE_EVERYONE,
	ATTRIBUTE_VALUE_SCHEMA_EDITORS,
} from '@graphqlapi/components';
import { getMarkdownContentOrUseDefault } from './markdown-loader';

const getViewBooleanLabel = ( value ) => value ? `✅ ${ __('Yes', 'graphql-api') }` : `❌ ${ __('No', 'graphql-api') }`
const getEditBooleanLabel = ( value ) => value ? __('Yes', 'graphql-api') : __('No', 'graphql-api')

const PersistedQueryEndpointOptions = ( props ) => {
	const {
		isSelected,
		className,
		setAttributes,
		attributes:
		{
			isEnabled,
			accessibleTo,
			acceptVariablesAsURLParams,
		}
	} = props;
	const options = [
		{
			label: SETTINGS_VALUE_LABEL,
			value: ATTRIBUTE_VALUE_DEFAULT,
		},
		{
			label: __('Schema editor users only', 'graphql-api'),
			value: ATTRIBUTE_VALUE_SCHEMA_EDITORS,
		},
		{
			label: __('Everyone', 'graphql-api'),
			value: ATTRIBUTE_VALUE_EVERYONE,
		},
	];
	const optionValues = options.map( option => option.value );
	const variablesAsURLParamsTitle = __('Accept variables as URL params?', 'graphql-api')
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
			<div className={ `${ className }__accessible_to` }>
				<em>{ __('Accessible to?', 'graphql-api') }</em>
				<InfoTooltip
					{ ...props }
					text={ __('Can everyone access the persisted query? Or only the schema editor users?', 'graphql-api') }
				/>
				{ !isSelected && (
					<>
						<br />
						{ ( accessibleTo == ATTRIBUTE_VALUE_DEFAULT || !optionValues.includes(accessibleTo) ) &&
							<span>🟣 { __('Default', 'graphql-api') }</span>
						}
						{ accessibleTo == ATTRIBUTE_VALUE_SCHEMA_EDITORS &&
							<span>🟡 { __('Accessible to Schema editor users only', 'graphql-api') }</span>
						}
						{ accessibleTo == ATTRIBUTE_VALUE_EVERYONE &&
							<span>🟢 { __('Accessible to Everyone', 'graphql-api') }</span>
						}
					</>
				) }
				{ isSelected &&
					<RadioControl
						{ ...props }
						options={ options }
						selected={ accessibleTo }
						onChange={ newValue => (
							setAttributes( {
								accessibleTo: newValue
							} )
						)}
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
		header: __('Persisted Query Options', 'graphql-api'),
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
] )( PersistedQueryEndpointOptions );
