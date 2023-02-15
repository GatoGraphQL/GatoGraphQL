/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { AllowAccessToEntriesCard } from '../allow-access-to-entries-card';

const SchemaConfigMetaCard = ( props ) => {
	const {
		labelEntity,
		labelExampleMetaKey,
		labelExampleEntries,
	} = props;
	const metaKeyDesc = __('List of all the meta keys, to either allow or deny access to, when querying fields `metaValue` and `metaValues` on %s (one entry per line).', 'graphql-api')
		.replace('%s', labelEntity);
	const headsUpDesc = __('Entries surrounded with "/" or "#" are evaluated as regex (regular expressions).', 'graphql-api');
	const examples = labelExampleEntries.join('", "');
	const entryDesc = __('For example, meta key "%1$s" is matched by any of the following entries: %2$s.', 'graphql-api')
		.replace('%1$s', labelExampleMetaKey)
		.replace(
			'%2$s',
			`"${ examples }"`
		);
	const entriesLabel = `${ metaKeyDesc } ${ headsUpDesc } ${ entryDesc }`;
	return (
		<AllowAccessToEntriesCard
			{ ...props }
			entriesHeader={ __('Meta keys:', 'graphql-api') }
			entriesLabel={ entriesLabel }
		/>
	);
}

export default SchemaConfigMetaCard;