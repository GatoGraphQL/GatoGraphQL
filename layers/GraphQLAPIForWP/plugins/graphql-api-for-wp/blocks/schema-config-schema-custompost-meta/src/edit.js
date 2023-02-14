/**
 * Application imports
 */
import SchemaConfigCustomPostMetaCard from './schema-config-schema-custompost-meta-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigCustomPostMetaCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
