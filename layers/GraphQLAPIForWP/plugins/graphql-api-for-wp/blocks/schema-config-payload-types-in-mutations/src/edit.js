/**
 * Application imports
 */
import SchemaConfigPayloadTypesInMutationsCard from './schema-config-payload-types-in-mutations-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigPayloadTypesInMutationsCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
