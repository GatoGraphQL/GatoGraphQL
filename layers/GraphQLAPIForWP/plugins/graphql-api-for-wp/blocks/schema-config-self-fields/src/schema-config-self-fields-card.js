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
	InfoTooltip,
	SETTINGS_VALUE_LABEL,
	ATTRIBUTE_VALUE_DEFAULT,
	ATTRIBUTE_VALUE_ENABLED,
	ATTRIBUTE_VALUE_DISABLED,
	withCard,
	withEditableOnFocus,
} from '@graphqlapi/components';

const SchemaConfigSelfFieldsCard = ( props ) => {
	const {
		isSelected,
		setAttributes,
		attributes: {
			enabledConst,
		},
	} = props;
	const options = [
		{
			label: SETTINGS_VALUE_LABEL,
			value: ATTRIBUTE_VALUE_DEFAULT,
		},
		{
			label: __('Expose "self" fields in the schema', 'graphql-api-pro'),
			value: ATTRIBUTE_VALUE_ENABLED,
		},
		{
			label: __('Do not expose self fields', 'graphql-api-pro'),
			value: ATTRIBUTE_VALUE_DISABLED,
		},
	];
	const optionValues = options.map( option => option.value );
	return (
		<>
			<em>{ __('Expose self fields in the schema?', 'graphql-api-pro') }</em>
			<InfoTooltip
				{ ...props }
				text={ __('Expose "self" fields in the GraphQL schema (such as "Post.self" and "User.self"), which can help give a particular shape to the GraphQL response', 'graphql-api-pro') }
			/>
			{ !isSelected && (
				<>
					<br />
					{ ( enabledConst == ATTRIBUTE_VALUE_DEFAULT || !optionValues.includes(enabledConst) ) &&
						<span>🟡 { __('Default', 'graphql-api-pro') }</span>
					}
					{ enabledConst == ATTRIBUTE_VALUE_ENABLED &&
						<span>✅ { __('Expose "self" fields', 'graphql-api-pro') }</span>
					}
					{ enabledConst == ATTRIBUTE_VALUE_DISABLED &&
						<span>❌ { __('Do not expose self fields', 'graphql-api-pro') }</span>
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
		</>
	);
}

export default compose( [
	withState( {
		header: __('Self Fields', 'graphql-api-pro'),
		className: 'graphql-api-schema-self-fields',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withEditableOnFocus(),
	withCard(),
] )( SchemaConfigSelfFieldsCard );
