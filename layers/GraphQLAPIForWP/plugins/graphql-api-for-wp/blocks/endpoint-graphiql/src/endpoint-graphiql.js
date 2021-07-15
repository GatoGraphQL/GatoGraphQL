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

const EndpointGraphiQL = ( props ) => {
	const {
		isSelected,
		className,
		setAttributes,
		attributes:
		{
			isGraphiQLEnabled,
		}
	} = props;
	return (
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
	);
}

export default compose( [
	withState( {
		header: __('GraphiQL', 'graphql-api'),
	} ),
	withEditableOnFocus(),
	withCard(),
] )( EndpointGraphiQL );
