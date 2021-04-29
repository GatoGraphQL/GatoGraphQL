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
const fetchGraphQLQuery = (endpointURL, query, variables) => {
	/**
	 * Validate the needed data has been provided
	 */
	if (!endpointURL || !query) {
		const errorMessage = !endpointURL ?
			__('No endpoint provided to execute the GraphQL query', 'graphql-api')
			: __('No GraphQL query provided', 'graphql-api');
		return  {
			errors: [ {
				message: errorMessage
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
