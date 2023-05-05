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
	receiveGlobalFields,
	setGlobalFields,
	receiveDirectives,
	setDirectives,
} from './action-creators';

import { DIRECTIVE_KINDS } from '../constants/directive-kinds'

/**
 * GraphQL query to fetch the list of types and their fields from the GraphQL schema
 */
import typeFieldsGraphQLQuery from '../../graphql-documents/type-fields.gql';

/**
 * GraphQL query to fetch the global fields from the GraphQL schema
*/
import globalFieldsGraphQLQuery from '../../graphql-documents/global-fields.gql';

/**
 * GraphQL query to fetch the list of directives from the GraphQL schema
 * Fetch only query-type directives, exclude schema-type ones.
 */
import directivesGraphQLQuery from '../../graphql-documents/directives.gql';

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

		const response = yield receiveTypeFields( typeFieldsGraphQLQuery );
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
			typeDescription: element.description,
			fields: element.fields == null ? null : element.fields.map(subelement => subelement.name),
		})) || [];
		return setTypeFields( typeFields );
	},

	/**
	 * Fetch the global fields from the GraphQL server
	 */
	* getGlobalFields() {

		const response = yield receiveGlobalFields( globalFieldsGraphQLQuery );
		/**
		 * If there were erros when executing the query, return an empty list, and keep the error in the state
		 */
		const maybeErrorMessage = maybeGetErrorMessage(response);
		if (maybeErrorMessage) {
			return setGlobalFields( [], maybeErrorMessage );
		}
		/**
		 * Flatten the response to an array containing the global field name directly (extracting them from under the "name" key)
		 */
		const globalFields = response.data?.__schema?.globalFields?.map(element => element.name) || [];
		return setGlobalFields( globalFields );
	},

	/**
	 * Fetch the directives from the GraphQL server
	 */
	* getDirectives() {

		const response = yield receiveDirectives( directivesGraphQLQuery, { directiveKinds: [ DIRECTIVE_KINDS.QUERY ]} );
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
};
