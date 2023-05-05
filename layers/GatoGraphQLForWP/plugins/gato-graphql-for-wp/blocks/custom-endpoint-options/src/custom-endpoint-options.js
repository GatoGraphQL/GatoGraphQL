/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { compose, withState } from '@wordpress/compose';
import { ToggleControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { getModuleDocMarkdownContentOrUseDefault } from './module-doc-markdown-loader';
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
			<em>{ __('Enabled?', 'gato-graphql') }</em>
			{ !isSelected && (
				<>
					<br />
					{ isEnabled ? `✅ ${ __('Yes', 'gato-graphql') }` : `❌ ${ __('No', 'gato-graphql') }` }
				</>
			) }
			{ isSelected &&
				<ToggleControl
					{ ...props }
					label={ isEnabled ? __('Yes', 'gato-graphql') : __('No', 'gato-graphql') }
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
	withEditableOnFocus(),
	withState( {
		header: __('Custom Endpoint Options', 'gato-graphql'),
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
] )( CustomEndpointOptions );
