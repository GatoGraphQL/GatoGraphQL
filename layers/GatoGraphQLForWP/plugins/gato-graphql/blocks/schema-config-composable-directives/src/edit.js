/**
 * Application imports
 */
import SchemaConfigComposableDirectivesCard from './schema-config-composable-directives-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigComposableDirectivesCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
