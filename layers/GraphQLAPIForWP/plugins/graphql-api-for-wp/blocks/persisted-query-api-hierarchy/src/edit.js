/**
 * Application imports
 */
import PersistedQueryEndpointAPIHierarchy from './persisted-query-api-hierarchy';

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
