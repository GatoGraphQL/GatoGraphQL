/**
 * Internal dependencies
 */
import { compose } from '@wordpress/compose';
import { withSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { MaybeWithSpinnerPostListPrintout } from '../post-list-multi-select-control';

// /**
//  * Print the selected Access Control Lists.
//  *
//  * @param {Object} props
//  */
// const MaybeWithSpinnerAccessControlListPrintout = ( props ) => {
// 	return (
// 		<MaybeWithSpinnerPostListPrintout
// 			header={ __('Access Control Lists', 'graphql-api') }
// 			{ ...props }
// 		/>
// 	);
// }

export default compose( [
	withSelect( ( select ) => {
		const {
			getAccessControlLists,
			hasRetrievedAccessControlLists,
			getRetrievingAccessControlListsErrorMessage,
		} = select ( 'graphql-api/components' );
		return {
			items: getAccessControlLists(),
			hasRetrievedItems: hasRetrievedAccessControlLists(),
			errorMessage: getRetrievingAccessControlListsErrorMessage(),
		};
	} ),
] )( MaybeWithSpinnerPostListPrintout/*MaybeWithSpinnerAccessControlListPrintout*/ );
