/**
 * Returns an action object used in setting the capabilities in the state
 *
 * @param {Array} capabilities Array of capabilities received.
 * @param {string} errorMessage Error message if fetching the objects failed
 *
 * @return {Object} Action object.
 */
export function setCapabilities( capabilities, errorMessage ) {
	return {
		type: 'SET_CAPABILITIES',
		capabilities,
		errorMessage,
	};
};

/**
 * Returns an action object used in signalling that the capabilities must be received.
 *
 * @param {string} query GraphQL query to execute
 *
 * @return {Object} Action object.
 */
export function receiveCapabilities( query ) {
	return {
		type: 'RECEIVE_CAPABILITIES',
		query,
	};
};
