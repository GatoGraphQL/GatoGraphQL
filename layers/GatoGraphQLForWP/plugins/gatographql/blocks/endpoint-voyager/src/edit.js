/**
 * Application imports
 */
import EndpointVoyager from './endpoint-voyager';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<EndpointVoyager
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
