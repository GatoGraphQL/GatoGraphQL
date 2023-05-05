/**
 * Application imports
 */
import CustomEndpointOptions from './custom-endpoint-options';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<CustomEndpointOptions
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
