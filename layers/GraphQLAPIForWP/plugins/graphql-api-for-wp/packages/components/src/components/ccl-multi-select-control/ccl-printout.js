/**
 * Internal dependencies
 */
import { compose } from '@wordpress/compose';
import { withSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { MaybeWithSpinnerPostListPrintout } from '../post-list-multi-select-control';

// /**
//  * Print the selected Cache Control Lists.
//  *
//  * @param {Object} props
//  */
// const MaybeWithSpinnerCacheControlListPrintout = ( props ) => {
// 	return (
// 		<MaybeWithSpinnerPostListPrintout
// 			header={ __('Cache Control Lists', 'graphql-api') }
// 			{ ...props }
// 		/>
// 	);
// }

export default compose( [
	withSelect( ( select ) => {
		const {
			getCacheControlLists,
			hasRetrievedCacheControlLists,
			getRetrievingCacheControlListsErrorMessage,
		} = select ( 'graphql-api/components' );
		return {
			items: getCacheControlLists(),
			hasRetrievedItems: hasRetrievedCacheControlLists(),
			errorMessage: getRetrievingCacheControlListsErrorMessage(),
		};
	} ),
] )( MaybeWithSpinnerPostListPrintout/*MaybeWithSpinnerCacheControlListPrintout*/ );
