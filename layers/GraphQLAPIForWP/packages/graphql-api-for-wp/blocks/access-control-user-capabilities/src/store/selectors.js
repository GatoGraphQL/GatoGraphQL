/**
 * Get the capabilities from the GraphQL schema
 *
 * @param {Object} state Store state
 *
 * @return {array} The list of capabilities from the GraphQL schema
 */
export function getCapabilities( state ) {
	return state.capabilities;
};

/**
 * Have the capabilities been retrieved from the GraphQL server?
 *
 * @param {Object} state Store state
 *
 * @return {bool} The list of capabilities from the GraphQL schema
 */
export function hasRetrievedCapabilities( state ) {
	return state.hasRetrievedCapabilities;
};

/**
 * Get the error message from retrieving the capabilities from the GraphQL server, if any
 *
 * @param {Object} state Store state
 *
 * @return {string|null} The error message
 */
export function getRetrievingCapabilitiesErrorMessage( state ) {
	return state.retrievingCapabilitiesErrorMessage;
};
