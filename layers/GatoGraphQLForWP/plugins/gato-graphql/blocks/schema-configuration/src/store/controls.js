/**
 * External dependencies
 */
import { fetchGraphQLQuery } from '@gatographql/api-fetch';

/**
 * Execute the GraphQL queries
 */
const controls = {
	RECEIVE_SCHEMA_CONFIGURATIONS( action ) {
		return fetchGraphQLQuery(
			GATO_GRAPHQL_PLUGIN_OWN_USE_ADMIN_ENDPOINT,
			action.query
		);
	},
};

export default controls;
