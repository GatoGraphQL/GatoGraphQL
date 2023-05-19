/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { RadioControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import {
    DEFAULT_SCHEMA_EXPOSURE,
    DO_NOT_EXPOSE,
    EXPOSE_IN_ROOT_TYPE_ONLY,
    EXPOSE_IN_ALL_TYPES
} from './global-fields-schema-exposure';
import {
	SETTINGS_VALUE_LABEL,
} from '@gatographql/components';

const GlobalFieldsControl = ( props ) => {
	const {
		className,
		isSelected,
		setAttributes,
		attributes,
		attributeName,
		defaultLabel = SETTINGS_VALUE_LABEL,
		defaultValue = DEFAULT_SCHEMA_EXPOSURE,
	} = props;
	const schemaExposure = attributes[ attributeName ] || defaultValue;
	const options = [
		{
			label: defaultLabel,
			value: DEFAULT_SCHEMA_EXPOSURE,
		},
		{
			label: __('Do not expose', 'gato-graphql'),
			value: DO_NOT_EXPOSE,
		},
		{
			label: __('Expose under the Root type only', 'gato-graphql'),
			value: EXPOSE_IN_ROOT_TYPE_ONLY,
		},
		{
			label: __('Expose under all types', 'gato-graphql'),
			value: EXPOSE_IN_ALL_TYPES,
		},
	];
	const optionValues = options.map( option => option.value );
	return (
		<>
			{ isSelected &&
				<RadioControl
					{ ...props }
					options={ options }
					selected={ schemaExposure }
					onChange={ newValue => (
						setAttributes( {
							[ attributeName ]: newValue
						} )
					)}
				/>
			}
			{ !isSelected && (
				<div className={ className+'__read'}>
					{ (schemaExposure == DEFAULT_SCHEMA_EXPOSURE || !optionValues.includes(schemaExposure) ) &&
						<span>🟡 { __('Default', 'gato-graphql') }</span>
					}
					{ (schemaExposure == DO_NOT_EXPOSE) &&
						<span>⚫️ { __('Do not expose', 'gato-graphql') }</span>
					}
					{ (schemaExposure == EXPOSE_IN_ROOT_TYPE_ONLY) &&
						<span>🔵 { __('Expose under the Root type only', 'gato-graphql') }</span>
					}
					{ (schemaExposure == EXPOSE_IN_ALL_TYPES) &&
						<span>⚪️ { __('Expose under all types', 'gato-graphql') }</span>
					}
				</div>
			) }
		</>
	);
}

export default GlobalFieldsControl;
