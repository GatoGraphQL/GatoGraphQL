/**
 * Application imports
 */
import PersistedQueryAPIHierarchy from './persisted-query-api-hierarchy';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<PersistedQueryAPIHierarchy
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
