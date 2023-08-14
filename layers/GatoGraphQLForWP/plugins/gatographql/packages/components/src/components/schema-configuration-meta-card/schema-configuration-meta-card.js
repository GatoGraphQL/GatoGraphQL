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
	} = props;
	return (
		<AllowAccessToEntriesCard
			{ ...props }
			entriesHeader={ __('Meta keys:', 'gatographql') }
			entriesLabelDescIntro={
				__('List of all the meta keys, to either allow or deny access to, when querying fields `metaValue` and `metaValues` on %s (one entry per line).', 'gatographql')
					.replace('%s', labelEntity)
			}
		/>
	);
}

export default SchemaConfigMetaCard;