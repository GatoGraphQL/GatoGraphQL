/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';

/**
 * If the response contains error(s), return a concatenated error message
 *
 * @param {Object} response A response object from the GraphQL server
 * @return {string|null} The error message or nothing
 */
const maybeGetErrorMessage = (response) => {
	if (response.errors && response.errors.length) {
		return sprintf(
			__(`There were errors connecting to the API: %s`, 'gatographql'),
			response.errors.map(error => error.message).join( __('; ', 'gatographql') )
		);
	}
	return null;
}

export { maybeGetErrorMessage };
