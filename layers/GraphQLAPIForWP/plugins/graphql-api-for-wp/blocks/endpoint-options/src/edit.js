/**
 * Application imports
 */
import EndpointOptions from './custom-endpoint-options';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<EndpointOptions
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
