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
	// const metaKeyDesc = __('List of all the meta keys, to either allow or deny access to, when querying fields `metaValue` and `metaValues` on %s (one entry per line).', 'graphql-api')
	// 	.replace('%s', __('custom posts', 'graphql-api'));
	// const headsUpDesc = __('Entries surrounded with "/" or "#" are evaluated as regex (regular expressions).', 'graphql-api');
	// const examples = [
	// 	'_edit_last',
	// 	'/_edit_.*/',
	// 	'#_edit_([a-zA-Z]*)#',
	// ].join('", "');
	// const entryDesc = __('For example, any of these entries match meta key "%1$s": %2$s.', 'graphql-api')
	// 	.replace('%1$s', '_edit_last')
	// 	.replace(
	// 		'%2$s',
	// 		`"${ examples }"`
	// 	);
	// const helpText = `${ metaKeyDesc } ${ headsUpDesc } ${ entryDesc }`;
	return (
		<SchemaConfigMetaCard
			{ ...props }
			labelEntity={ __('custom posts', 'graphql-api') }
			labelExampleMetaKey='_edit_last'
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