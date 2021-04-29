/**
 * External dependencies
 */
import { fetchGraphQLQuery } from '@graphqlapi/api-fetch';

/**
 * Execute the GraphQL queries
 */
const controls = {
	RECEIVE_CAPABILITIES( action ) {
		return fetchGraphQLQuery(
			GRAPHQL_API_ADMIN_FIXEDSCHEMA_ENDPOINT,
			action.query
		);
	},
};

export default controls;
