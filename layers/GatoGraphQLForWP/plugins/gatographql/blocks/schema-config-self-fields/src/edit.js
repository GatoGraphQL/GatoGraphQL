/**
 * Application imports
 */
import SchemaConfigSelfFieldsCard from './schema-config-self-fields-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigSelfFieldsCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
