/**
 * Application imports
 */
import SchemaConfigExposeAdminDataCard from './schema-config-expose-sensitive-data-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigExposeAdminDataCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
