/**
 * Returns an action object used in signalling that the schema configurations
 * have been requested and are loading.
 *
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 *
 * @return {Object} Action object.
 */
export function fetchSchemaConfigurations( graphQLVariables ) {
	return { type: 'FETCH_SCHEMA_CONFIGURATIONS', graphQLVariables };
}

/**
 * Returns an action object used in signalling that the schema configurations
 * have been updated.
 *
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 * @param {Array} schemaConfigurations Schema configurations.
 * @param {string|null} errorMessage Error message if fetching the objects failed
 *
 * @return {Object} Action object.
 */
export function receiveSchemaConfigurations( graphQLVariables, schemaConfigurations, errorMessage ) {
	return {
		type: 'RECEIVE_SCHEMA_CONFIGURATIONS',
		graphQLVariables,
		schemaConfigurations,
		errorMessage,
	};
}
