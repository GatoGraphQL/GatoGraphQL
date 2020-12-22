/**
 * The initial state of the store
 */
const DEFAULT_STATE = {
	roles: [],
	hasRetrievedRoles: false,
	retrievingRolesErrorMessage: null,
};

/**
 * Reducer returning an array of types and their fields, and roles.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */
const roles = (
	state = DEFAULT_STATE,
	action
) => {
	switch ( action.type ) {
		case 'SET_ROLES':
			return {
				...state,
				roles: action.roles,
				hasRetrievedRoles: true,
				retrievingRolesErrorMessage: action.errorMessage,
			};
	}
	return state;
};

export default roles;
