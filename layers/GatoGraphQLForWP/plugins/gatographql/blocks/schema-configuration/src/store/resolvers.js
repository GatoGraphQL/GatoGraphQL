/**
 * Internal dependencies
 */
import { fetchSchemaConfigurations, receiveSchemaConfigurations } from './actions';
import parameters from './parameters'
import { maybeGetErrorMessage } from '@gatographql/components';
import { fetchGraphQLQuery } from '@gatographql/api-fetch';

export const getSchemaConfigurations =
	( variables = parameters.defaultVariables ) =>
	async ( { dispatch } ) => {
		try {
			dispatch( fetchSchemaConfigurations( variables ) );
			const response = await fetchGraphQLQuery(
				GATOGRAPHQL_PLUGIN_OWN_USE_ADMIN_ENDPOINT,
				parameters.query,
				variables
			);
			
			/**
			 * If there were errors when executing the query, return an empty list, and keep the error in the state
			 */
			const maybeErrorMessage = maybeGetErrorMessage(response);
			if (maybeErrorMessage) {
				dispatch( receiveSchemaConfigurations( variables, [], maybeErrorMessage ) );
				return
			}

			const results = response.data?.schemaConfigurations || [];
			dispatch( receiveSchemaConfigurations( variables, results ) );
		} catch {}
	};
