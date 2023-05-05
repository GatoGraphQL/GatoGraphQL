/**
 * Application imports
 */
import SchemaConfigUserMetaCard from './schema-config-schema-user-meta-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigUserMetaCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
