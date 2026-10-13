/**
 * Application imports
 */
import SchemaConfigSpecCompliantErrorHandlingCard from './schema-config-spec-compliant-error-handling-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigSpecCompliantErrorHandlingCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
