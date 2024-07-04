/**
 * WordPress dependencies
 */
import { withSelect } from '@wordpress/data';
import { compose, withState } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { getModuleDocMarkdownContentOrUseDefault } from './module-doc-markdown-loader';
import { SelectCard, getLabelForNotFoundElement } from '@gatographql/components';
import {
	ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT,
	ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_NONE,
	ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_INHERIT,
} from './schema-configuration-meta-values'

const SchemaConfigurationSelectCard = ( props ) => {
	const {
		isAPIHierarchyEnabled = true,
		queryPostParent,
		schemaConfigurations,
		attributes: {
			schemaConfiguration
		}
	} = props;
	
	/**
	 * Title to use when the element's title is empty.
	 * (This may not happen: WordPress might then set it as "Untitled")
	 */
	const noTitleLabel = __('(No title)', 'gatographql');

	/**
	 * React Select expects an object with this format:
	 * { value: ..., label: ... },
	 * Convert the schemaConfigurations array to this structure:
	 * [{label:"schemaConfiguration.rawTitle",value:"schemaConfiguration.id"},...]
	 */
	const schemaConfigurationOptions = schemaConfigurations.map( schemaConfiguration => (
		{
			// label: `→ ${ schemaConfiguration.rawTitle }`,
			label: schemaConfiguration.rawTitle || noTitleLabel,
			value: schemaConfiguration.id,
		}
	) );
	/**
	 * If API hierarchy is enabled and this query has a parent,
	 * then add option "Inherit from parent"
	 */
	const enableInheritFromParent = isAPIHierarchyEnabled && ( schemaConfiguration == ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_INHERIT || queryPostParent )
	const metaOptions = ( enableInheritFromParent ?
		[
			{
				label: `🛑 ${ __('Inherit from parent', 'gatographql') }`,
				value: ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_INHERIT,
			}
		]
		: []
	).concat([
		{
			label: `🟡 ${ __('Default', 'gatographql') }`,
			value: ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT,
		},
		{
			label: `❌ ${ __('None', 'gatographql') }`,
			value: ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_NONE,
		},
	])

	const options = metaOptions.concat(
		schemaConfigurationOptions
	);
	const groupedOptions = [
		{
		  label: '',
		  options: metaOptions,
		},
		{
		  label: '',
		  options: schemaConfigurationOptions,
		},
	  ];
	/**
	 * React Select expects to pass the same elements from the options as defaultValue,
	 * including the label: { value: ..., label: ... }
	 * Retrieve this object from the options
	 */
	let defaultValue = null;
	if (schemaConfiguration != null) {
		const selectedOptions = options.filter( option => option.value == schemaConfiguration );
		if (selectedOptions.length) {
			/**
			 * React Select expects to pass the same elements from the options as defaultValue,
			 * including the label: { value: ..., label: ... }
			 */
			defaultValue = selectedOptions[0];
		} else {
			/**
			 * If the defaultValue is not part of the options, it's a stranded ID
			 * (eg: from a deleted custom post)
			 */
			defaultValue = {
				label: getLabelForNotFoundElement(schemaConfiguration),
				value: schemaConfiguration,
			}
		}
	}

	/**
	 * Check if the schema configurations have not been fetched yet,
	 * or if there are selected items (for which we need the data to know the label),
	 * then show the spinner
	 */
	const maybeShowSpinnerOrError = !schemaConfigurations?.length || schemaConfiguration != null;
	return (
		<SelectCard
			{ ...props }
			isMulti={ false }
			attributeName="schemaConfiguration"
			options={ groupedOptions/*options*/ }
			defaultValue={ defaultValue }
			getLabelForNotFoundValueCallback={ getLabelForNotFoundElement }
			maybeShowSpinnerOrError={ maybeShowSpinnerOrError }
		/>
	);
}

// const WithSpinnerSchemaConfiguration = compose( [
// 	withSpinner(),
// 	withErrorMessage(),
// ] )( SchemaConfigurationSelectCard );

// /**
//  * Check if the schema configurations have not been fetched yet,
//  * or if there are selected items (for which we need the data to know the label),
//  * then show the spinner
//  *
//  * @param {Object} props
//  */
// const MaybeWithSpinnerSchemaConfiguration = ( props ) => {
// 	const { schemaConfigurations, attributes: { schemaConfiguration } } = props;
// 	if ( !schemaConfigurations?.length || schemaConfiguration != null ) {
// 		return (
// 			<WithSpinnerSchemaConfiguration { ...props } />
// 		)
// 	}
// 	return (
// 		<SchemaConfigurationSelectCard { ...props } />
// 	);
// }

export default compose( [
	withState( {
		header: __('Schema configuration', 'gatographql'),
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withSelect( ( select ) => {
		const {
			getSchemaConfigurations,
			isRequestingSchemaConfigurations,
			getFetchingSchemaConfigurationsErrorMessage,
		} = select ( 'gatographql/schema-configuration' );
		return {
			schemaConfigurations: getSchemaConfigurations(),
			hasRetrievedItems: ! isRequestingSchemaConfigurations(),
			errorMessage: getFetchingSchemaConfigurationsErrorMessage(),
		};
	} ),
	withSelect( ( select ) => {
		const { getEditedPostAttribute } = select(
			'core/editor'
		);
		return {
			queryPostParent: getEditedPostAttribute( 'parent' ),
		};
	} ),
] )( SchemaConfigurationSelectCard/*MaybeWithSpinnerSchemaConfiguration*/ );
