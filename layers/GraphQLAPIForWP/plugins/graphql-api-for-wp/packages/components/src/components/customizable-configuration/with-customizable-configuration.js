/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';
import { RadioControl } from '@wordpress/components';
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
		const options = [
			{
				label: __('Use configuration from Settings', 'graphql-api'),
				value: 'false',
			},
			{
				label: __('Use custom configuration', 'graphql-api'),
				value: 'true',
			},
		];
		return (
			<div className={ componentClassName }>
				<div className="customizable-configuration-header">
					<em>{ __('Customize configuration, or use default from Settings?', 'graphql-api') }</em>
					<InfoTooltip
						{ ...props }
						text={ __('For specific entries (see inputs below), either use the configuration defined on the Settings page or a custom configuration', 'graphql-api') }
					/>
					{ !isSelected && (
						<>
							<br />
							{ ! customizeConfiguration &&
								<span>🟡 { __('Use configuration from Settings', 'graphql-api') }</span>
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
							selected={ customizeConfiguration ? 'true' : 'false' }
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
