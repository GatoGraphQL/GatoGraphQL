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
} from '@graphqlapi/components';

const CustomEndpointOptions = ( props ) => {
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
	);
}

export default compose( [
	withState( {
		header: __('Options', 'graphql-api'),
	} ),
	withEditableOnFocus(),
	withCard(),
] )( CustomEndpointOptions );
