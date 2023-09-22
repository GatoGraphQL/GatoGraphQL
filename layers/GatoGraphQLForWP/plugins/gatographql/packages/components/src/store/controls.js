/**
 * External dependencies
 */
import { fetchGraphQLQuery } from '@gatographql/api-fetch';

/**
 * Execute the GraphQL queries
 */
const controls = {
	RECEIVE_TYPE_FIELDS( action ) {
		return fetchGraphQLQuery(
			GATOGRAPHQL_PLUGIN_OWN_USE_ADMIN_ENDPOINT,
			action.query
		);
	},
	RECEIVE_GLOBAL_FIELDS( action ) {
		return fetchGraphQLQuery(
			GATOGRAPHQL_PLUGIN_OWN_USE_ADMIN_ENDPOINT,
			action.query
		);
	},
	RECEIVE_DIRECTIVES( action ) {
		return fetchGraphQLQuery(
			GATOGRAPHQL_PLUGIN_OWN_USE_ADMIN_ENDPOINT,
			action.query,
			action.variables
		);
	},
};

export default controls;
