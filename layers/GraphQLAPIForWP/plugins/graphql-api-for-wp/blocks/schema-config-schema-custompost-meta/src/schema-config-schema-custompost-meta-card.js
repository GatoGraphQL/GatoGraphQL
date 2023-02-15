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
} from '@graphqlapi/components';

const SchemaConfigCustomPostMetaCard = ( props ) => {
	return (
		<SchemaConfigMetaCard
			{ ...props }
			labelEntity={ __('custom posts', 'graphql-api') }
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
	withState( {
		header: __('Custom Post Meta', 'graphql-api'),
		className: 'graphql-api-custompost-meta',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
] )( SchemaConfigCustomPostMetaCard );