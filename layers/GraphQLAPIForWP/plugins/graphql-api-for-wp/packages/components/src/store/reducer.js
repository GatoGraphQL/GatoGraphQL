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
	}
	return state;
};

export default schemaInstrospection;
