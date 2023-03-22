/**
 * Application imports
 */
import SchemaConfigUseMutationPayloadTypesCard from './schema-config-use-mutation-payload-types-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigUseMutationPayloadTypesCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
