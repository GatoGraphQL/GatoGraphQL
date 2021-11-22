/**
 * Application imports
 */
import SchemaConfigAdminFieldsCard from './schema-config-expose-admin-data-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigAdminFieldsCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
