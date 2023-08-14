/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { RadioControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import {
    OPTION_DEFAULT_SCHEMA_EXPOSURE,
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
		defaultValue = OPTION_DEFAULT_SCHEMA_EXPOSURE,
	} = props;
	const schemaExposure = attributes[ attributeName ] || defaultValue;
	const options = [
		{
			label: defaultLabel,
			value: OPTION_DEFAULT_SCHEMA_EXPOSURE,
		},
		{
			label: __('Do not expose', 'gatographql'),
			value: DO_NOT_EXPOSE,
		},
		{
			label: __('Expose under the Root type only', 'gatographql'),
			value: EXPOSE_IN_ROOT_TYPE_ONLY,
		},
		{
			label: __('Expose under all types', 'gatographql'),
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
					{ (schemaExposure == OPTION_DEFAULT_SCHEMA_EXPOSURE || !optionValues.includes(schemaExposure) ) &&
						<span>🟡 { __('Default', 'gatographql') }</span>
					}
					{ (schemaExposure == DO_NOT_EXPOSE) &&
						<span>⚫️ { __('Do not expose', 'gatographql') }</span>
					}
					{ (schemaExposure == EXPOSE_IN_ROOT_TYPE_ONLY) &&
						<span>🔵 { __('Expose under the Root type only', 'gatographql') }</span>
					}
					{ (schemaExposure == EXPOSE_IN_ALL_TYPES) &&
						<span>⚪️ { __('Expose under all types', 'gatographql') }</span>
					}
				</div>
			) }
		</>
	);
}

export default GlobalFieldsControl;
