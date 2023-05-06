/**
 * Application imports
 */
import SchemaConfigCustomPostsCard from './schema-config-schema-customposts-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigCustomPostsCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
