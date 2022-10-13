/**
 * WordPress dependencies
 */
import { compose, withState } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import MultiSelectControl from '../multi-select-control';
import AddUndefinedSelectedItemIDs from '../multi-select-control/add-undefined-selected-item-ids';

const OPERATIONS = ["query", "mutation"];

/**
 * Convert the global fields array to this structure:
 * [{group:"Operations",title:"operation",value:"operation"},...]
 */
const items = OPERATIONS.map( operation => (
	{
		group: __('Operations', 'graphql-api'),
		title: operation,
		value: operation,
	}
) );

const OperationMultiSelectControl = compose( [
	withState( {
		attributeName: 'operations',
		items,
		hasRetrievedItems: true,
	} ),
	AddUndefinedSelectedItemIDs,
] )( MultiSelectControl );

export default OperationMultiSelectControl;
