/**
 * Application imports
 */
import SchemaConfigCommentMetaCard from './schema-config-schema-comment-meta-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigCommentMetaCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
