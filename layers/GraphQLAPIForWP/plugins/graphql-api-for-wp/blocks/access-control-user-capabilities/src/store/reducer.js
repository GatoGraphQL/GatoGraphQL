/**
 * The initial state of the store
 */
const DEFAULT_STATE = {
	capabilities: [],
	hasRetrievedCapabilities: false,
	retrievingCapabilitiesErrorMessage: null,
};

/**
 * Reducer returning an array of types and their fields, and capabilities.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */
const capabilities = (
	state = DEFAULT_STATE,
	action
) => {
	switch ( action.type ) {
		case 'SET_CAPABILITIES':
			return {
				...state,
				capabilities: action.capabilities,
				hasRetrievedCapabilities: true,
				retrievingCapabilitiesErrorMessage: action.errorMessage,
			};
	}
	return state;
};

export default capabilities;
