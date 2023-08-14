/**
 * Application imports
 */
import SchemaConfigTaxonomyMetaCard from './schema-config-schema-taxonomy-meta-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigTaxonomyMetaCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
