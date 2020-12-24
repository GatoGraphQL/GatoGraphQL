/**
 * External dependencies
 */
import { fetchGraphQLQuery } from '@graphqlapi/api-fetch';

/**
 * Execute the GraphQL queries
 */
const controls = {
	RECEIVE_ROLES( action ) {
		return fetchGraphQLQuery( action.query );
	},
};

export default controls;
