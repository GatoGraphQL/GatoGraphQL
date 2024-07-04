/**
 * Returns an action object used in signalling that the type fields
 * have been requested and are loading.
 *
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 *
 * @return {Object} Action object.
 */
export function fetchTypeFields( graphQLVariables ) {
	return { type: 'FETCH_TYPE_FIELDS', graphQLVariables };
}

/**
 * Returns an action object used in signalling that the type fields
 * have been updated.
 *
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 * @param {Array} typeFields Type fields.
 * @param {string|null} errorMessage Error message if fetching the objects failed
 *
 * @return {Object} Action object.
 */
export function receiveTypeFields( graphQLVariables, typeFields, errorMessage ) {
	return {
		type: 'RECEIVE_TYPE_FIELDS',
		graphQLVariables,
		typeFields,
		errorMessage,
	};
}

/**
 * Returns an action object used in signalling that the global fields
 * have been requested and are loading.
 *
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 *
 * @return {Object} Action object.
 */
export function fetchGlobalFields( graphQLVariables ) {
	return { type: 'FETCH_GLOBAL_FIELDS', graphQLVariables };
}

/**
 * Returns an action object used in signalling that the global fields
 * have been updated.
 *
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 * @param {Array} globalFields Global fields.
 * @param {string|null} errorMessage Error message if fetching the objects failed
 *
 * @return {Object} Action object.
 */
export function receiveGlobalFields( graphQLVariables, globalFields, errorMessage ) {
	return {
		type: 'RECEIVE_GLOBAL_FIELDS',
		graphQLVariables,
		globalFields,
		errorMessage,
	};
}

/**
 * Returns an action object used in signalling that the directives
 * have been requested and are loading.
 *
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 *
 * @return {Object} Action object.
 */
export function fetchDirectives( graphQLVariables ) {
	return { type: 'FETCH_DIRECTIVES', graphQLVariables };
}

/**
 * Returns an action object used in signalling that the directives
 * have been updated.
 *
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 * @param {Array} directives Directives.
 * @param {string|null} errorMessage Error message if fetching the objects failed
 *
 * @return {Object} Action object.
 */
export function receiveDirectives( graphQLVariables, directives, errorMessage ) {
	return {
		type: 'RECEIVE_DIRECTIVES',
		graphQLVariables,
		directives,
		errorMessage,
	};
}
