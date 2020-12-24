/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';

/**
 * Set the access control group to the one passed by prop
 */
const withAccessControlGroup = () => createHigherOrderComponent(
	( WrappedComponent ) => ( props ) => {
		const { setAttributes, accessControlGroup } = props;
		setAttributes(
			{ accessControlGroup: accessControlGroup },
		);
		return (
			<WrappedComponent
				{ ...props }
			/>
		);
	},
	'withAccessControlGroup'
);

export default withAccessControlGroup;
