/**
 * The initial state of the store
 */
const DEFAULT_STATE = {
	schemaConfigurations: [],
	hasRetrievedSchemaConfigurations: false,
	retrievingSchemaConfigurationsErrorMessage: null,
};

/**
 * Reducer returning an array of types and their fields, and schemaConfigurations.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */
const schemaConfigurations = (
	state = DEFAULT_STATE,
	action
) => {
	switch ( action.type ) {
		case 'SET_SCHEMA_CONFIGURATIONS':
			return {
				...state,
				schemaConfigurations: action.schemaConfigurations,
				hasRetrievedSchemaConfigurations: true,
				retrievingSchemaConfigurationsErrorMessage: action.errorMessage,
			};
	}
	return state;
};

export default schemaConfigurations;
