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
	ATTRIBUTE_VALUE_PAYLOAD_TYPE_DEFAULT,
	ATTRIBUTE_VALUE_USE_PAYLOAD_TYPES_FOR_MUTATIONS,
	ATTRIBUTE_VALUE_USE_AND_QUERY_PAYLOAD_TYPES_FOR_MUTATIONS,
	ATTRIBUTE_VALUE_DO_NOT_USE_PAYLOAD_TYPES_FOR_MUTATIONS,
} from './mutation-payload-type-options';
import {
	InfoTooltip,
	SETTINGS_VALUE_LABEL,
	withCard,
	withEditableOnFocus,
} from '@gatographql/components';

const SchemaConfigPayloadTypesForMutationsCard = ( props ) => {
	const {
		isSelected,
		setAttributes,
		attributes: {
			usePayloadType,
		},
	} = props;
	const options = [
		{
			label: SETTINGS_VALUE_LABEL,
			value: ATTRIBUTE_VALUE_PAYLOAD_TYPE_DEFAULT,
		},
		{
			label: __('Use payload types for mutations', 'gatographql'),
			value: ATTRIBUTE_VALUE_USE_PAYLOAD_TYPES_FOR_MUTATIONS,
		},
		{
			label: __('Use payload types for mutations, and add fields to query those payload objects', 'gatographql'),
			value: ATTRIBUTE_VALUE_USE_AND_QUERY_PAYLOAD_TYPES_FOR_MUTATIONS,
		},
		{
			label: __('Do not use payload types for mutations (i.e. return the mutated entity)', 'gatographql'),
			value: ATTRIBUTE_VALUE_DO_NOT_USE_PAYLOAD_TYPES_FOR_MUTATIONS,
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
					{ ( usePayloadType == ATTRIBUTE_VALUE_PAYLOAD_TYPE_DEFAULT || !optionValues.includes(usePayloadType) ) &&
						<span>🟡 { __('Default', 'gatographql') }</span>
					}
					{ usePayloadType == ATTRIBUTE_VALUE_USE_PAYLOAD_TYPES_FOR_MUTATIONS &&
						<span>✅ { __('Use payload types for mutations', 'gatographql') }</span>
					}
					{ usePayloadType == ATTRIBUTE_VALUE_USE_AND_QUERY_PAYLOAD_TYPES_FOR_MUTATIONS &&
						<span>✳️ { __('Use payload types for mutations, and add fields to query those payload objects', 'gatographql') }</span>
					}
					{ usePayloadType == ATTRIBUTE_VALUE_DO_NOT_USE_PAYLOAD_TYPES_FOR_MUTATIONS &&
						<span>❌ { __('Do not use payload types for mutations (i.e. return the mutated entity)', 'gatographql') }</span>
					}
				</>
			) }
			{ isSelected &&
				<RadioControl
					{ ...props }
					options={ options }
					selected={ usePayloadType }
					onChange={ newValue => (
						setAttributes( {
							usePayloadType: newValue
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
