/**
 * This value is not defined as GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes::CUSTOMIZABLE_CONFIGURATION_DEFAULT,
 * because the default value is not saved in the entry (it's just null)
 * But it is defined here to keep this value DRY,
 * when declaring the state's default value in index.js
 */
const ATTRIBUTE_VALUE_CUSTOMIZABLE_CONFIGURATION_IGNORE = false;
/**
 * GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes::CUSTOMIZABLE_CONFIGURATION_APPLY
 */
const ATTRIBUTE_VALUE_CUSTOMIZABLE_CONFIGURATION_APPLY = true;

export {
	ATTRIBUTE_VALUE_CUSTOMIZABLE_CONFIGURATION_IGNORE,
	ATTRIBUTE_VALUE_CUSTOMIZABLE_CONFIGURATION_APPLY,
};
