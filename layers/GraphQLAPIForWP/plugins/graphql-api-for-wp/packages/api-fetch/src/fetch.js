/**
 * External dependencies
 */
import { request } from 'graphql-request'

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Execute a GraphQL Query and return its results
 *
 * @param {string} query The GraphQL query to execute
 * @param {Array|null} variables An array of variables and their values
 * @param {string|null} endpoint The endpoint against which to execute the query
 * 
 * @return {Object} The response from the GraphQL server
 */
const fetchGraphQLQuery = (query, variables, endpoint) => {
	/**
	 * If the endpoint is not provided, use the admin endpoint GRAPHQL_API_ADMIN_ENDPOINT
	 */
	const endpointURL = endpoint || GRAPHQL_API_ADMIN_ENDPOINT;
	/**
	 * If there is no endpoint (eg: not passing param endpoint,
	 * and running component outside context of WordPress, so GRAPHQL_API_ADMIN_ENDPOINT is not set)
	 * then return an error message
	 */
	if (!endpointURL) {
		return  {
			errors: [ {
				message: __('No endpoint provided to execute the GraphQL query', 'graphql-api')
			} ]
		};
	}
	/**
	 * Return the response always, both in case of success and error
	 * Add the successful response under key "data", which is stripped by "graphql-request"
	 */
	return request(endpointURL, query, variables)
		.then(response => ({
			data: response
		}))
		.catch(
			/**
			 * If it is a 500 response, return its error message under entry "errors"
			 */
			err => err.response.status == 500 ? {
				errors: [ {
					message: `${ __('[Internal Server Error (500)]:', 'graphql-api') } ${ err.response.message }`
				} ],
			} : err.response
		);
};

/**
 * Exports
 */
export default fetchGraphQLQuery;
