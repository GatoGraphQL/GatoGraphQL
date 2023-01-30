/**
 * Application imports
 */
import SchemaConfigCustomPostsCard from './schema-config-customposts-card';

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
