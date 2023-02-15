/**
 * Application imports
 */
import SchemaConfigSettingsCard from './schema-config-schema-settings-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigSettingsCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
