/**
 * Internal dependencies
 */
import {
	fetchTypeFields,
	receiveTypeFields,
	fetchGlobalFields,
	receiveGlobalFields,
	fetchDirectives,
	receiveDirectives,
} from './actions';
import parameters from './parameters'
import { maybeGetErrorMessage } from './utils';
import { fetchGraphQLQuery } from '@gatographql/api-fetch';

export const getTypeFields =
	( variables = parameters.typeFields.defaultVariables ) =>
	async ( { dispatch } ) => {
		try {
			dispatch( fetchTypeFields( variables ) );
			const response = await fetchGraphQLQuery(
				GATOGRAPHQL_PLUGIN_OWN_USE_ADMIN_ENDPOINT,
				parameters.typeFields.query,
				variables
			);
			
			/**
			 * If there were erros when executing the query, return an empty list, and keep the error in the state
			 */
			const maybeErrorMessage = maybeGetErrorMessage(response);
			if (maybeErrorMessage) {
				dispatch( receiveTypeFields( variables, [], maybeErrorMessage ) );
				return
			}

			/**
			 * Convert the response to an array with this structure:
			 * {
			 *   "typeName": string (where currently is "type.name")
			 *   "typeNamespacedName": string (where currently is "type.namespacedName")
			 *   "fields": array|null (where currently is "type.fields.name")
			 * }
			 */
			const results = response.data?.__schema?.types?.map(element => ({
				typeName: element.name,
				typeNamespacedName: element.namespacedName,
				typeKind: element.kind,
				typeDescription: element.description,
				fields: element.fields == null ? null : element.fields.map(subelement => subelement.name),
			})) || [];
			dispatch( receiveTypeFields( variables, results ) );
		} catch {}
	};

export const getGlobalFields =
	( variables = parameters.globalFields.defaultVariables ) =>
	async ( { dispatch } ) => {
		try {
			dispatch( fetchGlobalFields( variables ) );
			const response = await fetchGraphQLQuery(
				GATOGRAPHQL_PLUGIN_OWN_USE_ADMIN_ENDPOINT,
				parameters.globalFields.query,
				variables
			);
			
			/**
			 * If there were erros when executing the query, return an empty list, and keep the error in the state
			 */
			const maybeErrorMessage = maybeGetErrorMessage(response);
			if (maybeErrorMessage) {
				dispatch( receiveGlobalFields( variables, [], maybeErrorMessage ) );
				return
			}

			/**
			 * Flatten the response to an array containing the global field name directly (extracting them from under the "name" key)
			 */
			const results = response.data?.__schema?.globalFields?.map(element => element.name) || [];
			dispatch( receiveGlobalFields( variables, results ) );
		} catch {}
	};

export const getDirectives =
	( variables = parameters.directives.defaultVariables ) =>
	async ( { dispatch } ) => {
		try {
			dispatch( fetchDirectives( variables ) );
			const response = await fetchGraphQLQuery(
				GATOGRAPHQL_PLUGIN_OWN_USE_ADMIN_ENDPOINT,
				parameters.directives.query,
				variables
			);
			
			/**
			 * If there were erros when executing the query, return an empty list, and keep the error in the state
			 */
			const maybeErrorMessage = maybeGetErrorMessage(response);
			if (maybeErrorMessage) {
				dispatch( receiveDirectives( variables, [], maybeErrorMessage ) );
				return
			}

			/**
			 * Flatten the response to an array containing the directive name directly (extracting them from under the "name" key)
			 */
			const results = response.data?.__schema?.directives?.map(element => element.name) || [];
			dispatch( receiveDirectives( variables, results ) );
		} catch {}
	};