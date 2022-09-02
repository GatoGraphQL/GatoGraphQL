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
	ATTRIBUTE_VALUE_MUTATION_SCHEME_DEFAULT,
	ATTRIBUTE_VALUE_MUTATION_SCHEME_STANDARD,
	ATTRIBUTE_VALUE_MUTATION_SCHEME_NESTED_WITH_REDUNDANT_ROOT_FIELDS,
	ATTRIBUTE_VALUE_MUTATION_SCHEME_NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS,
} from './mutation-scheme-values';
import {
	InfoTooltip,
	SETTINGS_VALUE_LABEL,
	withCard,
	withEditableOnFocus,
} from '@graphqlapi/components';

const SchemaConfigMutationSchemeCard = ( props ) => {
	const {
		isSelected,
		setAttributes,
		attributes: {
			mutationScheme,
		},
	} = props;
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
		<>
			<em>{ __('Support nested mutations?', 'graphql-api') }</em>
			<InfoTooltip
				{ ...props }
				text={ __('Add mutation fields on entities other than the root type?', 'graphql-api') }
			/>
			{ !isSelected && (
				<>
					<br />
					{ ( mutationScheme == ATTRIBUTE_VALUE_MUTATION_SCHEME_DEFAULT || !optionValues.includes(mutationScheme) ) &&
						<span>🟡 { __('Default', 'graphql-api') }</span>
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
		</>
	);
}

export default compose( [
	withState( {
		header: __('Mutation Scheme', 'graphql-api'),
		className: 'graphql-api-mutation-scheme',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withEditableOnFocus(),
	withCard(),
] )( SchemaConfigMutationSchemeCard );