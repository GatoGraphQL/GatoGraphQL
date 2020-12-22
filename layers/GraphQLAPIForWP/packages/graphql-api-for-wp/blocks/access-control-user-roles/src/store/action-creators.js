/**
 * Returns an action object used in setting the roles in the state
 *
 * @param {Array} roles Array of roles received.
 * @param {string} errorMessage Error message if fetching the objects failed
 *
 * @return {Object} Action object.
 */
export function setRoles( roles, errorMessage ) {
	return {
		type: 'SET_ROLES',
		roles,
		errorMessage,
	};
};

/**
 * Returns an action object used in signalling that the roles must be received.
 *
 * @param {string} query GraphQL query to execute
 *
 * @return {Object} Action object.
 */
export function receiveRoles( query ) {
	return {
		type: 'RECEIVE_ROLES',
		query,
	};
};
