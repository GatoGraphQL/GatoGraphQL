/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { RadioControl } from '@wordpress/components';
import { compose, withState } from '@wordpress/compose';

/**
 * Internal dependencies
 */
import { getModuleDocMarkdownContentOrUseDefault } from './module-doc-markdown-loader';
import {
	InfoTooltip,
	SETTINGS_VALUE_LABEL,
	ATTRIBUTE_VALUE_DEFAULT,
	ATTRIBUTE_VALUE_ENABLED,
	ATTRIBUTE_VALUE_DISABLED,
	withCard,
	withEditableOnFocus,
} from '@gatographql/components';

const EnableDisableOption = ( props ) => {
	const {
		isSelected,
		setAttributes,
		attributes,
		attributeName,
		label,
		tooltip,
		enabledLabel,
		disabledLabel,
	} = props;
	const value = attributes[ attributeName ];
	const options = [
		{
			label: SETTINGS_VALUE_LABEL,
			value: ATTRIBUTE_VALUE_DEFAULT,
		},
		{
			label: enabledLabel,
			value: ATTRIBUTE_VALUE_ENABLED,
		},
		{
			label: disabledLabel,
			value: ATTRIBUTE_VALUE_DISABLED,
		},
	];
	const optionValues = options.map( option => option.value );
	return (
		<div className="gatographql-graphql-spec-compliance-option">
			<em>{ label }</em>
			<InfoTooltip
				{ ...props }
				text={ tooltip }
			/>
			{ !isSelected && (
				<>
					<br />
					{ ( value == ATTRIBUTE_VALUE_DEFAULT || !optionValues.includes(value) ) &&
						<span>🟡 { __('Default', 'gatographql') }</span>
					}
					{ value == ATTRIBUTE_VALUE_ENABLED &&
						<span>✅ { enabledLabel }</span>
					}
					{ value == ATTRIBUTE_VALUE_DISABLED &&
						<span>❌ { disabledLabel }</span>
					}
				</>
			) }
			{ isSelected &&
				<RadioControl
					options={ options }
					selected={ value }
					onChange={ newValue => (
						setAttributes( {
							[ attributeName ]: newValue
						} )
					)}
				/>
			}
		</div>
	);
}

const SchemaConfigGraphQLSpecComplianceCard = ( props ) => {
	return (
		<>
			<EnableDisableOption
				{ ...props }
				attributeName="propagateNullToNearestNullableParent"
				label={ __('Pass a null up to the nearest nullable parent?', 'gatographql') }
				tooltip={ __('When a non-null field is null, pass the null up to its nearest nullable parent (or to "data")', 'gatographql') }
				enabledLabel={ __('Pass the null up', 'gatographql') }
				disabledLabel={ __('Print the field as null', 'gatographql') }
			/>
			<hr />
			<EnableDisableOption
				{ ...props }
				attributeName="coerceOutputValues"
				label={ __('Coerce scalar and enum values?', 'gatographql') }
				tooltip={ __('Coerce the values of the built-in scalars and of enums to their type (eg: print an ID as a string)', 'gatographql') }
				enabledLabel={ __('Coerce the values', 'gatographql') }
				disabledLabel={ __('Print the values as they are', 'gatographql') }
			/>
			<hr />
			<EnableDisableOption
				{ ...props }
				attributeName="addResponsePathToErrors"
				label={ __('Add the response path to errors?', 'gatographql') }
				tooltip={ __('Add entry "path" to every error, with its position in the response', 'gatographql') }
				enabledLabel={ __('Add the response path', 'gatographql') }
				disabledLabel={ __('Do not add the response path', 'gatographql') }
			/>
		</>
	);
}

export default compose( [
	withEditableOnFocus(),
	withState( {
		header: __('GraphQL Spec Compliance', 'gatographql'),
		className: 'gatographql-graphql-spec-compliance',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
] )( SchemaConfigGraphQLSpecComplianceCard );
