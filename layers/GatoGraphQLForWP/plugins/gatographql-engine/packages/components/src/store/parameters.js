/**
 * GraphQL query to fetch the list of typeFields from the GraphQL schema
 */
import typeFieldsGraphQLQuery from '../../graphql-documents/type-fields.gql';
/**
 * GraphQL query to fetch the list of globalFields from the GraphQL schema
 */
import globalFieldsGraphQLQuery from '../../graphql-documents/global-fields.gql';
/**
 * GraphQL query to fetch the list of directives from the GraphQL schema
 */
import directivesGraphQLQuery from '../../graphql-documents/directives.gql';

import { DIRECTIVE_KINDS } from '../constants/directive-kinds'

export default {
	typeFields: {
		query: typeFieldsGraphQLQuery,
		defaultVariables: {}
	},
	globalFields: {
		query: globalFieldsGraphQLQuery,
		defaultVariables: {}
	},
	directives: {
		query: directivesGraphQLQuery,
		defaultVariables: {
			directiveKinds: [ DIRECTIVE_KINDS.QUERY ]
		}
	}
}