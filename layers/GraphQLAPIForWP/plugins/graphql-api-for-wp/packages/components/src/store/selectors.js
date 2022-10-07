/**
 * Get the types and their fields from the GraphQL schema
 *
 * @param {Object} state Store state
 * @param {bool} keepScalarTypes Keep the scalar types in the typeFields object
 * @param {bool} keepIntrospectionTypes Keep the introspection types (__Type, __Directive, __Field, etc) in the typeFields object
 *
 * @return {array} The list of types and their fields from the GraphQL schema
 */
export function getTypeFields( state, keepScalarTypes = false, keepIntrospectionTypes = false ) {
	/**
	 * Each element in typeFields has this shape:
	 * {
	 *   "typeName": string
	 *   "typeNamespacedName": string
	 *   "fields": array|null
	 * }
	 */
	let { typeFields } = state;

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
};

/**
 * Have the typeFields been retrieved from the GraphQL server?
 *
 * @param {Object} state Store state
 *
 * @return {bool} The list of types and their fields from the GraphQL schema
 */
export function hasRetrievedTypeFields( state ) {
	return state.hasRetrievedTypeFields;
};

/**
 * Get the error message from retrieving the typeFields from the GraphQL server, if any
 *
 * @param {Object} state Store state
 *
 * @return {string|null} The error message
 */
export function getRetrievingTypeFieldsErrorMessage( state ) {
	return state.retrievingTypeFieldsErrorMessage;
};

/**
 * Get the global fields from the GraphQL schema
 *
 * @param {Object} state Store state
 *
 * @return {array} The list of global fields from the GraphQL schema
 */
export function getGlobalFields( state ) {
	return state.globalFields;
};

/**
 * Have the global fields been retrieved from the GraphQL server?
 *
 * @param {Object} state Store state
 *
 * @return {bool} The list of global fields from the GraphQL schema
 */
export function hasRetrievedGlobalFields( state ) {
	return state.hasRetrievedGlobalFields;
};

/**
 * Get the error message from retrieving the global fields from the GraphQL server, if any
 *
 * @param {Object} state Store state
 *
 * @return {string|null} The error message
 */
export function getRetrievingGlobalFieldsErrorMessage( state ) {
	return state.retrievingGlobalFieldsErrorMessage;
};

/**
 * Get the directives from the GraphQL schema
 *
 * @param {Object} state Store state
 *
 * @return {array} The list of directives from the GraphQL schema
 */
export function getDirectives( state ) {
	return state.directives;
};

/**
 * Have the directives been retrieved from the GraphQL server?
 *
 * @param {Object} state Store state
 *
 * @return {bool} The list of directives from the GraphQL schema
 */
export function hasRetrievedDirectives( state ) {
	return state.hasRetrievedDirectives;
};

/**
 * Get the error message from retrieving the directives from the GraphQL server, if any
 *
 * @param {Object} state Store state
 *
 * @return {string|null} The error message
 */
export function getRetrievingDirectivesErrorMessage( state ) {
	return state.retrievingDirectivesErrorMessage;
};
