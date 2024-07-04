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
 * @param {Array} schemaConfigurations Type fields.
 * @param {string|null} errorMessage Error message if fetching the objects failed
 *
 * @return {Object} Action object.
 */
export function receiveTypeFields( graphQLVariables, schemaConfigurations, errorMessage ) {
	return {
		type: 'RECEIVE_TYPE_FIELDS',
		graphQLVariables,
		schemaConfigurations,
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
 * @param {Array} schemaConfigurations Global fields.
 * @param {string|null} errorMessage Error message if fetching the objects failed
 *
 * @return {Object} Action object.
 */
export function receiveGlobalFields( graphQLVariables, schemaConfigurations, errorMessage ) {
	return {
		type: 'RECEIVE_GLOBAL_FIELDS',
		graphQLVariables,
		schemaConfigurations,
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
 * @param {Array} schemaConfigurations Directives.
 * @param {string|null} errorMessage Error message if fetching the objects failed
 *
 * @return {Object} Action object.
 */
export function receiveDirectives( graphQLVariables, schemaConfigurations, errorMessage ) {
	return {
		type: 'RECEIVE_DIRECTIVES',
		graphQLVariables,
		schemaConfigurations,
		errorMessage,
	};
}
