/**
 * Application imports
 */
import PersistedQueryEndpointAPIHierarchy from './persisted-query-endpoint-api-hierarchy';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<PersistedQueryEndpointAPIHierarchy
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
