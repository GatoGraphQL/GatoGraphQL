/**
 * The initial state of the store
 */
const DEFAULT_STATE = {
	typeFields: [],
	hasRetrievedTypeFields: false,
	retrievingTypeFieldsErrorMessage: null,
	directives: [],
	hasRetrievedDirectives: false,
	retrievingDirectivesErrorMessage: null,
	accessControlLists: [],
	hasRetrievedAccessControlLists: false,
	retrievingAccessControlListsErrorMessage: null,
	cacheControlLists: [],
	hasRetrievedCacheControlLists: false,
	retrievingCacheControlListsErrorMessage: null,
	fieldDeprecationLists: [],
	hasRetrievedFieldDeprecationLists: false,
	retrievingFieldDeprecationListsErrorMessage: null,
};

/**
 * Reducer returning an array of types and their fields, and directives.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */
const schemaInstrospection = (
	state = DEFAULT_STATE,
	action
) => {
	switch ( action.type ) {
		case 'SET_TYPE_FIELDS':
			return {
				...state,
				typeFields: action.typeFields,
				hasRetrievedTypeFields: true,
				retrievingTypeFieldsErrorMessage: action.errorMessage,
			};
		case 'SET_DIRECTIVES':
			return {
				...state,
				directives: action.directives,
				hasRetrievedDirectives: true,
				retrievingDirectivesErrorMessage: action.errorMessage,
			};
		case 'SET_ACCESS_CONTROL_LISTS':
			return {
				...state,
				accessControlLists: action.accessControlLists,
				hasRetrievedAccessControlLists: true,
				retrievingAccessControlListsErrorMessage: action.errorMessage,
			};
		case 'SET_CACHE_CONTROL_LISTS':
			return {
				...state,
				cacheControlLists: action.cacheControlLists,
				hasRetrievedCacheControlLists: true,
				retrievingCacheControlListsErrorMessage: action.errorMessage,
			};
		case 'SET_FIELD_DEPRECATION_LISTS':
			return {
				...state,
				fieldDeprecationLists: action.fieldDeprecationLists,
				hasRetrievedFieldDeprecationLists: true,
				retrievingFieldDeprecationListsErrorMessage: action.errorMessage,
			};
	}
	return state;
};

export default schemaInstrospection;
