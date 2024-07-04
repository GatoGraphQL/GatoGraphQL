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

const GlobalFieldMultiSelectControl = compose( [
	withState( { attributeName: 'globalFields' } ),
	withSelect( ( select ) => {
		const {
			getGlobalFields,
			hasRetrievedGlobalFields,
			getRetrievingGlobalFieldsErrorMessage,
		} = select ( 'gatographql/components' );
		/**
		 * Convert the global fields array to this structure:
		 * [{group:"Global Fields",title:"globalFieldName",value:"globalFieldName"},...]
		 */
		const items = getGlobalFields().map( globalField => (
			{
				group: __('Global Fields', 'gatographql'),
				title: `${ globalField }`,
				value: globalField,
			}
		) );
		return {
			items,
			isRequestingItems: ! hasRetrievedGlobalFields(),
			errorMessage: getRetrievingGlobalFieldsErrorMessage(),
		};
	} ),
	AddUndefinedSelectedItemIDs,
] )( MultiSelectControl );

export default GlobalFieldMultiSelectControl;
