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
 * @param {bool} keepScalarTypes Keep the scalar types in the typeFields object
 * @param {bool} keepIntrospectionTypes Keep the introspection types (__Type, __Directive, __Field, etc) in the typeFields object
 *
 * @return {Array} Type fields.
 */
export function getTypeFields( state, graphQLVariables, keepScalarTypes = false, keepIntrospectionTypes = false ) {
	const key = getTypeFieldsKey(graphQLVariables)
	
	/**
	 * Each element in typeFields has this shape:
	 * {
	 *   "typeName": string
	 *   "typeNamespacedName": string
	 *   "fields": array|null
	 * }
	 */
	let typeFields = state.typeFields[ key ]?.results ?? [];

	/**
	 * Scalar types are those with no fields
	 */
	if ( !keepScalarTypes ) {
		typeFields = typeFields.filter(element => element.fields != null);
	}

	/**
	 * Introspection types (eg: __Schema, __Directive, __Type, etc) start with "__"
	 */
	if ( !keepIntrospectionTypes ) {
		typeFields = typeFields.filter(element => !element.typeName.startsWith('__'));
	}

	return typeFields;
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
	return state.globalFields[ key ]?.isRequesting ?? false;
}

/**
 * Returns the global fields.
 *
 * @param {Object} state Global application state.
 * @param {Array} graphQLVariables Variables to customize the result of executing the GraphQL query (if any is needed).
 *
 * @return {Array} Global fields.
 */
export function getGlobalFields( state, graphQLVariables ) {
	const key = getGlobalFieldsKey(graphQLVariables)
	return state.globalFields[ key ]?.results ?? [];
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
	return state.globalFields[ key ]?.errorMessage ?? null;
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
 * @return {Array} Directives.
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
