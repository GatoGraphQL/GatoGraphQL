/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { compose, withState } from '@wordpress/compose';

/**
 * Internal dependencies
 */
import { getModuleDocMarkdownContentOrUseDefault } from './module-doc-markdown-loader';
import {
	InfoTooltip,
	withCard,
	withCustomizableConfiguration,
	withEditableOnFocus,
} from '@gatographql/components';

const SchemaConfigResponseHeadersCard = ( props ) => {
	const {
		responseHeaders
	} = props;
	return (
		<>
			<em>{ __('Response Headers', 'gato-graphql') }</em>
			<InfoTooltip
				{ ...props }
				text={ __('Custom headers to add to the API response for the corresponding endpoint.', 'gato-graphql') }
			/>
			<EditableArrayTextareaControl
				{ ...props }
				attributeName="responseHeaders"
				values={ responseHeaders }
				help={ __('One header per line, with format: "{header name}: {header value}', 'gato-graphql') }
				rows='10'
			/>
		</>
	);
}

export default compose( [
	withEditableOnFocus(),
	withState( {
		header: __('Response Headers', 'gato-graphql'),
		className: 'gato-graphql-response-headers',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
	withCustomizableConfiguration(),
] )( SchemaConfigResponseHeadersCard );
