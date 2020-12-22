/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { compose, withState } from '@wordpress/compose';

/**
 * Internal dependencies
 */
import AccessControlListMultiSelectControl from './acl-multi-select-control';
import AccessControlListPrintout from './acl-printout';
import { withCard } from '../card'
import { withEditableOnFocus } from '../editable-on-focus'

const AccessControlListEditableOnFocusMultiSelectControl = ( props ) => {
	const { isSelected, className, attributes: { accessControlLists } } = props;
	return (
		<>
			{ isSelected &&
				<AccessControlListMultiSelectControl
					{ ...props }
					selectedItems={ accessControlLists }
					className={ className }
				/>
			}
			{ !isSelected && (
				<AccessControlListPrintout
					{ ...props }
					selectedItems={ accessControlLists }
					className={ className }
				/>
			) }
		</>
	);
}

export default compose( [
	withState( {
		header: __('Access Control Lists', 'graphql-api'),
		className: 'graphql-api-access-control-list-select',
	 } ),
	 withEditableOnFocus(),
	 withCard(),
] )( AccessControlListEditableOnFocusMultiSelectControl );
