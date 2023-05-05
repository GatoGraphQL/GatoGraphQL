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

const SchemaConfigCategoriesCard = ( props ) => {
	const {
		possibleCategoryTaxonomies,
		attributes: {
			includedCategoryTaxonomies,
		},
	} = props;
	const options = possibleCategoryTaxonomies.map( customPostType => (
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
	const defaultValue = includedCategoryTaxonomies.map( customPostType => (
		{
			label: customPostType,
			value: customPostType,
		}
	) );
	return (
		<>
			<div>
				<span>
					<em>{ __('Included category taxonomies:', 'gato-graphql') }</em>
					<InfoTooltip
						{ ...props }
						text={ __('Select the category taxonomies that can be queried. A category taxonomy will be represented by its own type in the schema if available (such as PostCategory) or, otherwise, via GenericCategory.', 'gato-graphql') }
					/>
				</span>
				<EditableSelect
					{ ...props }
					attributeName="includedCategoryTaxonomies"
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
		header: __('Categories', 'gato-graphql'),
		className: 'gato-graphql-categories',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withCard(),
	withCustomizableConfiguration(),
] )( SchemaConfigCategoriesCard );