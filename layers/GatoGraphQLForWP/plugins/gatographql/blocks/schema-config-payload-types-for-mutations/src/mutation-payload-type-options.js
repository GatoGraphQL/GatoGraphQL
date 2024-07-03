/**
 * This value is not defined as GraphQLByPoP\GraphQLServer\Configuration\MutationPayloadTypeOptions::PAYLOAD_TYPE_DEFAULT,
 * because the default value is not saved in the entry (it's just null)
 * But it is defined here to keep this value DRY,
 * when declaring the state's default value in index.js
 */
const ATTRIBUTE_VALUE_PAYLOAD_TYPE_DEFAULT = 'default';
/**
 * GraphQLByPoP\GraphQLServer\Configuration\MutationPayloadTypeOptions::USE_PAYLOAD_TYPES_FOR_MUTATIONS
 */
const ATTRIBUTE_VALUE_USE_PAYLOAD_TYPES_FOR_MUTATIONS = 'use-payload-types';
/**
 * GraphQLByPoP\GraphQLServer\Configuration\MutationPayloadTypeOptions::USE_AND_QUERY_PAYLOAD_TYPES_FOR_MUTATIONS
 */
const ATTRIBUTE_VALUE_USE_AND_QUERY_PAYLOAD_TYPES_FOR_MUTATIONS = 'use-and-query-payload-types';
/**
 * GraphQLByPoP\GraphQLServer\Configuration\MutationPayloadTypeOptions::DO_NOT_USE_PAYLOAD_TYPES_FOR_MUTATIONS
 */
const ATTRIBUTE_VALUE_DO_NOT_USE_PAYLOAD_TYPES_FOR_MUTATIONS = 'do-not-use-payload-types';

export {
	ATTRIBUTE_VALUE_PAYLOAD_TYPE_DEFAULT,
	ATTRIBUTE_VALUE_USE_PAYLOAD_TYPES_FOR_MUTATIONS,
	ATTRIBUTE_VALUE_USE_AND_QUERY_PAYLOAD_TYPES_FOR_MUTATIONS,
	ATTRIBUTE_VALUE_DO_NOT_USE_PAYLOAD_TYPES_FOR_MUTATIONS,
};
