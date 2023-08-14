/**
 * Application imports
 */
import SchemaConfigTagsCard from './schema-config-schema-tags-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigTagsCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
