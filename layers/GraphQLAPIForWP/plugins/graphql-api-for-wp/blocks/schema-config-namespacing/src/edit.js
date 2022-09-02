/**
 * Application imports
 */
import SchemaConfigNamespacingCard from './schema-config-namespacing-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigNamespacingCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
