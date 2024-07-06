import parameters from './parameters'

function getSchemaConfigurationsKey( graphQLVariables ) {
	return (graphQLVariables || parameters.defaultVariables).toString()
}

/**
 * Returns true if application is requesting for schema configurations.
 *
 * @param {Object} state Global application state.
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 *
 * @return {boolean} Whether a request is in progress for the schema configurations.
 */
export function isRequestingSchemaConfigurations( state, graphQLVariables ) {
	const key = getSchemaConfigurationsKey(graphQLVariables)
	return state.schemaConfigurations[ key ]?.isRequesting ?? false;
}

/**
 * Returns the schema configurations.
 *
 * @param {Object} state Global application state.
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 *
 * @return {Array} Schema configurations.
 */
export function getSchemaConfigurations( state, graphQLVariables ) {
	const key = getSchemaConfigurationsKey(graphQLVariables)
	return state.schemaConfigurations[ key ]?.results ?? [];
}

/**
 * Returns the error message from fetching schema configurations.
 *
 * @param {Object} state Global application state.
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 *
 * @return {string|null} Error message, if any.
 */
export function getRetrievingSchemaConfigurationsErrorMessage( state, graphQLVariables ) {
	const key = getSchemaConfigurationsKey(graphQLVariables)
	return state.schemaConfigurations[ key ]?.errorMessage ?? null;
}
