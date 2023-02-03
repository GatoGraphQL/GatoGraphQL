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
	InfoTooltip,
	withCard,
	withCustomizableConfiguration,
	withEditableOnFocus,
	EditableSelect,
} from '@graphqlapi/components';

const SchemaConfigTagsCard = ( props ) => {
	const {
		possibleTagTaxonomies,
		attributes: {
			includedTagTaxonomies,
		},
	} = props;
	const options = possibleTagTaxonomies.map( customPostType => (
		{
			label: customPostType,
			value: customPostType,
		}
	) );
	/**
	 * React Select expects to pass the same elements from the options as defaultValue,
	 * including the label
	 * { value: ..., label: ... },
	 */
	const defaultValue = includedTagTaxonomies.map( customPostType => (
		{
			label: customPostType,
			value: customPostType,
		}
	) );
	return (
		<>
			<div>
				<span>
					<em>{ __('Included custom post types:', 'graphql-api') }</em>
					<InfoTooltip
						{ ...props }
						text={ __('Select the custom post types that can be queried. A custom post type will be represented by its own type in the schema if available (such as Post or Page) or, otherwise, via GenericCustomPost.', 'graphql-api') }
					/>
				</span>
				<EditableSelect
					{ ...props }
					attributeName="includedTagTaxonomies"
					options={ options }
					defaultValue={ defaultValue }
				/>
			</div>
		</>
	);
}

export default compose( [
	withEditableOnFocus(),
	withState( {
		header: __('Tags', 'graphql-api'),
		className: 'graphql-api-tags',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
	withCustomizableConfiguration(),
] )( SchemaConfigTagsCard );