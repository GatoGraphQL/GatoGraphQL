/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
/**
 * Internal dependencies
 */
import { maybeGetErrorMessage, DEFAULT_DIRECTIVE_CONDITION_IS_EMPTY } from '@graphqlapi/components';

/**
 * External dependencies
 */
import {
	receiveSchemaConfigurations,
	setSchemaConfigurations,
} from './action-creators';

/**
 * Title to use when the CPT doesn't have a title
 */
const noTitleLabel = __('(No title)', 'graphql-api');

/**
 * GraphQL query to fetch the list of schemaConfigurations from the GraphQL schema
 */
export const FETCH_SCHEMA_CONFIGURATIONS_GRAPHQL_QUERY = `
	query GetSchemaConfigurations {
		schemaConfigurations {
			id
			title @default(
				value: "${ noTitleLabel }",
				condition: ${ DEFAULT_DIRECTIVE_CONDITION_IS_EMPTY }
			)
			# excerpt
		}
	}
`

export default {
	/**
	 * Fetch the schemaConfigurations from the GraphQL server
	 */
	* getSchemaConfigurations() {

		const response = yield receiveSchemaConfigurations( FETCH_SCHEMA_CONFIGURATIONS_GRAPHQL_QUERY );
		/**
		 * If there were erros when executing the query, return an empty list, and keep the error in the state
		 */
		const maybeErrorMessage = maybeGetErrorMessage(response);
		if (maybeErrorMessage) {
			return setSchemaConfigurations( [], maybeErrorMessage );
		}
		/**
		 * Flatten the response to an array containing the schemaConfiguration name directly (extracting them from under the "name" key)
		 */
		const schemaConfigurations = response.data?.schemaConfigurations || [];
		return setSchemaConfigurations( schemaConfigurations );
	},
};
