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

const SchemaConfigExposeAdminDataCard = ( props ) => {
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
			label: __('Expose "admin" elements in the schema', 'graphql-api'),
			value: ATTRIBUTE_VALUE_ENABLED,
		},
		{
			label: __('Do not expose admin elements', 'graphql-api'),
			value: ATTRIBUTE_VALUE_DISABLED,
		},
	];
	const optionValues = options.map( option => option.value );
	return (
		<div className={ componentClassName }>
			<Card { ...props }>
				<CardHeader isShady>
					{ __('Schema Expose Admin Data', 'graphql-api') }
				</CardHeader>
				<CardBody>
					<div className={ `${ className }__admin_schema` }>
						<em>{ __('Expose admin elements in the schema?', 'graphql-api') }</em>
						<InfoTooltip
							{ ...props }
							text={ __('Expose "admin" elements in the GraphQL schema (such as field "Root.roles", input field "Root.posts(status:)", and others), which provide access to private data', 'graphql-api') }
						/>
						{ !isSelected && (
							<>
								<br />
								{ ( enabledConst == ATTRIBUTE_VALUE_DEFAULT || !optionValues.includes(enabledConst) ) &&
									<span>🟡 { __('Default', 'graphql-api') }</span>
								}
								{ enabledConst == ATTRIBUTE_VALUE_ENABLED &&
									<span>✅ { __('Expose "admin" elements in the schema', 'graphql-api') }</span>
								}
								{ enabledConst == ATTRIBUTE_VALUE_DISABLED &&
									<span>❌ { __('Do not expose admin elements', 'graphql-api') }</span>
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

export default SchemaConfigExposeAdminDataCard;
