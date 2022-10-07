
/**
 * Returns an action object used in setting the typeFields object in the state
 *
 * @param {Array} typeFields Array of typeField objects received, where each object has key "type" for the type name, and key "fields" with an array of the type's fields.
 * @param {string} errorMessage Error message if fetching the objects failed
 *
 * @return {Object} Action object.
 */
export function setTypeFields( typeFields, errorMessage ) {
	return {
		type: 'SET_TYPE_FIELDS',
		typeFields,
		errorMessage,
	};
};

/**
 * Returns an action object used in signalling that the typeFields object must be received.
 *
 * @param {string} query GraphQL query to execute
 *
 * @return {Object} Action object.
 */
export function receiveTypeFields( query ) {
	return {
		type: 'RECEIVE_TYPE_FIELDS',
		query,
	};
};

/**
 * Returns an action object used in setting the globalFields object in the state
 *
 * @param {Array} globalFields Array of globalField objects received, where each object has key "type" for the type name, and key "fields" with an array of the type's fields.
 * @param {string} errorMessage Error message if fetching the objects failed
 *
 * @return {Object} Action object.
 */
 export function setGlobalFields( globalFields, errorMessage ) {
	return {
		type: 'SET_GLOBAL_FIELDS',
		globalFields,
		errorMessage,
	};
};

/**
 * Returns an action object used in signalling that the globalFields object must be received.
 *
 * @param {string} query GraphQL query to execute
 *
 * @return {Object} Action object.
 */
export function receiveGlobalFields( query ) {
	return {
		type: 'RECEIVE_GLOBAL_FIELDS',
		query,
	};
};

/**
 * Returns an action object used in setting the directives in the state
 *
 * @param {Array} directives Array of directives received.
 * @param {string} errorMessage Error message if fetching the objects failed
 *
 * @return {Object} Action object.
 */
export function setDirectives( directives, errorMessage ) {
	return {
		type: 'SET_DIRECTIVES',
		directives,
		errorMessage,
	};
};

/**
 * Returns an action object used in signalling that the directives must be received.
 *
 * @param {string} query GraphQL query to execute
 *
 * @return {Object} Action object.
 */
export function receiveDirectives( query ) {
	return {
		type: 'RECEIVE_DIRECTIVES',
		query,
	};
};
