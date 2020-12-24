/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { maybeGetErrorMessage } from '@graphqlapi/components';

/**
 * External dependencies
 */
import {
	receiveRoles,
	setRoles,
} from './action-creators';

/**
 * GraphQL query to fetch the list of roles from the GraphQL schema
 */
export const FETCH_ROLES_GRAPHQL_QUERY = `
	query GetRoles {
		roles {
			name
		}
	}
`

export default {
	/**
	 * Fetch the roles from the GraphQL server
	 */
	* getRoles() {

		const response = yield receiveRoles( FETCH_ROLES_GRAPHQL_QUERY );
		/**
		 * If there were erros when executing the query, return an empty list, and keep the error in the state
		 */
		const maybeErrorMessage = maybeGetErrorMessage(response);
		if (maybeErrorMessage) {
			return setRoles( [], maybeErrorMessage );
		}
		/**
		 * Flatten the response to an array containing the role name directly (extracting them from under the "name" key)
		 */
		const roles = response.data?.roles?.map(element => element.name) || [];
		return setRoles( roles );
	},
};
