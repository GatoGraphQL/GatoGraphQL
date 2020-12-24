/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { maybeGetErrorMessage } from '@graphqlapi/components';

/**
 * External dependencies
 */
import {
	receiveCapabilities,
	setCapabilities,
} from './action-creators';

/**
 * GraphQL query to fetch the list of capabilities from the GraphQL schema
 */
export const FETCH_CAPABILITIES_GRAPHQL_QUERY = `
	query GetCapabilities {
		capabilities
	}
`

export default {
	/**
	 * Fetch the capabilities from the GraphQL server
	 */
	* getCapabilities() {

		const response = yield receiveCapabilities( FETCH_CAPABILITIES_GRAPHQL_QUERY );
		/**
		 * If there were erros when executing the query, return an empty list, and keep the error in the state
		 */
		const maybeErrorMessage = maybeGetErrorMessage(response);
		if (maybeErrorMessage) {
			return setCapabilities( [], maybeErrorMessage );
		}
		return setCapabilities( response.data?.capabilities || [] );
	},
};
