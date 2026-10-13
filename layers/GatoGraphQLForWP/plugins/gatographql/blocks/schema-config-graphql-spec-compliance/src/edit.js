/**
 * Application imports
 */
import SchemaConfigGraphQLSpecComplianceCard from './schema-config-graphql-spec-compliance-card';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<SchemaConfigGraphQLSpecComplianceCard
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
