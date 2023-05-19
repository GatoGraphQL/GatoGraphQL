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
	withEditableOnFocus,
} from '@gatographql/components';
import GlobalFieldsControl from './global-fields-control';

const SchemaConfigGlobalFieldsCard = ( props ) => {
	return (
		<>
			<em>{ __('Schema exposure:', 'gato-graphql') }</em>
			<InfoTooltip
				{ ...props }
				text={ __('"Default": use value from Settings. "Do not expose" global fields in the schema (even though they\'ll still be there), "Expose them on the Root type only", or "Expose them on all types".', 'gato-graphql') }
			/>
			<GlobalFieldsControl
				{ ...props }
				attributeName="schemaExposure"
			/>
		</>
	);
}

export default compose( [
	withEditableOnFocus(),
	withState( {
		header: __('Global Fields', 'gato-graphql'),
		className: 'gato-graphql-global-fields',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
] )( SchemaConfigGlobalFieldsCard );
