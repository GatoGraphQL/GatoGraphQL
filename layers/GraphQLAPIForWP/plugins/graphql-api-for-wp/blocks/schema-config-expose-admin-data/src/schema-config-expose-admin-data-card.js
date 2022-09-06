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

const SchemaConfigExposeAdminDataCard = ( props ) => {
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
			label: __('Expose “sensitive” data elements in the schema', 'graphql-api'),
			value: ATTRIBUTE_VALUE_ENABLED,
		},
		{
			label: __('Do not expose “sensitive” data elements', 'graphql-api'),
			value: ATTRIBUTE_VALUE_DISABLED,
		},
	];
	const optionValues = options.map( option => option.value );
	return (
		<>
			<em>{ __('Expose admin elements in the schema?', 'graphql-api') }</em>
			<InfoTooltip
				{ ...props }
				text={ __('Expose “sensitive” data elements in the GraphQL schema (such as field "Root.roles", input field "Root.posts(status:)", and others), which provide access to potentially private user data', 'graphql-api') }
			/>
			{ !isSelected && (
				<>
					<br />
					{ ( enabledConst == ATTRIBUTE_VALUE_DEFAULT || !optionValues.includes(enabledConst) ) &&
						<span>🟡 { __('Default', 'graphql-api') }</span>
					}
					{ enabledConst == ATTRIBUTE_VALUE_ENABLED &&
						<span>✅ { __('Expose “sensitive” data elements in the schema', 'graphql-api') }</span>
					}
					{ enabledConst == ATTRIBUTE_VALUE_DISABLED &&
						<span>❌ { __('Do not expose “sensitive” data elements', 'graphql-api') }</span>
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
		header: __('Expose Sensitive Data in the Schema', 'graphql-api'),
		className: 'graphql-api-schema-expose-admin-data',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withEditableOnFocus(),
	withCard(),
] )( SchemaConfigExposeAdminDataCard );