/**
 * Application imports
 */
import SchemaConfigCategoriesCard from './schema-config-schema-categories-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigCategoriesCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
