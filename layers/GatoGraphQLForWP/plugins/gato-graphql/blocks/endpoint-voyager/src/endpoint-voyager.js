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
	InfoTooltip,
} from '@gatographql/components';

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
			<em>{ __('Expose the Interactive Schema client?', 'gatographql') }</em>
			<InfoTooltip
				{ ...props }
				text={ __('Publicly available under /{endpoint-slug}/?view=schema', 'gatographql') }
			/>
			{ !isSelected && (
				<>
					<br />
					{ isEnabled ? `✅ ${ __('Yes', 'gatographql') }` : `❌ ${ __('No', 'gatographql') }` }
				</>
			) }
			{ isSelected &&
				<ToggleControl
					{ ...props }
					label={ isEnabled ? __('Yes', 'gatographql') : __('No', 'gatographql') }
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
		header: __('Interactive Schema', 'gatographql'),
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
] )( EndpointVoyager );
