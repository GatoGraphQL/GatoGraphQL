/**
 * Internal dependencies
 */
import { fetchSchemaConfigurations, receiveSchemaConfigurations } from './actions';
import { maybeGetErrorMessage } from '@gatographql/components';
import { fetchGraphQLQuery } from '@gatographql/api-fetch';
/**
 * GraphQL query to fetch the list of schemaConfigurations from the GraphQL schema
 */
import schemaConfigurationsGraphQLQuery from '../../graphql-documents/schema-configurations.gql';


export const getSchemaConfigurations =
	() =>
	async ( { dispatch } ) => {
		const query = schemaConfigurationsGraphQLQuery
		const variables = []
		try {
			dispatch( fetchSchemaConfigurations( variables ) );
			const response = await fetchGraphQLQuery(
				GATOGRAPHQL_PLUGIN_OWN_USE_ADMIN_ENDPOINT,
				query,
				variables
			);
			
			/**
			 * If there were erros when executing the query, return an empty list, and keep the error in the state
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
