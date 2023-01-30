/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';
import { RadioControl } from '@wordpress/components';
import { InfoTooltip } from '../info-tooltip';
import { __ } from '@wordpress/i18n';
import {
	ATTRIBUTE_VALUE_CUSTOMIZABLE_CONFIGURATION_IGNORE,
	ATTRIBUTE_VALUE_CUSTOMIZABLE_CONFIGURATION_APPLY,
} from './customizable-configuration-values';

const withCustomizableConfiguration = () => createHigherOrderComponent(
	( WrappedComponent ) => ( props ) => {
		const {
			isSelected,
			setAttributes,
			attributes: {
				useCustomizableConfiguration,
			},
		} = props;
		const options = [
			{
				label: __('Use default configuration from Settings', 'graphql-api'),
				value: ATTRIBUTE_VALUE_CUSTOMIZABLE_CONFIGURATION_IGNORE,
			},
			{
				label: __('Use custom configuration', 'graphql-api'),
				value: ATTRIBUTE_VALUE_CUSTOMIZABLE_CONFIGURATION_APPLY,
			},
		];
		const optionValues = options.map( option => option.value );
		return (
			<>
				<em>{ __('Customize configuration, or use default from Settings?', 'graphql-api') }</em>
				<InfoTooltip
					{ ...props }
					text={ __('Either use the configuration defined on the Settings page, or use a custom configuration', 'graphql-api') }
				/>
				{ !isSelected && (
					<>
						<br />
						{ ( useCustomizableConfiguration == ATTRIBUTE_VALUE_CUSTOMIZABLE_CONFIGURATION_IGNORE || !optionValues.includes(useCustomizableConfiguration) ) &&
							<span>🟡 { __('Use default configuration from Settings', 'graphql-api') }</span>
						}
						{ useCustomizableConfiguration == ATTRIBUTE_VALUE_CUSTOMIZABLE_CONFIGURATION_APPLY &&
							<span>🟢 { __('Use custom configuration', 'graphql-api') }</span>
						}
					</>
				) }
				{ isSelected &&
					<RadioControl
						{ ...props }
						options={ options }
						selected={ useCustomizableConfiguration }
						onChange={ newValue => (
							setAttributes( {
								useCustomizableConfiguration: newValue
							} )
						)}
					/>
				}
				<div className="customizable-configuration-inner">
					<WrappedComponent
						{ ...props }
					/>
				</div>
			</>
		);
	},
	'withCustomizableConfiguration'
);

export default withCustomizableConfiguration;
