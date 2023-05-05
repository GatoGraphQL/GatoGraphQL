/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';
import { getEditableOnFocusComponentClass } from '../base-styles'
import './style.scss';

/**
 * Display an error message if loading data failed
 */
const withEditableOnFocus = () => createHigherOrderComponent(
	( WrappedComponent ) => ( props ) => {
		const {
			isSelected,
		} = props;
		const componentClassName = `${ getEditableOnFocusComponentClass(isSelected) }`;
		return (
			<div className={ componentClassName }>
				<WrappedComponent
					{ ...props }
				/>
			</div>
		);
	},
	'withEditableOnFocus'
);

export default withEditableOnFocus;
