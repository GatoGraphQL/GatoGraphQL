/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';
import { RadioControl } from '@wordpress/components';
import { InfoTooltip } from '../info-tooltip';
import { __ } from '@wordpress/i18n';
import { getCustomizableConfigurationComponentClass } from '../base-styles'

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
		const options = [
			{
				label: __('Use default configuration from Settings', 'graphql-api'),
				value: 'false',
			},
			{
				label: __('Use custom configuration', 'graphql-api'),
				value: 'true',
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
							{ ! customizeConfiguration &&
								<span>🟡 { __('Use default configuration from Settings', 'graphql-api') }</span>
							}
							{ customizeConfiguration &&
								<span>🟢 { __('Use custom configuration', 'graphql-api') }</span>
							}
						</>
					) }
					{ isSelected &&
						<RadioControl
							{ ...props }
							options={ options }
							selected={ customizeConfiguration }
							onChange={ newValue => (
								setAttributes( {
									customizeConfiguration: newValue === true || newValue === 'true'
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
