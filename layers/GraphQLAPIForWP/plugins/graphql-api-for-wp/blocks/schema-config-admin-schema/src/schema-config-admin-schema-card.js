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

const SchemaConfigAdminSchemaCard = ( props ) => {
	const {
		isSelected,
		className,
		setAttributes,
		attributes: {
			enableAdminSchema,
		},
	} = props;
	const componentClassName = `${ className } ${ getEditableOnFocusComponentClass(isSelected) }`;
	const adminSchemaOptions = [
		{
			label: SETTINGS_VALUE_LABEL,
			value: ATTRIBUTE_VALUE_DEFAULT,
		},
		{
			label: __('Add "unrestricted" admin fields to the schema', 'graphql-api'),
			value: ATTRIBUTE_VALUE_ENABLED,
		},
		{
			label: __('Do not add admin fields', 'graphql-api'),
			value: ATTRIBUTE_VALUE_DISABLED,
		},
	];
	const adminSchemaOptionValues = adminSchemaOptions.map( option => option.value );
	return (
		<div className={ componentClassName }>
			<Card { ...props }>
				<CardHeader isShady>
					{ __('Schema for the Admin', 'graphql-api') }
				</CardHeader>
				<CardBody>
					<div className={ `${ className }__admin_schema` }>
						<em>{ __('Add admin fields to the schema?', 'graphql-api') }</em>
						<InfoTooltip
							{ ...props }
							text={ __('Add "unrestricted" fields to the GraphQL schema (such as "Root.unrestrictedPosts", "User.roles", and others), to be used by the admin only', 'graphql-api') }
						/>
						{ !isSelected && (
							<>
								<br />
								{ ( enableAdminSchema == ATTRIBUTE_VALUE_DEFAULT || !adminSchemaOptionValues.includes(enableAdminSchema) ) &&
									<span>⭕️ { __('Default', 'graphql-api') }</span>
								}
								{ enableAdminSchema == ATTRIBUTE_VALUE_ENABLED &&
									<span>✅ { __('Add "unrestricted" admin fields', 'graphql-api') }</span>
								}
								{ enableAdminSchema == ATTRIBUTE_VALUE_DISABLED &&
									<span>❌ { __('Do not add fields', 'graphql-api') }</span>
								}
							</>
						) }
						{ isSelected &&
							<RadioControl
								{ ...props }
								options={ adminSchemaOptions }
								selected={ enableAdminSchema }
								onChange={ newValue => (
									setAttributes( {
										enableAdminSchema: newValue
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

export default SchemaConfigAdminSchemaCard;
