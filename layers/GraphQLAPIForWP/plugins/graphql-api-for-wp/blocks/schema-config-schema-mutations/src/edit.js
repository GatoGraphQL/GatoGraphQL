/**
 * Application imports
 */
import SchemaConfigSchemaMutationsCard from './schema-config-schema-mutations-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigSchemaMutationsCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
