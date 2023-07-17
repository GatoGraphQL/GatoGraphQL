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
	EditableArrayTextareaControl,
	withCard,
	withCustomizableConfiguration,
	withEditableOnFocus,
} from '@gatographql/components';

const SchemaConfigResponseHeadersCard = ( props ) => {
	const {
		attributes
	} = props;
	const entriesAttributeName = "entries";
	const entries = attributes[ entriesAttributeName ];
	return (
		<>
			<em>{ __('Response Headers', 'gato-graphql') }</em>
			<EditableArrayTextareaControl
				{ ...props }
				attributeName={ entriesAttributeName }
				values={ entries }
				help={ __('Provide custom headers to add to the API response. One header per line, with format: `{header name}: {header value}`', 'gato-graphql') }
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
