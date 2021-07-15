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
} from '@graphqlapi/components';
import {
	ATTRIBUTE_VALUE_MUTATION_SCHEME_DEFAULT,
	ATTRIBUTE_VALUE_MUTATION_SCHEME_STANDARD,
	ATTRIBUTE_VALUE_MUTATION_SCHEME_NESTED_WITH_REDUNDANT_ROOT_FIELDS,
	ATTRIBUTE_VALUE_MUTATION_SCHEME_NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS,
} from './mutation-scheme-values';

const SchemaConfigMutationSchemeCard = ( props ) => {
	const {
		isSelected,
		className,
		setAttributes,
		attributes: {
			mutationScheme,
		},
	} = props;
	const componentClassName = `${ className } ${ getEditableOnFocusComponentClass(isSelected) }`;
	const options = [
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
	const optionValues = options.map( option => option.value );
	return (
		<div className={ componentClassName }>
			<Card { ...props }>
				<CardHeader isShady>
					{ __('Mutation Scheme', 'graphql-api') }
				</CardHeader>
				<CardBody>
					<div className={ `${ className }__nestedmutations` }>
						<em>{ __('Support nested mutations?', 'graphql-api') }</em>
						<InfoTooltip
							{ ...props }
							text={ __('Add mutation fields on entities other than the root type?', 'graphql-api') }
						/>
						{ !isSelected && (
							<>
								<br />
								{ ( mutationScheme == ATTRIBUTE_VALUE_MUTATION_SCHEME_DEFAULT || !optionValues.includes(mutationScheme) ) &&
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
								options={ options }
								selected={ mutationScheme }
								onChange={ newValue => (
									setAttributes( {
										mutationScheme: newValue
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

export default SchemaConfigMutationSchemeCard;
