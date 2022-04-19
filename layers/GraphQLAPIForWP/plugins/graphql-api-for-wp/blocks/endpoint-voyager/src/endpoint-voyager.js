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

const EndpointVoyager = ( props ) => {
	const {
		isSelected,
		className,
		setAttributes,
		attributes:
		{
			isEnabled,
		}
	} = props;
	return (
		<div className={ `${ className }__voyager_enabled` }>
			<em>{ __('Expose the Interactive Schema client?', 'graphql-api') }</em>
			<InfoTooltip
				{ ...props }
				text={ __('Publicly available under /{endpoint-slug}/?view=schema', 'graphql-api') }
			/>
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
	);
}

export default compose( [
	withState( {
		header: __('Interactive Schema', 'graphql-api'),
	} ),
	withEditableOnFocus(),
	withCard(),
] )( EndpointVoyager );
