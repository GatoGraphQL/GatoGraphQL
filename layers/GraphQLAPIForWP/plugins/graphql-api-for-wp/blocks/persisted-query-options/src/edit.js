/**
 * Application imports
 */
import PersistedQueryOptions from './persisted-query-options';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<PersistedQueryOptions
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
