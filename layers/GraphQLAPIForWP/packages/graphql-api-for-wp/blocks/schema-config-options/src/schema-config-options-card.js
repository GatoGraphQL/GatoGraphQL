/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Card, CardHeader, CardBody, RadioControl, Notice } from '@wordpress/components';

/**
 * Internal dependencies
 */
import {
	SchemaModeControl,
	InfoTooltip,
	getEditableOnFocusComponentClass,
	SETTINGS_VALUE_LABEL,
} from '@graphqlapi/components';
import {
	ATTRIBUTE_VALUE_USE_NAMESPACING_DEFAULT,
	ATTRIBUTE_VALUE_USE_NAMESPACING_ENABLED,
	ATTRIBUTE_VALUE_USE_NAMESPACING_DISABLED,
} from './namespacing-values';
import {
	ATTRIBUTE_VALUE_MUTATION_SCHEME_DEFAULT,
	ATTRIBUTE_VALUE_MUTATION_SCHEME_STANDARD,
	ATTRIBUTE_VALUE_MUTATION_SCHEME_NESTED_WITH_REDUNDANT_ROOT_FIELDS,
	ATTRIBUTE_VALUE_MUTATION_SCHEME_NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS,
} from './mutation-scheme-values';

const SchemaConfigOptionsCard = ( props ) => {
	const {
		isSelected,
		className,
		setAttributes,
		attributes: {
			useNamespacing,
			mutationScheme,
		},
		isPublicPrivateSchemaEnabled = true,
		isSchemaNamespacingEnabled = true,
		isNestedMutationsEnabled = true,
	} = props;
	const componentClassName = `${ className } ${ getEditableOnFocusComponentClass(isSelected) }`;
	const namespacingOptions = [
		{
			label: SETTINGS_VALUE_LABEL,
			value: ATTRIBUTE_VALUE_USE_NAMESPACING_DEFAULT,
		},
		{
			label: __('Use namespacing', 'graphql-api'),
			value: ATTRIBUTE_VALUE_USE_NAMESPACING_ENABLED,
		},
		{
			label: __('Do not use namespacing', 'graphql-api'),
			value: ATTRIBUTE_VALUE_USE_NAMESPACING_DISABLED,
		},
	];
	const namespacingOptionValues = namespacingOptions.map( option => option.value );
	const mutationSchemeOptions = [
		{
			label: SETTINGS_VALUE_LABEL,
			value: ATTRIBUTE_VALUE_MUTATION_SCHEME_DEFAULT,
		},
		{
			label: __('Do not enable nested mutations', 'graphql-api'),
			value: ATTRIBUTE_VALUE_MUTATION_SCHEME_STANDARD,
		},
		{
			label: __('Enable nested mutations, keeping all mutation fields in the root', 'graphql-api'),
			value: ATTRIBUTE_VALUE_MUTATION_SCHEME_NESTED_WITH_REDUNDANT_ROOT_FIELDS,
		},
		{
			label: __('Enable nested mutations, removing the redundant mutation fields from the root', 'graphql-api'),
			value: ATTRIBUTE_VALUE_MUTATION_SCHEME_NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS,
		},
	];
	const mutationSchemeOptionValues = mutationSchemeOptions.map( option => option.value );
	return (
		<div className={ componentClassName }>
			<Card { ...props }>
				<CardHeader isShady>
					{ __('Options', 'graphql-api') }
				</CardHeader>
				<CardBody>
					{ ! isPublicPrivateSchemaEnabled && ! isSchemaNamespacingEnabled && ! isNestedMutationsEnabled && (
						<Notice status="warning" isDismissible={ false }>
							{ __('All options for the Schema Configuration are disabled', 'graphql-api') }
						</Notice>
					) }
					{ isPublicPrivateSchemaEnabled && (
						<div className={ `${ className }__schema_mode` }>
							<em>{ __('Public/Private Schema:', 'graphql-api') }</em>
							<InfoTooltip
								{ ...props }
								text={ __('Default: use value from Settings. Public: fields/directives are always visible. Private: fields/directives are hidden unless rules are satisfied.', 'graphql-api') }
							/>
							<SchemaModeControl
								{ ...props }
								attributeName="defaultSchemaMode"
							/>
						</div>
					) }
					{ isPublicPrivateSchemaEnabled && ( isSchemaNamespacingEnabled || isNestedMutationsEnabled ) && (
						<hr />
					) }
					{ isSchemaNamespacingEnabled && (
						<div className={ `${ className }__namespacing` }>
							<em>{ __('Namespace Types and Interfaces?', 'graphql-api') }</em>
							<InfoTooltip
								{ ...props }
								text={ __('Add a unique namespace to types and interfaces to avoid conflicts', 'graphql-api') }
							/>
							{ !isSelected && (
								<>
									<br />
									{ ( useNamespacing == ATTRIBUTE_VALUE_USE_NAMESPACING_DEFAULT || !namespacingOptionValues.includes(useNamespacing) ) &&
										<span>⭕️ { __('Default', 'graphql-api') }</span>
									}
									{ useNamespacing == ATTRIBUTE_VALUE_USE_NAMESPACING_ENABLED &&
										<span>✅ { __('Use namespacing', 'graphql-api') }</span>
									}
									{ useNamespacing == ATTRIBUTE_VALUE_USE_NAMESPACING_DISABLED &&
										<span>❌ { __('Do not use namespacing', 'graphql-api') }</span>
									}
								</>
							) }
							{ isSelected &&
								<RadioControl
									{ ...props }
									options={ namespacingOptions }
									selected={ useNamespacing }
									onChange={ newValue => (
										setAttributes( {
											useNamespacing: newValue
										} )
									)}
								/>
							}
						</div>
					) }
					{ isSchemaNamespacingEnabled && isNestedMutationsEnabled && (
						<hr />
					) }
					{ isNestedMutationsEnabled && (
						<div className={ `${ className }__nestedmutations` }>
							<em>{ __('Mutation Scheme', 'graphql-api') }</em>
							<InfoTooltip
								{ ...props }
								text={ __('Support nested mutations? (Add mutation fields on entities other than the root type)', 'graphql-api') }
							/>
							{ !isSelected && (
								<>
									<br />
									{ ( mutationScheme == ATTRIBUTE_VALUE_MUTATION_SCHEME_DEFAULT || !mutationSchemeOptionValues.includes(mutationScheme) ) &&
										<span>⭕️ { __('Default', 'graphql-api') }</span>
									}
									{ mutationScheme == ATTRIBUTE_VALUE_MUTATION_SCHEME_STANDARD &&
										<span>❌ { __('Do not enable nested mutations', 'graphql-api') }</span>
									}
									{ mutationScheme == ATTRIBUTE_VALUE_MUTATION_SCHEME_NESTED_WITH_REDUNDANT_ROOT_FIELDS &&
										<span>✅ { __('Enable nested mutations, keeping all mutation fields in the root', 'graphql-api') }</span>
									}
									{ mutationScheme == ATTRIBUTE_VALUE_MUTATION_SCHEME_NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS &&
										<span>✳️ { __('Enable nested mutations, removing the redundant mutation fields from the root', 'graphql-api') }</span>
									}
								</>
							) }
							{ isSelected &&
								<RadioControl
									{ ...props }
									options={ mutationSchemeOptions }
									selected={ mutationScheme }
									onChange={ newValue => (
										setAttributes( {
											mutationScheme: newValue
										} )
									)}
								/>
							}
						</div>
					) }
				</CardBody>
			</Card>
		</div>
	);
}

export default SchemaConfigOptionsCard;
