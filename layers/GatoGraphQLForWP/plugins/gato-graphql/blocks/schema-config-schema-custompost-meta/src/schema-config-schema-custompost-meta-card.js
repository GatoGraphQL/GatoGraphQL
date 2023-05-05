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

const SchemaConfigCustomPostMetaCard = ( props ) => {
	return (
		<SchemaConfigMetaCard
			{ ...props }
			labelEntity={ __('custom posts', 'gato-graphql') }
			labelExampleItem='_edit_last'
			labelExampleEntries={
				[
					'_edit_last',
					'/_edit_.*/',
					'#_edit_([a-zA-Z]*)#',
				]
			}
		/>
	);
}

export default compose( [
	withEditableOnFocus(),
	withState( {
		header: __('Custom Post Meta', 'gato-graphql'),
		className: 'gato-graphql-custompost-meta',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
	withCustomizableConfiguration(),
] )( SchemaConfigCustomPostMetaCard );