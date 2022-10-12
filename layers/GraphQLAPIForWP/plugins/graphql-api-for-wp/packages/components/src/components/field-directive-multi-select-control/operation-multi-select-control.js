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

const OperationMultiSelectControl = compose( [
	withState( { attributeName: 'operations' } ),
	withSelect( ( select ) => {
		const {
			getOperations,
			hasRetrievedOperations,
			getRetrievingOperationsErrorMessage,
		} = select ( 'graphql-api/components' );
		/**
		 * Convert the global fields array to this structure:
		 * [{group:"Operations",title:"operationName",value:"operationName"},...]
		 */
		const items = getOperations().map( operation => (
			{
				group: __('Operations', 'graphql-api'),
				title: `${ operation }`,
				value: operation,
			}
		) );
		return {
			items,
			hasRetrievedItems: hasRetrievedOperations(),
			errorMessage: getRetrievingOperationsErrorMessage(),
		};
	} ),
	AddUndefinedSelectedItemIDs,
] )( MultiSelectControl );

export default OperationMultiSelectControl;
