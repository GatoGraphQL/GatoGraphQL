/**
 * Application imports
 */
import SchemaConfigAdminFieldsCard from './schema-config-admin-fields-card';

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
