/**
 * This value is not defined as GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes::MUTATION_SCHEME_DEFAULT,
 * because the default value is not saved in the entry (it's just null)
 * But it is defined here to keep this value DRY,
 * when declaring the state's default value in index.js
 */
const ATTRIBUTE_VALUE_MUTATION_SCHEME_DEFAULT = 'default';
/**
 * GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes::MUTATION_SCHEME_STANDARD
 */
const ATTRIBUTE_VALUE_MUTATION_SCHEME_STANDARD = 'standard';
/**
 * GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes::MUTATION_SCHEME_NESTED_WITH_REDUNDANT_ROOT_FIELDS
 */
const ATTRIBUTE_VALUE_MUTATION_SCHEME_NESTED_WITH_REDUNDANT_ROOT_FIELDS = 'nested';
/**
 * GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes::MUTATION_SCHEME_NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS
 */
const ATTRIBUTE_VALUE_MUTATION_SCHEME_NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS = 'lean_nested';

export {
	ATTRIBUTE_VALUE_MUTATION_SCHEME_DEFAULT,
	ATTRIBUTE_VALUE_MUTATION_SCHEME_STANDARD,
	ATTRIBUTE_VALUE_MUTATION_SCHEME_NESTED_WITH_REDUNDANT_ROOT_FIELDS,
	ATTRIBUTE_VALUE_MUTATION_SCHEME_NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS,
};
