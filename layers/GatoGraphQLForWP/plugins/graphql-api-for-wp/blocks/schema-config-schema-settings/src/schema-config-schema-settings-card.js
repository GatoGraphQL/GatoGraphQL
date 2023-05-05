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
	AllowAccessToEntriesCard,
	withCustomizableConfiguration,
	withEditableOnFocus,
	withCard,
} from '@graphqlapi/components';

const SchemaConfigSettingsCard = ( props ) => {
	return (
		<AllowAccessToEntriesCard
			{ ...props }
			entriesHeader={ __('Settings entries:', 'gato-graphql') }
			entriesLabelDescIntro={
				__('List of all the option names, to either allow or deny access to, when querying fields `optionValue`, `optionValues` and `optionObjectValue` (one entry per line).', 'gato-graphql')
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

export default compose( [
	withEditableOnFocus(),
	withState( {
		header: __('Settings', 'gato-graphql'),
		className: 'gato-graphql-settings',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
	withCustomizableConfiguration(),
] )( SchemaConfigSettingsCard );