/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { compose, withState } from '@wordpress/compose';

/**
 * Internal dependencies
 */
import FieldDeprecationListMultiSelectControl from './fdl-multi-select-control';
import FieldDeprecationListPrintout from './fdl-printout';
import { withCard } from '../card'
import { withEditableOnFocus } from '../editable-on-focus'

const FieldDeprecationListEditableOnFocusMultiSelectControl = ( props ) => {
	const { className, isSelected, attributes: { fieldDeprecationLists } } = props;
	return (
		<>
			{ isSelected &&
				<FieldDeprecationListMultiSelectControl
					{ ...props }
					selectedItems={ fieldDeprecationLists }
					className={ className }
				/>
			}
			{ !isSelected && (
				<FieldDeprecationListPrintout
					{ ...props }
					selectedItems={ fieldDeprecationLists }
					className={ className }
				/>
			) }
		</>
	);
}

export default compose( [
	withState( {
		header: __('Field Deprecation Lists', 'graphql-api'),
		className: 'graphql-api-field-deprecation-list-select',
	 } ),
	 withEditableOnFocus(),
	 withCard(),
] )( FieldDeprecationListEditableOnFocusMultiSelectControl );
