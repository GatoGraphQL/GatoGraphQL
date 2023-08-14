/**
 * Application imports
 */
import SchemaConfigExposeSensitiveDataCard from './schema-config-expose-sensitive-data-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigExposeSensitiveDataCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
