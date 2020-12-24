/**
 * Get the roles from the GraphQL schema
 *
 * @param {Object} state Store state
 *
 * @return {array} The list of roles from the GraphQL schema
 */
export function getRoles( state ) {
	return state.roles;
};

/**
 * Have the roles been retrieved from the GraphQL server?
 *
 * @param {Object} state Store state
 *
 * @return {bool} The list of roles from the GraphQL schema
 */
export function hasRetrievedRoles( state ) {
	return state.hasRetrievedRoles;
};

/**
 * Get the error message from retrieving the roles from the GraphQL server, if any
 *
 * @param {Object} state Store state
 *
 * @return {string|null} The error message
 */
export function getRetrievingRolesErrorMessage( state ) {
	return state.retrievingRolesErrorMessage;
};
