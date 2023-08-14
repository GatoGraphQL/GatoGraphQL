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
	SchemaConfigMetaCard,
	withCustomizableConfiguration,
	withEditableOnFocus,
	withCard,
} from '@gatographql/components';

const SchemaConfigUserMetaCard = ( props ) => {
	return (
		<SchemaConfigMetaCard
			{ ...props }
			labelEntity={ __('users', 'gatographql') }
			labelExampleItem='last_name'
			labelExampleEntries={
				[
					'last_name',
					'/last_.*/',
					'#last_([a-zA-Z]*)#',
				]
			}
		/>
	);
}

export default compose( [
	withEditableOnFocus(),
	withState( {
		header: __('User Meta', 'gatographql'),
		className: 'gatographql-user-meta',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
	withCustomizableConfiguration(),
] )( SchemaConfigUserMetaCard );