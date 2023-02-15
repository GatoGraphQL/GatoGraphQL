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

const SchemaConfigTaxonomyMetaCard = ( props ) => {
	return (
		<SchemaConfigMetaCard
			{ ...props }
			labelEntity={ __('taxonomies', 'graphql-api') }
			labelExampleItem='description'
			labelExampleEntries={
				[
					'description',
					'/desc.*/',
					'#desc([a-zA-Z]*)#',
				]
			}
		/>
	);
}

export default compose( [
	withState( {
		header: __('Taxonomy Meta', 'graphql-api'),
		className: 'graphql-api-taxonomy-meta',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
] )( SchemaConfigTaxonomyMetaCard );