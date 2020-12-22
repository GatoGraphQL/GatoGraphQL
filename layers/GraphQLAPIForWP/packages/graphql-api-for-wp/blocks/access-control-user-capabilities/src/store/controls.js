/**
 * External dependencies
 */
import { fetchGraphQLQuery } from '@graphqlapi/api-fetch';

/**
 * Execute the GraphQL queries
 */
const controls = {
	RECEIVE_CAPABILITIES( action ) {
		return fetchGraphQLQuery( action.query );
	},
};

export default controls;
