/**
 * Application imports
 */
import EditBody from './edit-body';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<EditBody
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
