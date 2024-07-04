/**
 * Returns an action object used in signalling that the schema configurations
 * have been requested and are loading.
 *
 * @param {string} query GraphQL query to execute
 *
 * @return {Object} Action object.
 */
export function fetchSchemaConfigurations( query ) {
	return { type: 'FETCH_SCHEMA_CONFIGURATIONS', query };
}

/**
 * Returns an action object used in signalling that the schema configurations
 * have been updated.
 *
 * @param {string|null} query GraphQL query to execute
 * @param {Array} schemaConfigurations Schema configurations.
 * @param {string|null} errorMessage Error message if fetching the objects failed
 *
 * @return {Object} Action object.
 */
export function receiveSchemaConfigurations( query, schemaConfigurations, errorMessage ) {
	return {
		type: 'RECEIVE_SCHEMA_CONFIGURATIONS',
		query,
		schemaConfigurations,
		errorMessage,
	};
}
