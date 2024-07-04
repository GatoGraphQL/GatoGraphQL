import parameters from './parameters'

function getTypeFieldsKey( graphQLVariables ) {
	return (graphQLVariables || parameters.typeFields.defaultVariables).toString()
}

/**
 * Returns true if application is requesting for type fields.
 *
 * @param {Object} state Global application state.
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 *
 * @return {boolean} Whether a request is in progress for the type fields.
 */
export function isRequestingTypeFields( state, graphQLVariables ) {
	const key = getTypeFieldsKey(graphQLVariables)
	return state.typeFields[ key ]?.isRequesting ?? false;
}

/**
 * Returns the type fields.
 *
 * @param {Object} state Global application state.
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 *
 * @return {Array} Schema configurations.
 */
export function getTypeFields( state, graphQLVariables ) {
	const key = getTypeFieldsKey(graphQLVariables)
	return state.typeFields[ key ]?.results ?? [];
}

/**
 * Returns the error message from fetching type fields.
 *
 * @param {Object} state Global application state.
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 *
 * @return {string|null} Error message, if any.
 */
export function getRetrievingTypeFieldsErrorMessage( state, graphQLVariables ) {
	const key = getTypeFieldsKey(graphQLVariables)
	return state.typeFields[ key ]?.errorMessage ?? null;
}


function getGlobalFieldsKey( graphQLVariables ) {
	return (graphQLVariables || parameters.globalFields.defaultVariables).toString()
}

/**
 * Returns true if application is requesting for global fields.
 *
 * @param {Object} state Global application state.
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 *
 * @return {boolean} Whether a request is in progress for the global fields.
 */
export function isRequestingGlobalFields( state, graphQLVariables ) {
	const key = getGlobalFieldsKey(graphQLVariables)
	return state.globalField[ key ]?.isRequesting ?? false;
}

/**
 * Returns the global fields.
 *
 * @param {Object} state Global application state.
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 *
 * @return {Array} Schema configurations.
 */
export function getGlobalFields( state, graphQLVariables ) {
	const key = getGlobalFieldsKey(graphQLVariables)
	return state.globalField[ key ]?.results ?? [];
}

/**
 * Returns the error message from fetching global fields.
 *
 * @param {Object} state Global application state.
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 *
 * @return {string|null} Error message, if any.
 */
export function getRetrievingGlobalFieldsErrorMessage( state, graphQLVariables ) {
	const key = getGlobalFieldsKey(graphQLVariables)
	return state.globalField[ key ]?.errorMessage ?? null;
}


function getDirectivesKey( graphQLVariables ) {
	return (graphQLVariables || parameters.directives.defaultVariables).toString()
}

/**
 * Returns true if application is requesting for directives.
 *
 * @param {Object} state Global application state.
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 *
 * @return {boolean} Whether a request is in progress for the directives.
 */
export function isRequestingDirectives( state, graphQLVariables ) {
	const key = getDirectivesKey(graphQLVariables)
	return state.directives[ key ]?.isRequesting ?? false;
}

/**
 * Returns the directives.
 *
 * @param {Object} state Global application state.
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 *
 * @return {Array} Schema configurations.
 */
export function getDirectives( state, graphQLVariables ) {
	const key = getDirectivesKey(graphQLVariables)
	return state.directives[ key ]?.results ?? [];
}

/**
 * Returns the error message from fetching directives.
 *
 * @param {Object} state Global application state.
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 *
 * @return {string|null} Error message, if any.
 */
export function getRetrievingDirectivesErrorMessage( state, graphQLVariables ) {
	const key = getDirectivesKey(graphQLVariables)
	return state.directives[ key ]?.errorMessage ?? null;
}
