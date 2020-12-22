/**
 * Internal dependencies
 */
import { compose } from '@wordpress/compose';
import { withSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { MaybeWithSpinnerPostListPrintout } from '../post-list-multi-select-control';

// /**
//  * Print the selected Field Deprecation Lists.
//  *
//  * @param {Object} props
//  */
// const MaybeWithSpinnerFieldDeprecationListPrintout = ( props ) => {
// 	return (
// 		<MaybeWithSpinnerPostListPrintout
// 			header={ __('Field Deprecation Lists', 'graphql-api') }
// 			{ ...props }
// 		/>
// 	);
// }

export default compose( [
	withSelect( ( select ) => {
		const {
			getFieldDeprecationLists,
			hasRetrievedFieldDeprecationLists,
			getRetrievingFieldDeprecationListsErrorMessage,
		} = select ( 'graphql-api/components' );
		return {
			items: getFieldDeprecationLists(),
			hasRetrievedItems: hasRetrievedFieldDeprecationLists(),
			errorMessage: getRetrievingFieldDeprecationListsErrorMessage(),
		};
	} ),
] )( MaybeWithSpinnerPostListPrintout/*MaybeWithSpinnerFieldDeprecationListPrintout*/ );
