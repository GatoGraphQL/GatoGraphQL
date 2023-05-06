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
} from '@gatographql/components';

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
					<em>{ __('Included tag taxonomies:', 'gato-graphql') }</em>
					<InfoTooltip
						{ ...props }
						text={ __('Select the tag taxonomies that can be queried. A tag taxonomy will be represented by its own type in the schema if available (such as PostTag) or, otherwise, via GenericTag.', 'gato-graphql') }
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
		header: __('Tags', 'gato-graphql'),
		className: 'gato-graphql-tags',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
	withCustomizableConfiguration(),
] )( SchemaConfigTagsCard );