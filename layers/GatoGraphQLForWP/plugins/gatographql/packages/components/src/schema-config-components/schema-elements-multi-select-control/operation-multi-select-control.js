/**
 * WordPress dependencies
 */
import { compose, withState } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { SUPPORTED_OPERATION_TYPES } from '../../schema-config-constants/operations'
import MultiSelectControl from '../multi-select-control';
import AddUndefinedSelectedItemIDs from '../multi-select-control/add-undefined-selected-item-ids';

/**
 * Convert the global fields array to this structure:
 * [{group:"Operations",title:"operation",value:"operation"},...]
 */
const items = SUPPORTED_OPERATION_TYPES.map( operation => (
	{
		group: __('Operations', 'gatographql'),
		title: operation,
		value: operation,
	}
) );

const OperationMultiSelectControl = compose( [
	withState( {
		attributeName: 'operations',
		items,
		isRequestingItems: false,
	} ),
	AddUndefinedSelectedItemIDs,
] )( MultiSelectControl );

export default OperationMultiSelectControl;
