/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';
import { getEditableOnFocusComponentClass } from '../base-styles'
import '../base-styles/editable-on-focus.scss';

/**
 * Display an error message if loading data failed
 */
const withEditableOnFocus = () => createHigherOrderComponent(
	( WrappedComponent ) => ( props ) => {
		const { className, isSelected } = props;
		const componentClassName = `${ className } ${ getEditableOnFocusComponentClass(isSelected) }`;
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
