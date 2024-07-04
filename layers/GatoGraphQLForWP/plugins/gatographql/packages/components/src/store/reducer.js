import parameters from './parameters'

/**
 * WordPress dependencies
 */
import { combineReducers } from '@wordpress/data';

/**
 * Reducer returning an array of type fields.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */
export const typeFields = ( state = {}, action ) => {
	switch ( action.type ) {
		case 'FETCH_TYPE_FIELDS':
			return {
				...state,
				[ ( action.variables || parameters.typeFields.defaultVariables ).toString() ]: {
					isRequesting: true,
				},
			};
		case 'RECEIVE_TYPE_FIELDS':
			return {
				...state,
				[ ( action.variables || parameters.typeFields.defaultVariables ).toString() ]: {
					results: action.typeFields,
					errorMessage: action.errorMessage,
					isRequesting: false,
				},
			};
	}
	return state;
};

/**
 * Reducer returning an array of global fields.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */
export const globalFields = ( state = {}, action ) => {
	switch ( action.type ) {
		case 'FETCH_GLOBAL_FIELDS':
			return {
				...state,
				[ ( action.variables || parameters.globalFields.defaultVariables ).toString() ]: {
					isRequesting: true,
				},
			};
		case 'RECEIVE_GLOBAL_FIELDS':
			return {
				...state,
				[ ( action.variables || parameters.globalFields.defaultVariables ).toString() ]: {
					results: action.globalFields,
					errorMessage: action.errorMessage,
					isRequesting: false,
				},
			};
	}
	return state;
};

/**
 * Reducer returning an array of directives.
 *
 * @param {Object} state  Current state.
 * @param {Object} action Dispatched action.
 *
 * @return {Object} Updated state.
 */
export const directives = ( state = {}, action ) => {
	switch ( action.type ) {
		case 'FETCH_DIRECTIVES':
			return {
				...state,
				[ ( action.variables || parameters.directives.defaultVariables ).toString() ]: {
					isRequesting: true,
				},
			};
		case 'RECEIVE_DIRECTIVES':
			return {
				...state,
				[ ( action.variables || parameters.directives.defaultVariables ).toString() ]: {
					results: action.directives,
					errorMessage: action.errorMessage,
					isRequesting: false,
				},
			};
	}
	return state;
};

export default combineReducers( {
	typeFields,
	globalFields,
	directives,
} );
