/**
 * Get the schemaConfigurations from the GraphQL schema
 *
 * @param {Object} state Store state
 *
 * @return {array} The list of schemaConfigurations from the GraphQL schema
 */
export function getSchemaConfigurations( state ) {
	return state.schemaConfigurations;
};

/**
 * Have the schemaConfigurations been retrieved from the GraphQL server?
 *
 * @param {Object} state Store state
 *
 * @return {bool} The list of schemaConfigurations from the GraphQL schema
 */
export function hasRetrievedSchemaConfigurations( state ) {
	return state.hasRetrievedSchemaConfigurations;
};

/**
 * Get the error message from retrieving the schemaConfigurations from the GraphQL server, if any
 *
 * @param {Object} state Store state
 *
 * @return {string|null} The error message
 */
export function getRetrievingSchemaConfigurationsErrorMessage( state ) {
	return state.retrievingSchemaConfigurationsErrorMessage;
};
