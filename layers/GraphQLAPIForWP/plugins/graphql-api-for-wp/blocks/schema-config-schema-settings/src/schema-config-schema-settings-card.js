/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { AllowAccessToEntriesCard } from '@graphqlapi/components';

const SchemaConfigSettingsCard = ( props ) => {
	return (
		<AllowAccessToEntriesCard
			{ ...props }
			entriesHeader={ __('Settings entries:', 'graphql-api') }
			entriesLabelDescIntro={
				__('List of all the option names, to either allow or deny access to, when querying fields <code>optionValue</code>, <code>optionValues</code> and <code>optionObjectValue</code> (one entry per line).', 'graphql-api')
			}
			labelExampleItem='siteurl'
			labelExampleEntries={
				[
					'siteurl',
					'/site.*/',
					'#site([a-zA-Z]*)#',
				]
			}
		/>
	);
}

export default SchemaConfigSettingsCard;