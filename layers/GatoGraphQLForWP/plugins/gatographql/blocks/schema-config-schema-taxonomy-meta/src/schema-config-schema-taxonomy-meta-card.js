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

const SchemaConfigTaxonomyMetaCard = ( props ) => {
	return (
		<SchemaConfigMetaCard
			{ ...props }
			labelEntity={ __('taxonomies', 'gatographql') }
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
	withEditableOnFocus(),
	withState( {
		header: __('Taxonomy Meta', 'gatographql'),
		className: 'gatographql-taxonomy-meta',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
	withCustomizableConfiguration(),
] )( SchemaConfigTaxonomyMetaCard );