/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';
import { ToggleControl } from '@wordpress/components';
import { InfoTooltip } from '../info-tooltip';
import { __ } from '@wordpress/i18n';
import { getCustomizableConfigurationComponentClass } from '../base-styles'
import './style.scss';

const withCustomizableConfiguration = () => createHigherOrderComponent(
	( WrappedComponent ) => ( props ) => {
		const {
			isSelected,
			className,
			setAttributes,
			attributes: {
				// `null` => `false`
				customizeConfiguration = false,
			},
		} = props;
		const componentClassName = `${ className }__customizable-configuration ${ getCustomizableConfigurationComponentClass(customizeConfiguration) }`;
		return (
			<div className={ componentClassName }>
				<div className="customizable-configuration-header">
					{ ! isSelected && ! customizeConfiguration && (
						<span>🟡 { __('Use configuration from Settings', 'graphql-api') }</span>
					) }
					{ isSelected && (
						<>
							<em>{ __('Customize configuration?', 'graphql-api') }</em>
							<InfoTooltip
								{ ...props }
								text={ __('The configuration items below can be customized for endpoints using this Schema Configuration. Otherwise, the general configuration (defined on the Settings page) will be used.', 'graphql-api') }
							/>
							<ToggleControl
								{ ...props }
								label={ __('Use custom configuration', 'graphql-api') }
								checked={ customizeConfiguration }
								onChange={ newValue => (
									setAttributes( {
										customizeConfiguration: newValue
									} )
								)}
							/>
							<hr/>
						</>
					) }
				</div>
				{ ( isSelected || customizeConfiguration ) && (
					<div className="customizable-configuration-body">
						<WrappedComponent
							{ ...props }
						/>
					</div>
				) }
			</div>
		);
	},
	'withCustomizableConfiguration'
);

export default withCustomizableConfiguration;
