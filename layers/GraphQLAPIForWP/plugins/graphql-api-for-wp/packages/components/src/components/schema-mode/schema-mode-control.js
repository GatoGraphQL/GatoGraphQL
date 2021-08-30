/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { RadioControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import {
	DEFAULT_SCHEMA_MODE,
	PUBLIC_SCHEMA_MODE,
	PRIVATE_SCHEMA_MODE
} from './schema-modes';
import {
	SETTINGS_VALUE_LABEL,
} from '../../default-configuration';

const SchemaModeControl = ( props ) => {
	const {
		className,
		isSelected,
		setAttributes,
		attributes,
		attributeName,
		defaultLabel = SETTINGS_VALUE_LABEL,
		defaultValue = DEFAULT_SCHEMA_MODE,
	} = props;
	const schemaMode = attributes[ attributeName ] || defaultValue;
	const options = [
		{
			label: defaultLabel,
			value: DEFAULT_SCHEMA_MODE,
		},
		{
			label: __('Public', 'graphql-api'),
			value: PUBLIC_SCHEMA_MODE,
		},
		{
			label: __('Private', 'graphql-api'),
			value: PRIVATE_SCHEMA_MODE,
		},
	];
	const optionValues = options.map( option => option.value );
	return (
		<>
			{ isSelected &&
				<RadioControl
					{ ...props }
					options={ options }
					selected={ schemaMode }
					onChange={ newValue => (
						setAttributes( {
							[ attributeName ]: newValue
						} )
					)}
				/>
			}
			{ !isSelected && (
				<div className={ className+'__read'}>
					{ (schemaMode == DEFAULT_SCHEMA_MODE || !optionValues.includes(schemaMode) ) &&
						<span>🟡 { __('Default', 'graphql-api') }</span>
					}
					{ (schemaMode == PUBLIC_SCHEMA_MODE) &&
						<span>⚪️ { __('Public', 'graphql-api') }</span>
					}
					{ (schemaMode == PRIVATE_SCHEMA_MODE) &&
						<span>⚫️ { __('Private', 'graphql-api') }</span>
					}
				</div>
			) }
		</>
	);
}

export default SchemaModeControl;
