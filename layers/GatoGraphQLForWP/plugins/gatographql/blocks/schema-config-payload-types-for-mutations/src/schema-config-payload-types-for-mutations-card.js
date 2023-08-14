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

const SchemaConfigPayloadTypesForMutationsCard = ( props ) => {
	const {
		isSelected,
		setAttributes,
		attributes: {
			enabledConst,
		},
	} = props;
	const options = [
		{
			label: SETTINGS_VALUE_LABEL,
			value: ATTRIBUTE_VALUE_DEFAULT,
		},
		{
			label: __('Use payload types for mutations', 'gatographql'),
			value: ATTRIBUTE_VALUE_ENABLED,
		},
		{
			label: __('Do not use payload types for mutations (i.e. return the mutated entity)', 'gatographql'),
			value: ATTRIBUTE_VALUE_DISABLED,
		},
	];
	const optionValues = options.map( option => option.value );
	return (
		<>
			<em>{ __('Use payload types for mutations in the schema?', 'gatographql') }</em>
			<InfoTooltip
				{ ...props }
				text={ __('Either have mutations return a payload object type, or directly the mutated entity.', 'gatographql') }
			/>
			{ !isSelected && (
				<>
					<br />
					{ ( enabledConst == ATTRIBUTE_VALUE_DEFAULT || !optionValues.includes(enabledConst) ) &&
						<span>🟡 { __('Default', 'gatographql') }</span>
					}
					{ enabledConst == ATTRIBUTE_VALUE_ENABLED &&
						<span>✅ { __('Use payload types for mutations', 'gatographql') }</span>
					}
					{ enabledConst == ATTRIBUTE_VALUE_DISABLED &&
						<span>❌ { __('Do not use payload types for mutations (i.e. return the mutated entity)', 'gatographql') }</span>
					}
				</>
			) }
			{ isSelected &&
				<RadioControl
					{ ...props }
					options={ options }
					selected={ enabledConst }
					onChange={ newValue => (
						setAttributes( {
							enabledConst: newValue
						} )
					)}
				/>
			}
		</>
	);
}

export default compose( [
	withEditableOnFocus(),
	withState( {
		header: __('Payload Types for Mutations', 'gatographql'),
		className: 'gatographql-mutations',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
] )( SchemaConfigPayloadTypesForMutationsCard );
