/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
/**
 * Internal dependencies
 */
import { maybeGetErrorMessage } from '@gatographql/components';

/**
 * External dependencies
 */
import {
	receiveSchemaConfigurations,
	setSchemaConfigurations,
} from './actions';

/**
 * GraphQL query to fetch the list of schemaConfigurations from the GraphQL schema
 */
import schemaConfigurationsGraphQLQuery from '../../graphql-documents/schema-configurations.gql';

export default {
	/**
	 * Fetch the schemaConfigurations from the GraphQL server
	 */
	* getSchemaConfigurations() {

		const response = yield receiveSchemaConfigurations( schemaConfigurationsGraphQLQuery );
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
