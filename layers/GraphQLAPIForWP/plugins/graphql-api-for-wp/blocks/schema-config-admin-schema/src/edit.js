/**
 * Application imports
 */
import SchemaConfigAdminSchemaCard from './schema-config-admin-schema-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigAdminSchemaCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
