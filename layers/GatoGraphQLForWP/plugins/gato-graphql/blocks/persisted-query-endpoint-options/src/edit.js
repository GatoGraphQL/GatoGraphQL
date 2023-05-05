/**
 * Application imports
 */
import PersistedQueryEndpointOptions from './persisted-query-endpoint-options';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<PersistedQueryEndpointOptions
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
