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
} from '@graphqlapi/components';

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
			<em>{ __('Expose GraphiQL client?', 'gato-graphql') }</em>
			<InfoTooltip
				{ ...props }
				text={ __('Publicly available under /{endpoint-slug}/?view=graphiql', 'gato-graphql') }
			/>
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
		header: __('GraphiQL', 'gato-graphql'),
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
] )( EndpointGraphiQL );
