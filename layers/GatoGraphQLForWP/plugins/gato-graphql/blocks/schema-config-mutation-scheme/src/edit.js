/**
 * Application imports
 */
import SchemaConfigMutationSchemeCard from './schema-config-mutation-scheme-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigMutationSchemeCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
