/**
 * Application imports
 */
import { CacheControlListEditableOnFocusMultiSelectControl } from '@graphqlapi/components';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<CacheControlListEditableOnFocusMultiSelectControl
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
