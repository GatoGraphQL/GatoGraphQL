/**
 * WordPress dependencies
 */
import { withSelect } from '@wordpress/data';
import { compose, withState } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import MultiSelectControl from '../multi-select-control';
import AddUndefinedSelectedItemIDs from '../multi-select-control/add-undefined-selected-item-ids';

const FieldDeprecationListMultiSelectControl = compose( [
	withState( { attributeName: 'fieldDeprecationLists' } ),
	withSelect( ( select ) => {
		const {
			getFieldDeprecationLists,
			hasRetrievedFieldDeprecationLists,
			getRetrievingFieldDeprecationListsErrorMessage,
		} = select ( 'graphql-api/components' );
		/**
		 * Convert the fieldDeprecationLists array to this structure:
		 * [{group:"FieldDeprecationLists",title:"fieldDeprecationList.title",value:"fieldDeprecationList.id",help:"fieldDeprecationList.excerpt"},...]
		 */
		const items = getFieldDeprecationLists().map( fieldDeprecationList => (
			{
				group: __('Field Deprecation Lists', 'graphql-api'),
				title: fieldDeprecationList.title,
				value: fieldDeprecationList.id,
				help: fieldDeprecationList.excerpt,
			}
		) );
		return {
			items,
			hasRetrievedItems: hasRetrievedFieldDeprecationLists(),
			errorMessage: getRetrievingFieldDeprecationListsErrorMessage(),
		};
	} ),
	AddUndefinedSelectedItemIDs,
] )( MultiSelectControl );

export default FieldDeprecationListMultiSelectControl;
