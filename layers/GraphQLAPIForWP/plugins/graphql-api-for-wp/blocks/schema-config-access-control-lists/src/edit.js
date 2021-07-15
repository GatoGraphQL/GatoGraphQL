/**
 * Application imports
 */
import { AccessControlListEditableOnFocusMultiSelectControl } from '@graphqlapi/components';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<AccessControlListEditableOnFocusMultiSelectControl
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
