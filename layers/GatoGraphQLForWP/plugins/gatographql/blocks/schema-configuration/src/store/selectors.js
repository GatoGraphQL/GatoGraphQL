/**
 * GraphQL query to fetch the list of schemaConfigurations from the GraphQL schema
 */
import schemaConfigurationsGraphQLQuery from '../../graphql-documents/schema-configurations.gql';

/**
 * Returns true if application is requesting for schema configurations.
 *
 * @param {Object} state Global application state.
 * @param {string} query GraphQL query to execute
 *
 * @return {boolean} Whether a request is in progress for the schema configurations.
 */
export function isRequestingSchemaConfigurations( state, query = schemaConfigurationsGraphQLQuery ) {
	return state.schemaConfigurations[ query ]?.isRequesting ?? false;
}

/**
 * Returns the schema configurations.
 *
 * @param {Object} state Global application state.
 * @param {string} query GraphQL query to execute
 *
 * @return {Array} Schema configurations.
 */
export function getSchemaConfigurations( state, query = schemaConfigurationsGraphQLQuery ) {
	return state.schemaConfigurations[ query ]?.results ?? [];
}

/**
 * Returns the error message from fetching schema configurations.
 *
 * @param {Object} state Global application state.
 * @param {string} query GraphQL query to execute
 *
 * @return {string|null} Error message, if any.
 */
export function getFetchingSchemaConfigurationsErrorMessage( state, query = schemaConfigurationsGraphQLQuery ) {
	return state.schemaConfigurations[ query ]?.errorMessage ?? null;
}
