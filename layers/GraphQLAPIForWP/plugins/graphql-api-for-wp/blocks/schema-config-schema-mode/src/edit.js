/**
 * Application imports
 */
import SchemaConfigSchemaModeCard from './schema-config-schema-mode-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigSchemaModeCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
