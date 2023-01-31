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
import { getCustomizableConfigurationComponentClass } from '../base-styles'

const withCustomizableConfiguration = () => createHigherOrderComponent(
	( WrappedComponent ) => ( props ) => {
		const {
			isSelected,
			className,
			setAttributes,
			attributes: {
				// `null` => `false`
				applyCustomizableConfiguration = false,
			},
		} = props;
		const componentClassName = `${ className }__customizable-configuration ${ getCustomizableConfigurationComponentClass(!! applyCustomizableConfiguration) }`;
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
			<div className={ componentClassName }>
				<div className="customizable-configuration-header">
					<em>{ __('Customize configuration, or use default from Settings?', 'graphql-api') }</em>
					<InfoTooltip
						{ ...props }
						text={ __('Either use the configuration defined on the Settings page, or use a custom configuration', 'graphql-api') }
					/>
					{ !isSelected && (
						<>
							<br />
							{ ( applyCustomizableConfiguration == ATTRIBUTE_VALUE_CUSTOMIZABLE_CONFIGURATION_IGNORE ) &&
								<span>🟡 { __('Use default configuration from Settings', 'graphql-api') }</span>
							}
							{ applyCustomizableConfiguration == ATTRIBUTE_VALUE_CUSTOMIZABLE_CONFIGURATION_APPLY &&
								<span>🟢 { __('Use custom configuration', 'graphql-api') }</span>
							}
						</>
					) }
					{ isSelected &&
						<RadioControl
							{ ...props }
							options={ options }
							selected={ applyCustomizableConfiguration }
							onChange={ newValue => (
								setAttributes( {
									applyCustomizableConfiguration: newValue
								} )
							)}
						/>
					}
				</div>
				<hr />
				<div className="customizable-configuration-body">
					<WrappedComponent
						{ ...props }
					/>
				</div>
			</div>
		);
	},
	'withCustomizableConfiguration'
);

export default withCustomizableConfiguration;
