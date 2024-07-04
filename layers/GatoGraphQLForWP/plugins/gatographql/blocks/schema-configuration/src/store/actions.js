/**
 * Returns an action object used in setting the schemaConfigurations in the state
 *
 * @param {Array} schemaConfigurations Array of schemaConfigurations received.
 * @param {string} errorMessage Error message if fetching the objects failed
 *
 * @return {Object} Action object.
 */
export function setSchemaConfigurations( schemaConfigurations, errorMessage ) {
	return {
		type: 'SET_SCHEMA_CONFIGURATIONS',
		schemaConfigurations,
		errorMessage,
	};
};

/**
 * Returns an action object used in signalling that the schemaConfigurations must be received.
 *
 * @param {string} query GraphQL query to execute
 *
 * @return {Object} Action object.
 */
export function receiveSchemaConfigurations( query ) {
	return {
		type: 'RECEIVE_SCHEMA_CONFIGURATIONS',
		query,
	};
};
