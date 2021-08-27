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
			GRAPHQL_API_ADMIN_FIXEDSCHEMA_ENDPOINT,
			action.query
		);
	},
	RECEIVE_DIRECTIVES( action ) {
		return fetchGraphQLQuery(
			GRAPHQL_API_ADMIN_FIXEDSCHEMA_ENDPOINT,
			action.query
		);
	},
	RECEIVE_ACCESS_CONTROL_LISTS( action ) {
		return fetchGraphQLQuery(
			GRAPHQL_API_ADMIN_FIXEDSCHEMA_ENDPOINT,
			action.query
		);
	},
	RECEIVE_CACHE_CONTROL_LISTS( action ) {
		return fetchGraphQLQuery(
			GRAPHQL_API_ADMIN_FIXEDSCHEMA_ENDPOINT,
			action.query
		);
	},
};

export default controls;
