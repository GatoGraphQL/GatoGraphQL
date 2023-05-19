/**
 * Application imports
 */
import SchemaConfigGlobalFieldsCard from './schema-config-global-fields-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigGlobalFieldsCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
