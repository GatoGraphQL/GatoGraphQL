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
} from '@gatographql/components';
 
const SchemaConfigComposableDirectivesCard = ( props ) => {
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
			label: __('Enable', 'gatographql'),
			value: ATTRIBUTE_VALUE_ENABLED,
		},
		{
			label: __('Disable', 'gatographql'),
			value: ATTRIBUTE_VALUE_DISABLED,
		},
	];
	const optionValues = options.map( option => option.value );
	return (
		<>
			<em>{ __('Enable composable directives?', 'gatographql') }</em>
			<InfoTooltip
				{ ...props }
				text={ __('Add composable directives to the schema, which are directives that can nest and modify the behavior of other directives', 'gatographql') }
			/>
			{ !isSelected && (
				<>
					<br />
					{ ( enabledConst == ATTRIBUTE_VALUE_DEFAULT || !optionValues.includes(enabledConst) ) &&
						<span>🟡 { __('Default', 'gatographql') }</span>
					}
					{ enabledConst == ATTRIBUTE_VALUE_ENABLED &&
						<span>✅ { __('Enabled', 'gatographql') }</span>
					}
					{ enabledConst == ATTRIBUTE_VALUE_DISABLED &&
						<span>❌ { __('Disabled', 'gatographql') }</span>
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
	withEditableOnFocus(),
	withState( {
		header: __('Composable Directives', 'gatographql'),
		className: 'gatographql-composable-directives',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
] )( SchemaConfigComposableDirectivesCard );