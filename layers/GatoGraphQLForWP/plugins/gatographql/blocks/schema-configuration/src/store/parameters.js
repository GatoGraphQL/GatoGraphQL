/**
 * GraphQL query to fetch the list of schemaConfigurations from the GraphQL schema
 */
import schemaConfigurationsGraphQLQuery from '../../graphql-documents/schema-configurations.gql';

export default {
	query: schemaConfigurationsGraphQLQuery,
	defaultVariables: {}
}