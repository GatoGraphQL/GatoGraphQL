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

const AccessControlListMultiSelectControl = compose( [
	withState( { attributeName: 'accessControlLists' } ),
	withSelect( ( select ) => {
		const {
			getAccessControlLists,
			hasRetrievedAccessControlLists,
			getRetrievingAccessControlListsErrorMessage,
		} = select ( 'graphql-api/components' );

		/**
		 * Title to use when the CPT doesn't have a title
		 */
		const noTitleLabel = __('(No title)', 'graphql-api');

		/**
		 * Convert the accessControlLists array to this structure:
		 * [{group:"AccessControlLists",title:"accessControlList.title",value:"accessControlList.id",help:"accessControlList.excerpt"},...]
		 */
		const items = getAccessControlLists().map( accessControlList => (
			{
				group: __('Access Control Lists', 'graphql-api'),
				title: accessControlList.title || noTitleLabel,
				value: accessControlList.id,
				help: accessControlList.excerpt,
			}
		) );
		return {
			items,
			hasRetrievedItems: hasRetrievedAccessControlLists(),
			errorMessage: getRetrievingAccessControlListsErrorMessage(),
		};
	} ),
	AddUndefinedSelectedItemIDs,
] )( MultiSelectControl );

export default AccessControlListMultiSelectControl;
