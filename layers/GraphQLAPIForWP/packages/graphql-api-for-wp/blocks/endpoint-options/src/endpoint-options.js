/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { compose, withState } from '@wordpress/compose';
import { ToggleControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import {
	withCard,
	withEditableOnFocus,
	InfoTooltip,
} from '@graphqlapi/components';

const EndpointOptions = ( props ) => {
	const {
		isSelected,
		className,
		setAttributes,
		attributes:
		{
			isEnabled,
			isGraphiQLEnabled,
			isVoyagerEnabled,
		}
	} = props;
	return (
		<>
			<div className={ `${ className }__enabled` }>
				<em>{ __('Enabled?', 'graphql-api') }</em>
				{ !isSelected && (
					<>
						<br />
						{ isEnabled ? `✅ ${ __('Yes', 'graphql-api') }` : `❌ ${ __('No', 'graphql-api') }` }
					</>
				) }
				{ isSelected &&
					<ToggleControl
						{ ...props }
						label={ isEnabled ? __('Yes', 'graphql-api') : __('No', 'graphql-api') }
						checked={ isEnabled }
						onChange={ newValue => setAttributes( {
							isEnabled: newValue,
						} ) }
					/>
				}
			</div>
			{ window.graphqlApiEndpointOptions.isGraphiQLEnabled && (
				<>
					<hr />
					<div className={ `${ className }__graphiql_enabled` }>
						<em>{ __('Expose GraphiQL client?', 'graphql-api') }</em>
						<InfoTooltip
							{ ...props }
							text={ __('Publicly available under /{endpoint-slug}/?view=graphiql', 'graphql-api') }
						/>
						{ !isSelected && (
							<>
								<br />
								{ isGraphiQLEnabled ? `✅ ${ __('Yes', 'graphql-api') }` : `❌ ${ __('No', 'graphql-api') }` }
							</>
						) }
						{ isSelected &&
							<ToggleControl
								{ ...props }
								label={ isGraphiQLEnabled ? __('Yes', 'graphql-api') : __('No', 'graphql-api') }
								checked={ isGraphiQLEnabled }
								onChange={ newValue => setAttributes( {
									isGraphiQLEnabled: newValue,
								} ) }
							/>
						}
					</div>
				</>
			) }
			{ window.graphqlApiEndpointOptions.isVoyagerEnabled && (
				<>
					<hr />
					<div className={ `${ className }__voyager_enabled` }>
						<em>{ __('Expose the Interactive Schema client?', 'graphql-api') }</em>
						<InfoTooltip
							{ ...props }
							text={ __('Publicly available under /{endpoint-slug}/?view=schema', 'graphql-api') }
						/>
						{ !isSelected && (
							<>
								<br />
								{ isVoyagerEnabled ? `✅ ${ __('Yes', 'graphql-api') }` : `❌ ${ __('No', 'graphql-api') }` }
							</>
						) }
						{ isSelected &&
							<ToggleControl
								{ ...props }
								label={ isVoyagerEnabled ? __('Yes', 'graphql-api') : __('No', 'graphql-api') }
								checked={ isVoyagerEnabled }
								onChange={ newValue => setAttributes( {
									isVoyagerEnabled: newValue,
								} ) }
							/>
						}
					</div>
				</>
			) }
		</>
	);
}

export default compose( [
	withState( {
		header: __('Options', 'graphql-api'),
	} ),
	withEditableOnFocus(),
	withCard(),
] )( EndpointOptions );
