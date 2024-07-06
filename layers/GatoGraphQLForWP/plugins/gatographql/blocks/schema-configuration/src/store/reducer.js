import parameters from './parameters'

/**
 * WordPress dependencies
 */
import { combineReducers } from '@wordpress/data';

/**
 * Reducer returning an array of schema configurations.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */
export const schemaConfigurations = ( state = {}, action ) => {
	switch ( action.type ) {
		case 'FETCH_SCHEMA_CONFIGURATIONS':
			return {
				...state,
				[ ( action.variables || parameters.defaultVariables ).toString() ]: {
					isRequesting: true,
				},
			};
		case 'RECEIVE_SCHEMA_CONFIGURATIONS':
			return {
				...state,
				[ ( action.variables || parameters.defaultVariables ).toString() ]: {
					results: action.schemaConfigurations,
					errorMessage: action.errorMessage,
					isRequesting: false,
				},
			};
	}
	return state;
};

export default combineReducers( {
	schemaConfigurations,
} );
