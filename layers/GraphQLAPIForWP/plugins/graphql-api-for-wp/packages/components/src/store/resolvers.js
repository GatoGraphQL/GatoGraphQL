/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';

/**
 * External dependencies
 */
import {
	receiveTypeFields,
	setTypeFields,
	receiveDirectives,
	setDirectives,
	receiveAccessControlLists,
	setAccessControlLists,
	receiveCacheControlLists,
	setCacheControlLists,
	receiveFieldDeprecationLists,
	setFieldDeprecationLists,
} from './action-creators';

import { DIRECTIVE_TYPE_QUERY } from '../constants/directive-types'

/**
 * GraphQL query to fetch the list of types and their fields from the GraphQL schema
 */
export const FETCH_TYPE_FIELDS_GRAPHQL_QUERY = `
	query GetTypeFields {
		__schema {
			types {
				name
				namespacedName:name(namespaced: true)
				fields(includeDeprecated: true) {
					name
				}
				kind
			}
		}
	}
`;

/**
 * GraphQL query to fetch the list of directives from the GraphQL schema
 * Fetch only query-type directives, exclude schema-type ones.
 */
export const FETCH_DIRECTIVES_GRAPHQL_QUERY = `
	query GetDirectives {
		__schema {
			directives(ofTypes: [ "${ DIRECTIVE_TYPE_QUERY }" ]) {
				name
			}
		}
	}
`

/**
 * GraphQL query to fetch the list of Access Control Lists from the GraphQL schema
 */
export const FETCH_ACCESS_CONTROL_LISTS_GRAPHQL_QUERY = `
	query GetAccessControlLists {
		accessControlLists {
			id
			title
			excerpt
		}
	}
`

/**
 * GraphQL query to fetch the list of Cache Control Lists from the GraphQL schema
 */
export const FETCH_CACHE_CONTROL_LISTS_GRAPHQL_QUERY = `
	query GetCacheControlLists {
		cacheControlLists {
			id
			title
			excerpt
		}
	}
`

/**
 * GraphQL query to fetch the list of Field Deprecation Lists from the GraphQL schema
 */
export const FETCH_FIELD_DEPRECATION_LISTS_GRAPHQL_QUERY = `
	query GetFieldDeprecationLists {
		fieldDeprecationLists {
			id
			title
			excerpt
		}
	}
`

/**
 * If the response contains error(s), return a concatenated error message
 *
 * @param {Object} response A response object from the GraphQL server
 * @return {string|null} The error message or nothing
 */
const maybeGetErrorMessage = (response) => {
	if (response.errors && response.errors.length) {
		return sprintf(
			__(`There were errors connecting to the API: %s`, 'graphql-api'),
			response.errors.map(error => error.message).join( __('; ', 'graphql-api') )
		);
	}
	return null;
}

export { maybeGetErrorMessage };
export default {
	/**
	 * Fetch the typeFields from the GraphQL server
	 *
	 * @param {bool} keepScalarTypes Keep the scalar types in the typeFields object
	 * @param {bool} keepIntrospectionTypes Keep the introspection types (__Type, __Directive, __Field, etc) in the typeFields object
	 */
	* getTypeFields( keepScalarTypes = false, keepIntrospectionTypes = false ) {

		const response = yield receiveTypeFields( FETCH_TYPE_FIELDS_GRAPHQL_QUERY );
		/**
		 * If there were erros when executing the query, return an empty list, and keep the error in the state
		 */
		const maybeErrorMessage = maybeGetErrorMessage(response);
		if (maybeErrorMessage) {
			return setTypeFields( [], maybeErrorMessage );
		}
		/**
		 * Convert the response to an array with this structure:
		 * {
		 *   "typeName": string (where currently is "type.name")
		 *   "typeNamespacedName": string (where currently is "type.namespacedName")
		 *   "fields": array|null (where currently is "type.fields.name")
		 * }
		 */
		const typeFields = response.data?.__schema?.types?.map(element => ({
			typeName: element.name,
			typeNamespacedName: element.namespacedName,
			typeKind: element.kind,
			fields: element.fields == null ? null : element.fields.map(subelement => subelement.name),
		})) || [];
		return setTypeFields( typeFields );
	},

	/**
	 * Fetch the directives from the GraphQL server
	 */
	* getDirectives() {

		const response = yield receiveDirectives( FETCH_DIRECTIVES_GRAPHQL_QUERY );
		/**
		 * If there were erros when executing the query, return an empty list, and keep the error in the state
		 */
		const maybeErrorMessage = maybeGetErrorMessage(response);
		if (maybeErrorMessage) {
			return setDirectives( [], maybeErrorMessage );
		}
		/**
		 * Flatten the response to an array containing the directive name directly (extracting them from under the "name" key)
		 */
		const directives = response.data?.__schema?.directives?.map(element => element.name) || [];
		return setDirectives( directives );
	},

	/**
	 * Fetch the Access Control Lists from the GraphQL server
	 */
	* getAccessControlLists() {

		const response = yield receiveAccessControlLists( FETCH_ACCESS_CONTROL_LISTS_GRAPHQL_QUERY );
		/**
		 * If there were erros when executing the query, return an empty list, and keep the error in the state
		 */
		const maybeErrorMessage = maybeGetErrorMessage(response);
		if (maybeErrorMessage) {
			return setAccessControlLists( [], maybeErrorMessage );
		}
		return setAccessControlLists( response.data?.accessControlLists );
	},

	/**
	 * Fetch the Cache Control Lists from the GraphQL server
	 */
	* getCacheControlLists() {

		const response = yield receiveCacheControlLists( FETCH_CACHE_CONTROL_LISTS_GRAPHQL_QUERY );
		/**
		 * If there were erros when executing the query, return an empty list, and keep the error in the state
		 */
		const maybeErrorMessage = maybeGetErrorMessage(response);
		if (maybeErrorMessage) {
			return setCacheControlLists( [], maybeErrorMessage );
		}
		return setCacheControlLists( response.data?.cacheControlLists );
	},

	/**
	 * Fetch the Field Deprecation Lists from the GraphQL server
	 */
	* getFieldDeprecationLists() {

		const response = yield receiveFieldDeprecationLists( FETCH_FIELD_DEPRECATION_LISTS_GRAPHQL_QUERY );
		/**
		 * If there were erros when executing the query, return an empty list, and keep the error in the state
		 */
		const maybeErrorMessage = maybeGetErrorMessage(response);
		if (maybeErrorMessage) {
			return setFieldDeprecationLists( [], maybeErrorMessage );
		}
		return setFieldDeprecationLists( response.data?.fieldDeprecationLists );
	},
};
