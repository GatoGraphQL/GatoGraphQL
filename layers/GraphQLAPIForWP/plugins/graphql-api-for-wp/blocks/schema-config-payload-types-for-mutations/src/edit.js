/**
 * Application imports
 */
import SchemaConfigPayloadTypesForMutationsCard from './schema-config-payload-types-for-mutations-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigPayloadTypesForMutationsCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
