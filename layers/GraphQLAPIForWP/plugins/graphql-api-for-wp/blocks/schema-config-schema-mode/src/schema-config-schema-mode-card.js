/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Card, CardHeader, CardBody } from '@wordpress/components';

/**
 * Internal dependencies
 */
import {
	SchemaModeControl,
	InfoTooltip,
	getEditableOnFocusComponentClass,
} from '@graphqlapi/components';

const SchemaConfigSchemaModeCard = ( props ) => {
	const {
		isSelected,
		className,
	} = props;
	const componentClassName = `${ className } ${ getEditableOnFocusComponentClass(isSelected) }`;
	return (
		<div className={ componentClassName }>
			<Card { ...props }>
				<CardHeader isShady>
					{ __('Public/Private Schema Mode', 'graphql-api') }
				</CardHeader>
				<CardBody>
					<div className={ `${ className }__schema_mode` }>
						<em>{ __('Visibility of the schema metadata:', 'graphql-api') }</em>
						<InfoTooltip
							{ ...props }
							text={ __('Default: use value from Settings. Public: fields/directives are always visible. Private: fields/directives are hidden unless rules are satisfied.', 'graphql-api') }
						/>
						<SchemaModeControl
							{ ...props }
							attributeName="defaultSchemaMode"
						/>
					</div>
				</CardBody>
			</Card>
		</div>
	);
}

export default SchemaConfigSchemaModeCard;
