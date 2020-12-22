/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { compose, withState } from '@wordpress/compose';

/**
 * Internal dependencies
 */
import CacheControlListMultiSelectControl from './ccl-multi-select-control';
import CacheControlListPrintout from './ccl-printout';
import { withCard } from '../card'
import { withEditableOnFocus } from '../editable-on-focus'

const CacheControlListEditableOnFocusMultiSelectControl = ( props ) => {
	const { className, isSelected, attributes: { cacheControlLists } } = props;
	return (
		<>
			{ isSelected &&
				<CacheControlListMultiSelectControl
					{ ...props }
					selectedItems={ cacheControlLists }
					className={ className }
				/>
			}
			{ !isSelected && (
				<CacheControlListPrintout
					{ ...props }
					selectedItems={ cacheControlLists }
					className={ className }
				/>
			) }
		</>
	);
}

export default compose( [
	withState( {
		header: __('Cache Control Lists', 'graphql-api'),
		className: 'graphql-api-cache-control-list-select',
	 } ),
	 withEditableOnFocus(),
	 withCard(),
] )( CacheControlListEditableOnFocusMultiSelectControl );
