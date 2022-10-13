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

const getOperations = () => ["query", "mutation"];

const OperationMultiSelectControl = compose( [
	withState( { attributeName: 'operations' } ),
	withSelect( ( select ) => {
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
			hasRetrievedItems: true,
		};
	} ),
	AddUndefinedSelectedItemIDs,
] )( MultiSelectControl );

export default OperationMultiSelectControl;
