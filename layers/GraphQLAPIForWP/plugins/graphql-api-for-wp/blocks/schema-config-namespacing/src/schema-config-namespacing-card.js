/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Card, CardHeader, CardBody, RadioControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import {
	InfoTooltip,
	getEditableOnFocusComponentClass,
	SETTINGS_VALUE_LABEL,
	ATTRIBUTE_VALUE_DEFAULT,
	ATTRIBUTE_VALUE_ENABLED,
	ATTRIBUTE_VALUE_DISABLED,
} from '@graphqlapi/components';

const SchemaConfigNamespacingCard = ( props ) => {
	const {
		isSelected,
		className,
		setAttributes,
		attributes: {
			enabledConst,
		},
	} = props;
	const componentClassName = `${ className } ${ getEditableOnFocusComponentClass(isSelected) }`;
	const options = [
		{
			label: SETTINGS_VALUE_LABEL,
			value: ATTRIBUTE_VALUE_DEFAULT,
		},
		{
			label: __('Use namespacing', 'graphql-api'),
			value: ATTRIBUTE_VALUE_ENABLED,
		},
		{
			label: __('Do not use namespacing', 'graphql-api'),
			value: ATTRIBUTE_VALUE_DISABLED,
		},
	];
	const optionValues = options.map( option => option.value );
	return (
		<div className={ componentClassName }>
			<Card { ...props }>
				<CardHeader isShady>
					{ __('Namespacing', 'graphql-api') }
				</CardHeader>
				<CardBody>
					<div className={ `${ className }__namespacing` }>
						<em>{ __('Namespace Types?', 'graphql-api') }</em>
						<InfoTooltip
							{ ...props }
							text={ __('Add a unique namespace to types to avoid conflicts', 'graphql-api') }
						/>
						{ !isSelected && (
							<>
								<br />
								{ ( enabledConst == ATTRIBUTE_VALUE_DEFAULT || !optionValues.includes(enabledConst) ) &&
									<span>🟡 { __('Default', 'graphql-api') }</span>
								}
								{ enabledConst == ATTRIBUTE_VALUE_ENABLED &&
									<span>✅ { __('Use namespacing', 'graphql-api') }</span>
								}
								{ enabledConst == ATTRIBUTE_VALUE_DISABLED &&
									<span>❌ { __('Do not use namespacing', 'graphql-api') }</span>
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
					</div>
				</CardBody>
			</Card>
		</div>
	);
}

export default SchemaConfigNamespacingCard;
