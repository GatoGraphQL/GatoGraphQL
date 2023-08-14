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

const EndpointGraphiQL = ( props ) => {
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
		<div className={ `${ className }__graphiql_enabled` }>
			<em>{ __('Expose GraphiQL client?', 'gatographql') }</em>
			<InfoTooltip
				{ ...props }
				text={ __('Publicly available under /{endpoint-slug}/?view=graphiql', 'gatographql') }
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
		header: __('GraphiQL', 'gatographql'),
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
] )( EndpointGraphiQL );
