/**
 * External dependencies
 */
import { fetchGraphQLQuery } from '@graphqlapi/api-fetch';

/**
 * Execute the GraphQL queries
 */
const controls = {
	RECEIVE_TYPE_FIELDS( action ) {
		return fetchGraphQLQuery(
			GRAPHQL_API_PLUGIN_INTERNAL_WP_EDITOR_ADMIN_ENDPOINT,
			action.query
		);
	},
	RECEIVE_GLOBAL_FIELDS( action ) {
		return fetchGraphQLQuery(
			GRAPHQL_API_PLUGIN_INTERNAL_WP_EDITOR_ADMIN_ENDPOINT,
			action.query
		);
	},
	RECEIVE_DIRECTIVES( action ) {
		return fetchGraphQLQuery(
			GRAPHQL_API_PLUGIN_INTERNAL_WP_EDITOR_ADMIN_ENDPOINT,
			action.query
		);
	},
};

export default controls;
